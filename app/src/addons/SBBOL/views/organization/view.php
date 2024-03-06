<?php

use addons\SBBOL\models\SBBOLCustomer;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var $this \yii\web\View */
/** @var $model \addons\SBBOL\models\SBBOLOrganization */
/** @var $customersDataProvider \yii\data\ActiveDataProvider */

$this->title = $model->fullName;

echo Html::a(
    Yii::t('app', 'Back'),
    Url::toRoute('index'),
    ['class' => 'btn btn-default']
);
?>

<h4><?= Yii::t('app/sbbol', 'Organization data') ?></h4>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'fullName',
        'inn',
        'terminalAddress',
    ]
]); ?>

<h4><?= Yii::t('app/sbbol', 'Customers') ?></h4>

<?php
echo GridView::widget([
    'dataProvider' => $customersDataProvider,
    'rowOptions' => function (SBBOLCustomer $model, $key, $index, $grid) {
        return ['ondblclick' => "window.location='" . Url::toRoute(['view-customer', 'id' => $model->id]) . "'"];
    },
    'columns' => [
        ['class' => \yii\grid\SerialColumn::class],
        'id',
        'fullName',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{view}',
            'contentOptions' => ['class' => 'text-right'],
            'urlCreator' => function (string $action, $model, $key, $index, $column) {
                if ($action === 'view') {
                    return Url::toRoute(['view-customer', 'id' => $model->id]);
                }
                return null;
            }
        ],
    ],
]);
