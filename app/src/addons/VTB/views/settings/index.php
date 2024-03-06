<?php
use yii\helpers\Url;
use common\widgets\AdvancedTabs;

/** @var \addons\VTB\settings\VTBSettings $settings */

$this->title = Yii::t('app/menu', 'Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'VTB'), 'url' => Url::toRoute(['/VTB/documents'])];
$this->params['breadcrumbs'][] = $this->title;

$data = [
    'action' => 'tabMode',
    'defaultTab' => isset($defaultTab) ? $defaultTab : 'tabCommon',
    'tabs' => [
        'tabCommon' => [
            'label' => Yii::t('app/menu', 'Common settings'),
            'content' => '@addons/VTB/views/settings/_common',
        ],
        'tabCryptoPro' => [
            'label' => Yii::t('app/iso20022', 'CryptoPro settings'),
            'content' => '@addons/VTB/views/settings/_cryptoPro',
        ],
    ],
];

?>

<?= AdvancedTabs::widget([
    'data' => $data,
    'notFoundTabContent' => '<div class="alert alert-danger" style="margin-top:20px">'.Yii::t('app/error', 'The requested page could not be found.').'</div>',
    'params' => [
        'settings'            => $settings,
        'cryptoproKeys'       => $cryptoproKeys,
        'cryptoproKeysSearch' => $cryptoproKeysSearch,
        'cryptoproCert'       => $cryptoproCert,
        'cryptoproCertSearch' => $cryptoproCertSearch,
    ]
]) ?>
