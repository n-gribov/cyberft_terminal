<?php
use yii\helpers\Url;
use common\widgets\AdvancedTabs;

$this->title = Yii::t('app/menu', 'Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'ISO20022'), 'url' => Url::toRoute(['/ISO20022/documents'])];
$this->params['breadcrumbs'][] = $this->title;

$data = [
    'action' => 'tabMode',
    'defaultTab' => isset($defaultTab) ? $defaultTab : 'tabCommon',
    'tabs' => [
        'tabCommon' => [
            'label' => Yii::t('app/menu', 'Common settings'),
            'content' => '@addons/ISO20022/views/settings/_common',
        ],
        'tabSFTP' => [
            'label' => Yii::t('app/menu', 'SFTP settings'),
            'content' => '@addons/ISO20022/views/settings/_sftp',
        ],
        'tabCryptoPro' => [
            'label' => Yii::t('app/iso20022', 'CryptoPro settings'),
            'content' => '@addons/ISO20022/views/settings/_cryptoPro',
        ],
        'tabCodes' => [
            'label' => Yii::t('app/iso20022', 'Document type code dictionary'),
            'content' => isset($authCodeContent) ? $authCodeContent : '@addons/ISO20022/views/settings/_typeCodeDict',
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