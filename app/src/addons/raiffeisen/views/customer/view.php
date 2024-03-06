<?php

use addons\raiffeisen\models\RaiffeisenCustomerAccount;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var $this yii\web\View */
/** @var $model \addons\raiffeisen\models\RaiffeisenCustomer */

$this->title = $model->fullName;
?>

<div class="buttons-block">
    <?= Html::a(
        Yii::t('app', 'Back'),
        ['index'],
        ['class' => 'btn btn-default']
    ); ?>
</div>

<?php
// Создать детализированное представление
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'shortName',
        'fullName',
        'internationalName',
        'propertyType',
        'inn',
        'kpp',
        'ogrn',
        'dateOgrn',
        'countryCode',
        'addressState',
        'addressDistrict',
        'addressSettlement',
        'addressStreet',
        'addressBuilding',
        'addressBuildingBlock',
        'addressApartment',
        'id',
        [
            'attribute' => 'isHoldingHead',
            'value' => $model->isHoldingHead ? Yii::t('app/raiffeisen', 'yes') : Yii::t('app/raiffeisen', 'no'),
        ],
        [
            'attribute' => 'holdingHeadCustomer',
            'label' => Yii::t('app/raiffeisen', 'Holding head organization'),
            'value' => $model->holdingHeadCustomer
                ? Html::a($model->holdingHeadCustomer->shortName, ['view', 'id' => $model->holdingHeadCustomer->id])
                : null,
            'format' => 'raw',
        ],
        'login',
        'certificate:ntext',
        'signatureType',
    ],
]) ?>

<h4><?= Yii::t('app/raiffeisen', 'Accounts') ?></h4>
<div class="buttons-block">
    <?= Html::a(
        Yii::t('app/raiffeisen', 'Create account'),
        ['/raiffeisen/customer-account/create', 'customerId' => $model->id],
        ['class' => 'btn btn-success']
    ); ?>
</div>
<?php
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->accounts,
        'modelClass' => RaiffeisenCustomerAccount::class,
        'pagination' => false,
    ]),
    'showOnEmpty' => false,
    'layout' => '{items}',
    'columns' => [
        'number',
        'bankBik',
        'bankName',
        'currencyCode',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{update} {delete}',
            'urlCreator' => function (string $action, RaiffeisenCustomerAccount $model) {
                switch ($action) {
                    case 'update':
                        return Url::to(['/raiffeisen/customer-account/update', 'id' => $model->id]);
                    case 'delete':
                        return Url::to(['/raiffeisen/customer-account/delete', 'id' => $model->id]);
                }
            },
            'contentOptions' => ['class' => 'text-right'],
        ],
    ],
]);

$this->registerCss(
    '.buttons-block {
        margin-bottom: 1em;
    }
    .buttons-block .btn {
        margin-right: .5em;
    }'
);
