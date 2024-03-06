<?php

use yii\data\ArrayDataProvider;
use common\widgets\GridView;
use yii\web\View;
use common\models\cyberxml\CyberXmlDocument;


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
<div style="width:80%;padding-top:2em">

<b>Выписка №<?=$content->statementNumber?> по счету <?=$content->statementAccountNumber?><br/>
Наименование организации: <?=$content->companyName?><br/>
За <?=$statementPeriodStart->format('d.m.Y')?> &mdash; <?=$statementPeriodEnd->format('d.m.Y')?><br/>
Входящий остаток: <?=$content->openingBalance?><br/><br/>
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
            ],
			[
                'attribute' => 'DocDate',
                'format' => ['date', 'php:d.m.Y'],
                'label' => Yii::t('doc/st', 'DocDate'),
            ],
			[
                'attribute' => 'Number',
                'enableSorting' => false,
                'label' => Yii::t('doc/st', 'Number'),
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
			[
                'attribute' => 'Debit',
                'enableSorting' => false,
                'format'        => 'decimal',
                'label' => Yii::t('doc/st', 'Debit'),
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
			[
                'attribute' => 'Credit',
                'enableSorting' => false,
                'format'        => 'decimal',
                'label' => Yii::t('doc/st', 'Credit'),
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
			[
                'enableSorting' => false,
                'format' => 'raw',
                'label' => Yii::t('doc/st', 'Corr. Bank'),
				'value' => function($model) {
					return Yii::t('doc/st', 'BIK') . ': ' . $model['CorrBIK']
							. '<br/>' . $model['CorrBankName']
							. '<br/>' . $model['CorrBankAccountNum'];
				},
			],
			[
                'enableSorting' => false,
                'format' => 'raw',
                'label' => Yii::t('doc/st', 'Correspondent'),
				'value' => function($model) {
					return $model['CorrAccountNum']
							. '<br/>' . $model['CorrName']
							. '<br/>' . Yii::t('doc/st', 'INN') . ': ' . $model['CorrINN']
							. '<br/>' . Yii::t('doc/st', 'KPP') . ': ' . $model['CorrKPP'];
				},
			],
			[
                'attribute' => 'Purpose',
                'enableSorting' => false,
                'label' => Yii::t('doc/st', 'Purpose'),
            ],
		],
	]);
?>
<div align="right" style="clear:both;font-weight:bold">
	Итого обороты: дебет <?=$content->debitTurnover?>, кредит: <?=$content->creditTurnover?><br/>
	Исходящий остаток: <?=$content->closingBalance?>
</div>
<?php endif?>
</div>