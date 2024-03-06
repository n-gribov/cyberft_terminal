<?php

use common\document\Document;
use common\modules\certManager\models\Cert;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/** @var View $this */

$urlParams = [];
$from = Yii::$app->request->get('from', false);
if ($from !== false) {
    $urlParams['from'] = $from;
}
?>

<style type="text/css">
    table.table td {
        background: #ffffff
    }
    table tr.iespike {
        border: none;
    }
    table tr.iespike td {
        border: none; padding: 0;
    }
</style>

<div class="form-group">
    <?= Html::checkbox('showSignature', true, ['id' => 'showSignature']); ?>
    <?= Yii::t('doc', 'Show details of digital signature'); ?>
</div>

<?php
// Если данные не пусты
if (!empty($dataProvider)) {
    // Создать таблицу для вывода
    $myGridWidget = GridView::begin([
        'emptyText'    => Yii::t('other', 'No documents matched your query'),
        'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
        'dataProvider' => $dataProvider,
        'rowOptions' => function ($model, $key, $index, $grid) use ($urlParams) {
            $options['ondblclick'] = "window.location='". Url::toRoute(['/edm/payment-register/payment-order-view', 'id'=>$model->id] + $urlParams) ."'";

            return $options;
        },
        'columns' => [
            [
                'attribute' => 'id',
                'textAlign' => 'right',
                'width' => 'narrow',
            ],
            [
                'attribute'  => 'number',
                'textAlign' => 'right',
                'width' => 'narrow',
            ],
            [
                'attribute' => 'date',
                'textAlign' => 'right',
                'nowrap' => true,
            ],
            'payerName',
            [
                'attribute' => 'beneficiaryName',
                'width' => 'narrow',
            ],
            'payerAccount',
            [
                'attribute' => 'sum',
                'value' => function ($model) {
                    return Yii::$app->formatter->asDecimal($model->sum, 2);
                },
                'textAlign' => 'right',
                'options' => [
                    'width' => '150px'
                ],
            ],
            'currency',
            [
                'attribute' => 'paymentPurpose',
                'options' => [
                    'width' => '200px'
                ]
            ],
            [
                'attribute' => 'businessStatus',
                'value' => function($item) {
                    return $item->getBusinessStatusTranslation();
                }
            ],
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons'  => [
                    'view' => function ($url, $model, $key) use ($urlParams){
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', [
                            '/edm/payment-register/payment-order-view', 'id' => $model->id,
                        ] + $urlParams);
                    }
                ],
            ],
        ],
    ]);

    $myGridWidget->formatter->nullDisplay = '';
    $myGridWidget->end();
}
