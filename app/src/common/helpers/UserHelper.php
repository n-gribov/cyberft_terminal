<?php
namespace common\helpers;

use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use common\base\BaseBlock;
use common\commands\CommandAR;
use common\document\DocumentPermission;
use common\models\BaseUserExt;
use common\models\CommonUserExt;
use common\models\Terminal;
use common\models\User;
use common\models\UserTerminal;
use common\modules\monitor\models\MonitorLogAR;
use RuntimeException;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class UserHelper
{
    private static $roleInitiatorTypes = [
        User::ROLE_ADMIN => MonitorLogAR::INITIATOR_TYPE_ADMIN,
        User::ROLE_ADDITIONAL_ADMIN => MonitorLogAR::INITIATOR_TYPE_ADDITIONAL_ADMIN,
        User::ROLE_USER => MonitorLogAR::INITIATOR_TYPE_USER,
        User::ROLE_RSO => MonitorLogAR::INITIATOR_TYPE_RSO,
        User::ROLE_LSO => MonitorLogAR::INITIATOR_TYPE_LSO
    ];

    public static function canUpdateProfile($userId)
    {
        $user = User::findOne(['id' => $userId]);
        if ($user) {
            if (!$user->isSecurityOfficer()
                && in_array($user->status, [
                    User::STATUS_ACTIVE,
                    User::STATUS_ACTIVATING,
                    User::STATUS_INACTIVE,
                    User::STATUS_APPROVE,
                    User::STATUS_ACTIVATED,
                ])
            ) {
                return true;
            }
        }

        return false;
    }

    public static function sendUserToSecurityOfficersAcceptance($userId)
    {
        if ($user = User::findOne(['id' => $userId])) {
            if ($user->status !== User::STATUS_INACTIVE) {
                if ($user->status == User::STATUS_ACTIVATING) {
                    self::addActivateCommand($userId);
                } else {
                    self::addSettingApproveCommand($userId);
                }
            }
        }

        return true;
    }

    public static function addSettingApproveCommand($userId)
    {
        if ($user = User::findOne(['id' => $userId])) {
            if (User::canUseSecurityOfficers()) {
                $params = [
                    'code' => 'UserSettingApprove',
                    'entity' => 'user',
                    'entityId' => $userId,
                    'userId' => Yii::$app->user->id,
                ];

                $lastCommand = Yii::$app->commandBus->findCommandId('UserSettingApprove', [
                    'entityId' => $userId,
                    'status' => CommandAR::STATUS_FOR_ACCEPTANCE
                ]);

                if ($result = Yii::$app->commandBus->addCommand(Yii::$app->user->id, 'UserSettingApprove', $params)) {
                    if ($user->updateStatus(User::STATUS_APPROVE)) {
                        if ($lastCommand) {
                            Yii::$app->commandBus->cancelCommand($lastCommand->id);
                        }
                        // Поместить в сессию флаг сообщения об условиях разблокировки пользователя
                        Yii::$app->session->setFlash('info', Yii::t('app/user', 'User will be unblocked after security officers\' confirmation'));

                        return true;
                    }
                }
            }
        }

        return false;
    }

    public static function addActivateCommand($userId)
    {
        if ($user = User::findOne(['id' => $userId])) {
            $params = [
                'code'     => 'UserActivate',
                'entity'   => 'user',
                'entityId' => $userId,
                'userId' => Yii::$app->user->id,
            ];

            $lastCommand = Yii::$app->commandBus->findCommandId('UserActivate', [
                'entityId' => $userId,
                'status' => CommandAR::STATUS_FOR_ACCEPTANCE
            ]);

            if ($result = Yii::$app->commandBus->addCommand(Yii::$app->user->id, 'UserActivate', $params)) {
                if ($user->updateStatus(User::STATUS_ACTIVATING)) {
                    if ($lastCommand) {
                        Yii::$app->commandBus->cancelCommand($lastCommand->id);
                    }

                    if (!User::canUseSecurityOfficers()) {
                        // Поместить в сессию флаг сообщения о статусе пользователя
                        Yii::$app->session->setFlash('info', Yii::t('app/user', 'User is awaiting activation'));
                    } else {
                        // Поместить в сессию флаг сообщения о статусе пользователя
                        Yii::$app->session->setFlash('info', Yii::t('app/user', 'User will be activated after security officers\' confirmation!'));
                    }

                    return true;
                } else {
                    // Поместить в сессию флаг сообщения ою ошибке активации пользователя
                    Yii::$app->session->setFlash('error', Yii::t('app/user', 'Failed to start the activation process!'));
                }
            }
        }

        return false;
    }

    public static function getEventInitiatorType($user)
    {
        $identity = $user->identity;

        if ($identity && isset(static::$roleInitiatorTypes[$identity->role])) {
            return static::$roleInitiatorTypes[$identity->role];
        }

        return null;
    }

    /**
     * Получение списка доступных дополнительному администратору
     * пользователей до доступным ему терминалам
     */
    public static function getUsersAdditionalAdmin($userIdentity)
    {
        $users = [];

        // Пользователь не найден
        if (!$userIdentity) {
            return $users;
        }

        // Пользователь не админ
        if ($userIdentity->role != User::ROLE_ADMIN && $userIdentity->role != User::ROLE_ADDITIONAL_ADMIN) {
            return $users;
        }

        // Список доступных пользователю терминалов
        if ($userIdentity->terminalId) {
            // Если у пользователя выбран конкретный терминал, фильтруем только по нему
            $terminalList = $userIdentity->terminalId;
        } else {
            $terminals = $userIdentity->getTerminals()->select('terminalId')->asArray()->all();
            $terminalList = ArrayHelper::getColumn($terminals, 'terminalId');
        }

        // Список пользователей (кроме админов), которым доступен этот терминал
        $userTerminals = UserTerminal::find()
                ->from(UserTerminal::tableName() . ' ust')
                ->select(['userId'])
                ->innerJoin(User::tableName() . ' usr',
                        ['and',
                            'usr.id = ust.userId',
                            ['!=', 'usr.role', User::ROLE_ADMIN]
                        ])
                ->where(['ust.terminalId' => $terminalList])
                ->asArray()
                ->all();


        return ArrayHelper::getColumn($userTerminals, 'userId');
    }

    /**
     * Получение всех доступных администратору терминалов
     */
    public static function getAdminAvailableTerminalsForSelect($userId)
    {
        // Получить модель пользователя из активной сессии
        $adminIdentity = Yii::$app->user->identity;

        // Список доступных текущему администратору терминалов
        if ($adminIdentity->role == User::ROLE_ADMIN) {

            // Главный администратор
            $allTerminals = Terminal::find()
                          ->where(['status' => Terminal::STATUS_ACTIVE])
                          ->select('id')
                          ->asArray()
                          ->all();

            $allTerminalsList = ArrayHelper::getColumn($allTerminals, 'id');
        } else {
            // Дополнительный администратор
            // Только выбранные администратора терминалы
            $allTerminals = UserTerminal::find()
                               ->where(['userId' => $adminIdentity])
                               ->select('terminalId')
                               ->asArray()
                               ->all();

            $allTerminalsList = ArrayHelper::getColumn($allTerminals, 'terminalId');
        }

        // Список доступных пользователю терминалов
        $userTerminals = UserTerminal::find()
                         ->where(['userId' => $userId])
                         ->select('terminalId')
                         ->asArray()->all();
        $userTerminalsList = ArrayHelper::getColumn($userTerminals, 'terminalId');


        // Общий список терминалов, которые не нужно считать доступными для выбора
        $query = Terminal::find();

        $terms = [];

        foreach($allTerminalsList as $terminal) {
            if (!in_array($terminal, $userTerminals)) {
                $terms[] = $terminal;
            }
        }

        $query->andWhere(['id' => $terms]);
        $availableTerminals = $query->all();

        return $availableTerminals;
    }

    /**
     * Список администраторов терминала
     */
    public static function getAdmins()
    {
        return User::findAll(['role' => [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN]]);
    }

    /**
     * Проверяет возможность
     * взаимодействия с профилем пользователя
     */
    public static function userProfileAccess($user, $ownerPermission = false)
    {
        $isAccess = false;
        // Получить модель пользователя из активной сессии
        $adminIdentity = Yii::$app->user->identity;

        if (in_array($adminIdentity->role, [User::ROLE_ADMIN, User::ROLE_LSO, User::ROLE_RSO])
            && !$user->isSecurityOfficer()
        ) {
            // Главному администратору и офицерам безопасности доступны все пользователи
            $isAccess = true;
        } else {
            // Дополнительному администратору доступны
            // только пользователи доступных ему терминалов
            $usersList = UserHelper::getUsersAdditionalAdmin($adminIdentity);

            if ($user->id == $adminIdentity->id) {
                // Собственный профиль можно редактировать без ограничений
                $isAccess = true;
            } else if ((in_array($user->id, $usersList))) {
                if ($ownerPermission) {
                    // Проверить, что пользователь
                    // принадлежит данному администратору
                    if ($user->ownerId === $adminIdentity->id) {
                        $isAccess = true;
                    }
                } else {
                    $isAccess = true;
                }
            }
        }

        return $isAccess;
    }

    /**
     * Получение счетов пользователя,
     * которые доступны ему по признаку принадлежности к терминалам
     */
    public static function getUserAccounts($user)
    {
        // Получаем терминалы пользователя
        $terminals = $user->getTerminals()->select('terminalId')->asArray()->all();
        $terminals = ArrayHelper::getColumn($terminals, 'terminalId');

        // Получаем организации, которые доступны пользователю
        $organizations = DictOrganization::find()
            ->select('id')
            ->where(['terminalId' => $terminals])
            ->asArray()
            ->all();
        $organizations = ArrayHelper::getColumn($organizations, 'id');

        // Получаем счета, доступные пользователю
        $accounts = EdmPayerAccount::find()
            ->with('edmDictOrganization', 'edmDictCurrencies')
            ->where(['organizationId' => $organizations])
            ->all();

        return $accounts;
    }

    /**
     * Получение списка счетов пользователя
     */
    public static function getViewableUserAccounts($user)
    {
        // Счета пользователя
        $accounts = static::getUserAccounts($user);

        $accountsArray = [];

        foreach ($accounts as $account) {
            $accountsArray[] = [
                'id' => $account->id,
                'organization' => $account->edmDictOrganization->name,
                'name' => $account->name,
                'number' => $account->number,
                'bankName' => $account->bank->name,
                'currency' => $account->edmDictCurrencies->name,
                'allow' => EdmPayerAccountUser::isUserAllowAccount($user->id, $account->id),
                'canSignDocuments' => EdmPayerAccountUser::userCanSingDocuments($user->id, $account->id),
            ];
        }

        // Сортировка счета по имени организации
        uasort($accountsArray, function($a, $b) {
            if ($a['organization'] == $b['organization']) {
                return 0;
            }
            return $a['organization'] > $b['organization'];
        });

        return new ArrayDataProvider([
            'allModels' => $accountsArray,
            'key' => 'number',
        ]);
    }

    /**
     * Присваивание новому пользователю права на счета
     * @param $userId
     */
    public static function setNewUserAccounts($userId) {
        // Получение всех счетов пользователя согласно доступным терминалам

        $user = User::findOne($userId);

        if (!$user) {
            throw new RuntimeException('User ' . $userId . ' not found');
        }

        $accounts = self::getUserAccounts($user);

        // Запись доступности счетов пользователю
        foreach($accounts as $account) {
            if (!EdmPayerAccountUser::createOrUpdate($userId, $account->id, true)) {
                throw new RuntimeException();
            }
        }
    }

    /**
     * Удаление доступа пользователя к счетам по терминалу
     * @param $userId
     * @param $terminalId
     */
    public static function deleteUserAccountsAllowByTerminal($userId, $terminalId)
    {
        // Терминал, счета которого необходимо получить
        $terminal = Terminal::findOne($terminalId);

        if (!$terminal) {
            throw new RuntimeException;
        }

        // Пользователь, для которого необходимо удалить доступ к счетам
        $user = User::findOne($userId);

        if (!$user) {
            throw new RuntimeException;
        }

        // Получаем организации, которые доступны пользователю по терминалу
        $organizations = DictOrganization::find()
            ->select('id')
            ->where(['terminalId' => $terminalId])
            ->asArray()
            ->all();
        $organizations = ArrayHelper::getColumn($organizations, 'id');

        // Получаем счета, доступные пользователю по терминалу
        $accounts = EdmPayerAccount::find()
            ->with('edmDictOrganization', 'edmDictCurrencies')
            ->where(['organizationId' => $organizations])
            ->all();

        // Удаляем доступ пользователя к счетам
        foreach($accounts as $account) {
            EdmPayerAccountUser::deleteAccountFromUser($userId, $account->id);
        }
    }

    public static function addUserAccountsAllowByTerminal($userId, $terminalId)
    {
        // Терминал, счета которого необходимо получить
        $terminal = Terminal::findOne($terminalId);

        if (!$terminal) {
            throw new RuntimeException;
        }

        // Пользователь, для которого необходимо удалить доступ к счетам
        $user = User::findOne($userId);

        if (!$user) {
            throw new RuntimeException;
        }

        // Получаем организации, которые доступны пользователю по терминалу
        $organizations = DictOrganization::find()
            ->select('id')
            ->where(['terminalId' => $terminalId])
            ->asArray()
            ->all();
        $organizations = ArrayHelper::getColumn($organizations, 'id');

        // Получаем счета, доступные пользователю по терминалу
        $accounts = EdmPayerAccount::find()
            ->with('edmDictOrganization', 'edmDictCurrencies')
            ->where(['organizationId' => $organizations])
            ->all();

        // Добавляем доступ пользователя к счетам
        foreach($accounts as $account) {
            EdmPayerAccountUser::createOrUpdate($userId, $account->id, true);
        }
    }

    public static function getUserAddonsServiceAccess($user)
    {
        $labels = [
            'swiftfin' => Yii::t('app', 'SwiftFin'),
            'fileact' => Yii::t('app', 'FileAct'),
            'finzip' => Yii::t('app/menu', 'Free Format'),
            'edm' => Yii::t('edm', 'Banking'),
            'ISO20022' => Yii::t('app', 'ISO20022'),
            'VTB' => Yii::t('app', 'VTB'),
            'SBBOL' => Yii::t('app', 'SBBOL'),
            'raiffeisen' => Yii::t('app', 'Raiffeisen'),
        ];

        // Список аддонов-сервисов формируем только при просмотре настроек обычных пользователей
        if ($user->role == User::ROLE_USER) {
            foreach (array_keys(Yii::$app->addon->getRegisteredAddons()) as $serviceId) {
                if ($serviceId === 'VTB' || $serviceId === 'sbbol2') {
                    continue;
                }

                $model = Yii::$app->getModule($serviceId)->getUserExtModel($user->id);
                if (!$model) {
                    $model = Yii::$app->getModule($serviceId)->getUserExtModel();
                }
                $checkStatus = $model->isAllowedAccess();

                // Дополнительные опции сервиса для пользователя
                $permissions = $model->permissions ? $model->permissions : [];

                $services[] = [
                    'type' => $serviceId,
                    'name' => $labels[$serviceId],
                    'checked' => $checkStatus,
                    'deleteDocument' => array_search('documentDelete', $permissions) !== false,
                    'createDocument' => array_search('documentCreate', $permissions) !== false,
                    'signDocument' => array_search('documentSign', $permissions) !== false,
                    'settingsUrl' => $checkStatus && $model->hasSettings()
                        ? Url::toRoute([$serviceId . '/user-ext', 'id' => $user->id])
                        : null
                ];
            }
        }

        return new ArrayDataProvider([
            'allModels' => (isset($services) ? $services : []),
            'key' => 'type',
            'pagination' => false
        ]);
    }

    public static function getUserAdditionalServiceAccess($user)
    {
         // Настройки сервисов доступны только главному администратору
        // и только при просмотре профилей
        // пользователей и дополнительных администратров

        if (Yii::$app->user->identity->role == User::ROLE_ADMIN &&
            ($user->role == User::ROLE_USER || $user->role == User::ROLE_ADDITIONAL_ADMIN)) {

            foreach(CommonUserExt::getServices() as $serviceId) {
                // Поиск данных сервиса по текущему пользователю
                $model = CommonUserExt::findOne(['userId' => $user->id, 'type' => $serviceId]);

                $services[] = [
                    'type' => $serviceId,
                    'name' => CommonUserExt::getServiceLabel($serviceId),
                    'checked' => $model && $model->canAccess,// статус настройки пользователя,
                    'settingsUrl' => $model && $model->canAccess && $model->hasSettings()
                        ? Url::toRoute(['/common-user-ext', 'id' => $user->id, 'type' => $serviceId])
                        : null
                ];

            }
        }

        return new ArrayDataProvider([
            'allModels' => (isset($services) ? $services : []),
            'key' => 'type',
            'pagination' => false
        ]);
    }

    /**
     * Получение времени таймаута неактивности пользователя
     * @return int|string
     */
    public static function getUserLogoutTimeout()
    {
        $timeout = getenv('USER_LOGOUT_TIMEOUT');

        if (empty($timeout)) {
            $timeout = 3;
        }

        return $timeout;
    }

    public static function getDocumentsPermissionsInfo(BaseUserExt $userExt, BaseBlock $module): array
    {
        $permissionsCodes = DocumentPermission::all();
        $grantedPermissions = $userExt->permissions;
        $permissionsInfo = [];

        foreach ($permissionsCodes as $permissionCode) {
            if (in_array($permissionCode, $grantedPermissions)) {
                $permissionsInfo[] = $userExt->getPermissionLabel($permissionCode);
                continue;
            }

            $documentTypesGruopsIds = array_reduce(
                $grantedPermissions,
                function ($carry, $item) use ($permissionCode) {
                    if (preg_match("/^$permissionCode\:([a-z\d]+)$/i", $item, $matches)) {
                        $carry[] = $matches[1];
                    }
                    return $carry;
                },
                []
            );

            $documentTypesGroupsNames = array_map(
                function ($id) use ($module) {
                    $group = $module->getDocumentTypeGroupById($id);
                    return $group ? $group->name : $id;
                },
                $documentTypesGruopsIds
            );

            if (count($documentTypesGroupsNames) > 0) {
                $permissionsInfo[] = $userExt->getPermissionLabel($permissionCode)
                    . ': '
                    . implode(', ', $documentTypesGroupsNames);
            }
        }

        return $permissionsInfo;
    }
}
