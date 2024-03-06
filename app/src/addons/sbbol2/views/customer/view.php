<?php

use addons\Sbbol2\models\Sbbol2Customer;
use common\widgets\GridView;
use yii\data\ActiveDataProvider;
use yii\grid\SerialColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/** @var $this View */
/** @var $model Sbbol2Customer */
/** @var $customersDataProvider ActiveDataProvider */

$this->title = $model->fullName;
?>

<div id="buttons-block">
    <?= Html::a(
        Yii::t('app', 'Back'),
        Url::toRoute('index'),
        ['class' => 'btn btn-default']
    ) ?>

    <?= Html::a(
        Yii::t('app/sbbol2', 'Request update'),
        ['request-update', 'id' => $model->id],
        ['class' => 'btn btn-success']
    ) ?>
</div>

<h4><?= Yii::t('app/sbbol2', 'Organization data') ?></h4>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'shortName',
        'fullName',
        'inn',
        'kpp',
        'ogrn',
        'okato',
        'okpo',
        'orgForm',
        'addressArea',
        'addressBuilding',
        'addressCity',
        'addressCountryCode',
        'addressFlat',
        'addressHouse',
        'addressRegion',
        'addressSettlement',
        'addressSettlementType',
        'addressStreet',
        'addressZip',
        'terminalAddress',
        'customerAccessToken.statusLabel',
    ]
]); ?>

<h4><?= Yii::t('app/sbbol2', 'Accounts') ?></h4>

<?php
echo GridView::widget([
    'dataProvider' => $customersDataProvider,
    'columns' => [
        ['class' => SerialColumn::class],
        'number',
        'bic',
        'currencyCode'
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
