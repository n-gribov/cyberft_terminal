<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \addons\VTB\models\VTBCustomer */

$this->title = $model->fullName;
?>

<div id="buttons-block">
    <?= Html::a(
        Yii::t('app', 'Back'),
        ['index'],
        ['class' => 'btn btn-default']
    ) ?>
</div>

<h4><?= Yii::t('app/vtb', 'Customer data') ?></h4>
<?php
// Создать детализированное представление
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'customerId',
        'clientId',
        'type',
        'kpp',
        'inn',
        [
            'attribute' => 'propertyType.name',
            'label' => Yii::t('app/vtb', 'Property type'),
        ],
        [
            'attribute' => 'name',
            'label' => Yii::t('app/vtb', 'Name in financial documents'),
        ],
        'addressState',
        'addressDistrict',
        'addressSettlement',
        'addressStreet',
        'addressBuilding',
        'addressBuildingBlock',
        'addressApartment',
        'countryCode',
        'addressZipCode',
        'internationalName',
        'internationalAddressState',
        'internationalAddressSettlement',
        'internationalStreetAddress',
        'internationalZipCode',
        'okato',
        'okpo',
    ],
]) ?>

<h4><?= Yii::t('app/vtb', 'Accounts') ?></h4>
<?php
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => new ArrayDataProvider([
        'allModels' => $model->accounts,
        'modelClass' => \addons\VTB\models\VTBCustomerAccount::class,
        'pagination' => false,
    ]),
    'showOnEmpty' => false,
    'layout' => '{items}',
    'columns' => [
        'number',
        'bankBik',
        'bankBranchName',
        'bankBranchId',
        [
            'attribute' => 'bankBranch.fullName',
            'label' => Yii::t('app/vtb', 'Bank branch name'),
        ]
    ],
]);

$this->registerCss(
    '#buttons-block {
        margin-bottom: 1em;
    }
    #buttons-block .btn {
        margin-right: .5em;
    }
    .detail-view td {
        width: 50%;
    }'
);
