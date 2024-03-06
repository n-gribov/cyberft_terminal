<?php
use common\widgets\GridView;

$filterInputOptions = [
	'maxLength' => 64,
];
// Создать таблицу для вывода
$myGridWidget = GridView::begin([
	'emptyText'    => Yii::t('other', 'No documents matched your query'),
	'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
	'dataProvider' => $dataProvider,
	'columns'      => [
		[
			'attribute'          => 'id',
			'filterInputOptions' => [
				'maxLength' => 10,
				'style'     => 'width: 60px'
			],
		],
		[
			'attribute'          => 'dateCreate',
			'format'             => 'datetime',
			'filterInputOptions' => $filterInputOptions,
			'filter'             => false
		],
		'size',
		'originalFilename',
	],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();