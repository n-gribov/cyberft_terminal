<?php
use yii\helpers\Url;
use common\widgets\AdvancedTabs;

$this->title = Yii::t('app/menu', 'Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'FileAct'), 'url' => Url::toRoute(['/fileact'])];
$this->params['breadcrumbs'][] = $this->title;

$data = [
    'query' => 'fileact/settings',
    'action' => 'tabMode',
    'defaultTab' => 'tabCryptoPro',
    'tabs' => [
        'tabCryptoPro' => [
            'label' => Yii::t('app/fileact', 'CryptoPro settings'),
            'content' => '@addons/fileact/views/settings/_cryptoPro',
        ],
    ],
];

?>

<?= AdvancedTabs::widget([
        'data' => $data,
        'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'.Yii::t('app/error', 'The requested page could not be found.').'</div>',
        'params' => [
            'settings' => $settings,
            'cryptoproKeys' => $cryptoproKeys,
            'cryptoproKeysSearch' => $cryptoproKeysSearch,
            'cryptoproCert' => $cryptoproCert,
            'cryptoproCertSearch' => $cryptoproCertSearch,
        ]
]) ?>

