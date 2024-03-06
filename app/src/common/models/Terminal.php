<?php

namespace common\models;

use common\components\TerminalId;
use common\models\TerminalRemoteId;
use common\models\User;
use common\models\UserTerminal;
use common\modules\autobot\models\Autobot;
use common\modules\certManager\models\Cert;
use common\validators\TerminalIdValidator;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Terminal model
 *
 * @property integer $id
 * @property string  $terminalId
 * @property string  $title
 * @property string $status
 * @property integer $isDefault
 * @property-read string $screenName
 */
class Terminal extends ActiveRecord
{
    const STATUS_INACTIVE = 'inactive';
    const STATUS_ACTIVE   = 'active';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'terminal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['terminalId', 'unique'],
            ['terminalId', TerminalIdValidator::className()],
            [['isDefault'], 'boolean'],
            [['isDefault'], function ($attribute, $params) {
                if (!empty($this->$attribute)) {
                    $data = Terminal::find()
                        ->where('isDefault = 1')
                        ->andWhere('id <> ' . (!empty($this->id) ? $this->id : 0))
                        ->all();
                    if (!empty($data)) {
                        $this->addError($attribute,
                            Yii::t('app/terminal',
                                'There can be only one default terminal'));
                    }
                }
            }],
            [['title', 'status'], 'string'],
            [['title'], 'trim'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', function ($attribute, $params) {
                    if (
                        (self::STATUS_INACTIVE === $this->$attribute) && ($this->isDefault)
                    ) {
                        $this->addError($attribute,
                            Yii::t('app/terminal',
                                'Default terminal cannot be deactivated'));
                    }
                }],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // Проверяем все новые и активные терминалы
        if ($insert) {

            // Проверяем все активные терминалы за исключением текущего созданного
            $allTerminals = static::find()
                ->where(['status' => static::STATUS_ACTIVE])
                ->andWhere(['not', ['id' => $this->id]])
                ->count();

            // Если в системе только 1 терминал,
            // то новый терминал добавлять всем пользователям не нужно
            if ($allTerminals == 1) {
                return true;
            }

            $users = User::find()->with('terminals')->all();

            // Всем пользователям, у которых выбраны все активные терминалы,
            // добавляем также новый терминал, если он активный

            foreach($users as $user) {

                // Получаем количество всех терминалов пользователя
                $userAllTerminals = count($user->terminals);

                // Если у пользователя выбраны все существующие активные терминалы,
                // то считаем, что у него есть доступ ко всем терминалам и добавляем ему новый
                if ($userAllTerminals == $allTerminals) {
                    $userTerminal = new UserTerminal();
                    $userTerminal->terminalId = $this->id;
                    $userTerminal->userId = $user->id;
                    $userTerminal->save();
                }
            }

            // Задаем настройку использования настроек подписания для терминала
            // Для новых терминалов по-умолчанию использовать общие настройки для всех модулей
            $terminalSettings = Yii::$app->settings->get('app', $this->terminalId);
            $terminalSettings->usePersonalAddonsSigningSettings = false;
            $terminalSettings->useAutosigning = false;
            $terminalSettings->qtySignings = 1;
            $terminalSettings->save();
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app/terminal', 'Status'),
            'terminalId' => Yii::t('app/terminal', 'Terminal ID'),
            'title' => Yii::t('app/terminal', 'Company Name'),
            'isDefault' => Yii::t('app/terminal', 'Default terminal'),
        ];
    }

    public function getStatusLabel()
    {
        return !empty($this->getStatusLabels()[$this->status])
                ? $this->getStatusLabels()[$this->status]
                : '';
    }

    public function getStatusLabels()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app/terminal', 'Active'),
            self::STATUS_INACTIVE => Yii::t('app/terminal', 'Inactive'),
        ];
    }

    public static function getDefaultTerminal()
    {
        return static::findOne(['status' => static::STATUS_ACTIVE, 'isDefault' => 1]);
    }

    public static function getIdByAddress($name)
    {
        $model = static::findOne(['status' => static::STATUS_ACTIVE, 'terminalId' => $name]);

        return empty($model) ? null : $model->id;
    }

    public static function getList($key, $val)
    {
        $list = static::findAll(['status' => static::STATUS_ACTIVE]);

        return ArrayHelper::map($list, $key, $val);
    }

    public function getScreenName()
    {
        return !empty($this->title) ? $this->title : $this->terminalId;
    }

    public static function getParticipantAddress($address)
    {
        $length = strlen($address);
        $where = null;
        if ($length == 12) {
            $where = ['terminalId' => $address];
        } else {
            $terminalId = TerminalId::extract($address);

            if ($length == 11) {
                $where = ['like', 'terminalId', $terminalId->bic . '_' . $terminalId->participantUnitCode, false];
            } else if ($length == 8) {
                $where = ['like', 'terminalId', $terminalId->bic . '%', false];
            }
        }

        if (!$where) {
            return null;
        }

        $where['status'] = Terminal::STATUS_ACTIVE;
        $resultSet = Terminal::find()->where($where)->orderBy(['terminalId' => SORT_ASC])->all();
        $count = count($resultSet);
        if ($count == 0) {
            return null;
        }

        // Если терминалов нашлось больше 1, то выбираем тот, который заканчивается на XXX
        if ($count > 1) {
            foreach($resultSet as $term) {
                if (substr($term->terminalId, -3) == 'XXX') {
                    return $term->terminalId;
                }
            }
        }
        // Если не нашли XXX, то выбираем первый из списка
        return $resultSet[0]->terminalId;
    }

    /**
     * Обработка удаления терминала
     * @return bool
     */
    public function beforeDelete()
    {
        if (parent::beforeDelete()) {

            //0. Основной терминал нельзя удалить

            if ($this->isDefault) {
                $this->addError('terminalDelete',
                    Yii::t(
                        'app/terminal', 'Cannot delete default terminal {terminalId}',
                        ['terminalId' => $this->terminalId]
                    )
                );

                // Нехер дальше проверять вообще
                return false;
            }

            //1. Проверка на привязанных к терминалу пользователей

            // Получаем всех пользователей, привязанных к данному терминалу
            $usersTerminal = UserTerminal::findAll(['terminalId' => $this->id]);

            if ($usersTerminal) {

                $emails = [];

                // Формируем список пользователей, которые привязаны к данному терминалу
                foreach($usersTerminal as $item) {

                    $userModel = $item->user;

                    if ($userModel->status != User::STATUS_DELETED) {
                        $emails[] = $userModel->email;
                    }
                }

                if ($emails) {
                    // Возвращаем ошибку удаления терминала
                    $this->addError('terminalDelete', Yii::t(
                        'app/terminal', 'Linked users exist - {users}',
                        ['users' => implode(', ', $emails)]
                    ));
                }
            }

            //2. Проверка на наличие привязанных к терминалу сертификатов

            // Получаем все сертификаты
            $certs = Cert::find()->all();

            // Получаем у каждого сертификата terminalId и сравниваем с удаляемым
            foreach($certs as $cert) {
                if ($cert->terminalId == $this->terminalId) {

                    $this->addError('terminalDelete',
                        Yii::t(
                            'app/terminal', 'Linked certificate exists - {cert}',
                            ['cert' => $cert->certId]
                    ));
                }
            }
            //3. Проверка на наличие привязанных к терминалу ключей контролера

            $autobot = Autobot::find()
                ->joinWith('controller.terminal')
                ->where(['terminal.terminalId' => $this->terminalId])
                ->all();

            // Формируем список ключей контролера, которые привязаны к терминалу
            if ($autobot) {

                $fingerprints = [];

                foreach($autobot as $key) {
                    $fingerprints[] = $key->fingerprint;
                }

                $this->addError('terminalDelete',
                    Yii::t(
                        'app/terminal', 'Linked controller keys exist - {keys}',
                        ['keys' => implode(', ', $fingerprints)]
                    ));
            }

            if ($this->hasErrors('terminalDelete')) {
                return false;
            }

            return true;
        } else {
            return false;
        }
    }

    public function getRemoteIds()
    {
        return $this->hasMany(TerminalRemoteId::className(), ['terminalId' => 'id']);
    }

    /**
     * Получение кода терминала в системе получателя
     * @param $receiver
     * @return mixed|null
     */
    public function getRemoteTerminalId($receiver)
    {
        $code = null;

        $query = $this->getRemoteIds()
            ->where(['terminalReceiver' => $receiver])
            ->orWhere(['terminalReceiver' => null])
            ->one();

        if ($query) {
            $code = $query->remoteId;
        }

        return $code;
    }
}