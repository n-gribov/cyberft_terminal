<?php

use common\models\User;
use common\widgets\AdvancedTabs;
use common\helpers\UserHelper;

/* @var $this yii\web\View */
/* @var $model common\models\User */

$this->title					 = $model->name;
$this->params['breadcrumbs'][]	 = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
$this->params['breadcrumbs'][]	 = $this->title;

// Вывести блок управления пользователем
echo $this->render('_userManage', ['model' => $model]);

$data = [
    'action' => 'tabMode',
    'defaultTab' => 'common',
    'tabs' => [
        'common' => [
            'label' => Yii::t('app/user', 'User data'),
            'content' => '@backend/views/user/pages/_info',
        ]
    ]
];

// Получить модель пользователя из активной сессии
if (User::ROLE_ADMIN === Yii::$app->user->identity->role ||
    // Дополнительный администратор не может менять собственные терминалы
    (User::ROLE_ADDITIONAL_ADMIN === Yii::$app->user->identity->role
        && UserHelper::userProfileAccess($model, true)
        && Yii::$app->user->id != $model->id)
) {
    $data['tabs']['terminals'] = [
        'label' => Yii::t('app/user', 'Terminals'),
        'content' => '@backend/views/user/pages/_terminals',
    ];
}

// Дополнительные опции может исправлять только главный администратор
// или дополнительный для собственных пользователей
if (User::ROLE_ADMIN === Yii::$app->user->identity->role ||
    (User::ROLE_ADDITIONAL_ADMIN === Yii::$app->user->identity->role && UserHelper::userProfileAccess($model, true))) {

    $data['tabs']['accounts'] = [
        'label' => Yii::t('app/user', 'Accounts'),
        'content' => '@backend/views/user/pages/_accounts',
    ];

    $data['tabs']['services'] = [
        'label' => Yii::t('app/user', 'Services'),
        'content' => '@backend/views/user/pages/_services',
    ];
}

if (User::ROLE_ADMIN === Yii::$app->user->identity->role ||
    (User::ROLE_ADDITIONAL_ADMIN === Yii::$app->user->identity->role && UserHelper::userProfileAccess($model, true)) ||
    Yii::$app->user->identity->isSecurityOfficer() &&
    Yii::$app->controller->id == 'user') {
    $data['tabs']['certs'] = [
        'label' => Yii::t('app/user', 'Certificates'),
        'content' => '@common/modules/certManager/views/cert/_openSSLKeys',
    ];
}

echo AdvancedTabs::widget([
    'data' => $data,
    'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'.Yii::t('app/error', 'The requested page could not be found.').'</div>',
    'params' => [
        'model' => $model,
        'accounts' => $accounts,
        'additionalServiceAccess' => $additionalServiceAccess,
        'servicesSettingsForm' => $servicesSettingsForm,
        'certDataProvider' => $certDataProvider,
        'certSearchModel' => $certSearchModel,
        'uploadCertForm' => $uploadCertForm,
    ]
]);

$this->registerCss(<<<CSS
    .nav-tabs {
        margin-bottom: 10px;
    }

    .tab-buttons {
        padding-top: 0;
    }

    .info-buttons {
        margin-bottom: 10px;
    }
CSS);
