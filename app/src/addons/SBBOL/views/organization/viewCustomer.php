<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \addons\SBBOL\models\SBBOLCustomer */

$this->title = $model->fullName;
?>

<div id="buttons-block">
    <?php
    echo Html::a(
        Yii::t('app', 'Back'),
        ['view', 'inn' => $model->inn],
        ['class' => 'btn btn-default']
    );

    echo Html::a(
        Yii::t('app/sbbol', 'Request update'),
        ['request-customer-update', 'id' => $model->id],
        ['class' => 'btn btn-success']
    );

    if ($model->isHoldingHead) {
        echo Html::a(
            Yii::t('app/sbbol', 'Import branch organizations'),
            ['import-branch-organizations', 'id' => $model->id],
            ['class' => 'btn btn-success']
        );
    }
    ?>
</div>

<h4><?= Yii::t('app/sbbol', 'Organization data') ?></h4>
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
            'value' => $model->isHoldingHead ? Yii::t('app/sbbol', 'yes') : Yii::t('app/sbbol', 'no'),
        ],
        [
            'attribute' => 'holdingHeadCustomer',
            'label' => Yii::t('app/sbbol', 'Holding head organization'),
            'value' => $model->holdingHeadCustomer
                ? Html::a($model->holdingHeadCustomer->shortName, ['view', 'id' => $model->holdingHeadCustomer->id])
                : null,
            'format' => 'raw',
        ],
        'login',
        'senderName',
        'certAuthId',
        'lastCertNumber',
        'bankBranchSystemName',
    ],
]) ?>

<h4><?= Yii::t('app/sbbol', 'Accounts') ?></h4>
<?php
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->accounts,
        'modelClass' => \addons\SBBOL\models\SBBOLCustomerAccount::class,
        'pagination' => false,
    ]),
    'showOnEmpty' => false,
    'layout' => '{items}',
    'columns' => [
        'number',
        'bankBik',
        'currencyCode',
        'id',
    ],
]) ?>

<h4><?= Yii::t('app/sbbol', 'Keys owners') ?></h4>
<?php
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->keyOwners,
        'modelClass' => \addons\SBBOL\models\SBBOLCustomerKeyOwner::class,
        'pagination' => false,
    ]),
    'showOnEmpty' => false,
    'layout' => '{items}',
    'columns' => [
        'fullName',
        'position',
        'id',
        'signDeviceId',
    ],
]);

$this->registerCss(
    '#buttons-block {
        margin-bottom: 1em;
    }
    #buttons-block .btn {
        margin-right: .5em;
    }'
);
