<?php

use common\widgets\AdvancedTabs;
use common\models\CryptoproKeySearch;

$userName = is_null(Yii::$app->user->identity) ? '' : Yii::$app->user->identity->screenName;
$this->title = Yii::t('app', 'User keys') . ' ' . $userName;
$this->params['breadcrumbs'][] = $this->title;
$session = Yii::$app->session;

// Ключи КриптоПро
$keysModel = new CryptoproKeySearch();

$data = [
    'action' => 'tabMode',
    'defaultTab' => isset($defaultTab) ? $defaultTab : 'tabSSLKeys',
    'tabs' => [
        'tabSSLKeys' => [
            'label' => Yii::t('app/menu', 'Keys for signing and authorization'),
            'content' => '@common/modules/certManager/views/cert/_openSSLKeys',
        ],
        'tabCryptoProKeys' => [
            'label' => Yii::t('app/menu', 'Keys for automatic signing'),
            'content' => '@common/modules/certManager/views/cert/_cryptoProKeys',
        ],
    ],
];

$user = Yii::$app->user;

echo AdvancedTabs::widget([
    'data' => $data,
    'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'.Yii::t('app/error', 'The requested page could not be found.').'</div>',
    'params' => [
        'model' => $model,
        'userName' => $userName,
        'cryptoproKeys' => $keysModel->searchByUser($user, Yii::$app->request->get()),
        'cryptoproKeysSearch' => $keysModel,
        'uploadCertForm' => $uploadCertForm,
        'certDataProvider' => $certDataProvider,
        'certSearchModel' => $certSearchModel,
    ]
]);

?>

