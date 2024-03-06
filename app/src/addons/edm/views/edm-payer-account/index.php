<?php

use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use addons\edm\models\DictCurrency;
use yii\helpers\ArrayHelper;
use common\widgets\InlineHelp\InlineHelp;
use addons\edm\helpers\EdmHelper;

$this->title = Yii::t('app/menu', 'Payers Accounts');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;

$isAdmin = Yii::$app->user->can('admin') || Yii::$app->user->can('additionalAdmin');

if ($isAdmin) {
    $this->beginBlock('pageActions');
    echo Html::a(Yii::t('edm', 'Add payer account'),
        ['create'],
        ['class' => 'btn btn-success'],
        'ic-plus');
    $this->endBlock('pageActions');
}
?>

<div class="pull-right">
    <?=InlineHelp::widget(['widgetId' => 'edm-payer-account-journal', 'setClassList' => ['edm-journal-wiki-widget']]);?>
</div>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        [
            'class' => 'yii\grid\SerialColumn',
            'headerOptions' => [
                'style' => "width: 20px;",
            ],
        ],
        [
            'attribute' => 'name',
            'headerOptions' => [
                'style' => "width: 350px;",
            ],
        ],
        [
            'attribute' => 'organizationName',
            'label' => Yii::t('edm', 'Organization name'),
            'value' => function($item) {
                return $item->getPayerName();
            },
            'headerOptions' => [
                'style' => "width: 350px;",
            ],
        ],
        [
            'attribute' => 'number',
            'headerOptions' => [
                'style' => "width: 350px;",
            ],
        ],
        [
            'attribute' => 'currencyName',
            'filter' => ArrayHelper::map(DictCurrency::getValues(), 'id', 'name'),
            'value' => 'edmDictCurrencies.name',
            'label' => Yii::t('edm', 'Currency'),
            'headerOptions' => [
                'style' => "width: 100px;",
            ],
        ],
        [
            'attribute' => 'bankName',
            'label' => Yii::t('edm', 'Account bank'),
            'value' => 'bank.name',
            'headerOptions' => [
                'style' => "width: 350px;",
            ],
        ],
        [
            'attribute' => 'requireSignQty',
            'label' => Yii::t('app', 'Required signatures'),
            'value' => function($model) {
                return EdmHelper::getPayerAccountSignaturesNumber($model);
            }
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'visibleButtons' => [
                'view' => function ($model, $key, $index) {
                    return true;
                },
                'update' => function ($model, $key, $index) use ($isAdmin) {
                    return $isAdmin;
                },
                'delete' => function ($model, $key, $index) use ($isAdmin) {
                    return $isAdmin;
                }
            ],
            'contentOptions' => [
                'style' => 'min-width: 125px;'
            ]
        ]
    ],
]);?>
