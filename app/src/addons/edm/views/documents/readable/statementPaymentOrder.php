<?php

use common\models\cyberxml\CyberXmlDocument;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\web\View;


/* @var $this View */
/* @var $model Document */

if ($model->getValidStoredFileId()) {
    $content = CyberXmlDocument::getTypeModel($model->getValidStoredFileId());
} else if ($model->status == $model::STATUS_CREATING) {
    echo 'Документ еще не создан';

    return;
} else {
    echo 'К сожалению, нет возможности отобразить документ данного типа';

    return;
}

$dataProvider = new ArrayDataProvider([
	'allModels' => $content->transactions,
	'pagination' => [
		'pageSize' => 20,
	],
]);

$statementPeriodStart = new DateTime($content->statementPeriodStart);
$statementPeriodEnd = new DateTime($content->statementPeriodEnd);
?>
<div style="padding-top:2em">

<b>Выписка №<?=$content->statementNumber?> по счету <?=$content->statementAccountNumber?><br/>
Наименование организации: <?=$content->companyName?><br/>
За <?=$statementPeriodStart->format('d.m.Y')?> &mdash; <?=$statementPeriodEnd->format('d.m.Y')?><br/>
<?php if ($content->currency) : ?>
Валюта: <?=$content->currency?><br/>
<?php endif ?>
Входящий остаток: <?=$content->openingBalance?><br/>
Сумма зарезервированных средств: <?=$content->reservedAmount?><br/><br/>
<?php if (!$dataProvider->totalCount): ?>
	Итого обороты: дебет <?=$content->debitTurnover?>, кредит: <?=$content->creditTurnover?><br/>
	Исходящий остаток: <?=$content->closingBalance?>
<?php endif ?>
    Дата последней операции: <?=date('Y-m-d', strtotime($content->prevLastOperationDate))?><br/><br/>
</b>
<?php if ($dataProvider->totalCount): ?>
<?php
    // Создать таблицу для вывода
    echo GridView::widget([
        'emptyText' => '',
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'attribute' => 'ValueDate',
                'format' => ['date', 'php:d.m.Y'],
                'label' => Yii::t('doc/st', 'Date'),
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'contentOptions' => [
                    'style' => 'text-align: right',
                    'nowrap' => 'nowrap'
                ],
            ],
            [
                'attribute' => 'DocDate',
                'format' => ['date', 'php:d.m.Y'],
                'label' => Yii::t('doc/st', 'DocDate'),
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'contentOptions' => [
                    'style' => 'text-align: right',
                    'nowrap' => 'nowrap'
                ],
            ],
            [
                'attribute' => 'Number',
                'enableSorting' => false,
                'label' => Yii::t('doc/st', 'Number'),
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'contentOptions' => [
                    'style' => 'text-align: right',
                ],
            ],
            [
                'enableSorting' => false,
                'format' => 'raw',
                'label' => Yii::t('edm', 'Payer'),
                'value' => function($model) {
                    return $model['PayerAccountNum']
                        . '<br/>' . $model['PayerName']
                        . '<br/>' . Yii::t('doc/st', 'INN') . ': ' . $model['PayerINN']
                        . '<br/>' . Yii::t('doc/st', 'KPP') . ': ' . $model['PayerKPP'];
                },
                'contentOptions' => [
                    'style' => 'width: 250px;'
                ],
            ],
            [
                'enableSorting' => false,
                'format' => 'raw',
                'label' => Yii::t('edm', 'Payer Bank'),
                'value' => function($model) {
                    return Yii::t('doc/st', 'BIK') . ': ' . $model['PayerBIK']
                        . '<br/>' . $model['PayerBankName']
                        . '<br/>' . $model['PayerBankAccountNum'];
                },
                'contentOptions' => [
                    'style' => 'width: 250px;'
                ],
            ],
            [
                'enableSorting' => false,
                'format' => 'raw',
                'label' => Yii::t('edm', 'Payee'),
                'value' => function($model) {
                    return $model['PayeeAccountNum']
                        . '<br/>' . $model['PayeeName']
                        . '<br/>' . Yii::t('doc/st', 'INN') . ': ' . $model['PayeeINN']
                        . '<br/>' . Yii::t('doc/st', 'KPP') . ': ' . $model['PayeeKPP'];
                },
                'contentOptions' => [
                    'style' => 'width: 250px;'
                ],
            ],
            [
                'enableSorting' => false,
                'format' => 'raw',
                'label' => Yii::t('edm', 'Payee Bank'),
                'value' => function($model) {
                    return Yii::t('doc/st', 'BIK') . ': ' . $model['PayeeBIK']
                        . '<br/>' . $model['PayeeBankName']
                        . '<br/>' . $model['PayeeBankAccountNum'];
                },
                'contentOptions' => [
                    'style' => 'width: 250px;'
                ],
            ],
            [
                'attribute' => 'Debit',
                'enableSorting' => false,
                'value' => function($row) {
                    return Yii::$app->formatter->asDecimal($row['Debit'], 2);
                },
                'label' => Yii::t('doc/st', 'Debit'),
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'contentOptions' => [
                    'style' => 'text-align: right',
                    'nowrap' => 'nowrap'
                ],
            ],
            [
                'attribute' => 'Credit',
                'enableSorting' => false,
                'value' => function($row) {
                    return Yii::$app->formatter->asDecimal($row['Credit'], 2);
                },
                'label' => Yii::t('doc/st', 'Credit'),
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'contentOptions' => [
                    'style' => 'text-align: right',
                    'nowrap' => 'nowrap'
                ],
            ],
            [
                'attribute' => 'Purpose',
                'enableSorting' => false,
                'label' => Yii::t('doc/st', 'Purpose'),
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'urlCreator' => function($action, $row, $key, $index ) use($model) {
                    return Url::toRoute(['statement/payment_order_' . $action, 'id' => $model->id]);
                },
            ]
        ],
    ]);
?>
<div align="right" style="clear:both;font-weight:bold">
	Итого обороты: дебет <?=$content->debitTurnover?>, кредит: <?=$content->creditTurnover?><br/>
	Исходящий остаток: <?=$content->closingBalance?>
</div>
<?php endif?>
</div>