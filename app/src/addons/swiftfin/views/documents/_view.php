<?php

use common\widgets\AdvancedTabs;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$data = [
    'action' => 'tabMode',
    'defaultTab' => 'mtdoc',
    'tabs' => [
        'mtdoc' => [
			'label' => Yii::t('other', 'MT-document view'),
			'content' => '@addons/swiftfin/views/documents/_mtdoc',
		],
		'readable' => [
			'label' => Yii::t('other', 'Readable view'),
			'content' => '@addons/swiftfin/views/documents/_readable',
		],
	],
];
?>
<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle"
		  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <?=Yii::t('app', 'Actions')?> <span class="caret"></span>
  </button>
  <ul class="dropdown-menu pull-left">
	<li><?=Html::a(Yii::t('app', 'Print'),
		Url::toRoute(['/swiftfin/documents/print', 'id' => $model->id, 'action' => 'printable']),
		['target' => '_blank'])?>
	</li>
	<li><?=Html::a(Yii::t('doc', 'Save as template'),
		Url::toRoute(['/swiftfin/templates/create/', 'fromId' => $model->id]))?>
	</li>
  </ul>
</div>
<br/><br/>
<?php

echo AdvancedTabs::widget([
	'data' => $data,
	'params' => [
		'model' => $model,
	],
]);
?>

<?php if (isset($commandDataProvider) && $commandDataProvider->count) :?>
<div class="col-lg-6">
<?=GridView::widget(
    [
        'summary' => false,
        'dataProvider' => $commandDataProvider,
        'columns' => [
            [
                'label' => Yii::t('app', 'Action'),
                'attribute' => 'action'
            ],
            [
                'label' => Yii::t('app', 'User'),
                'format' => 'html',
                'value' => function($item) {
                    return Html::a($item['userName'], Url::toRoute(['/user/view', 'id' => $item['userId']]));
                }
            ],
            [
                'label' => Yii::t('doc', 'Date'),
                'attribute' => 'date'
            ]
        ],
    ]);
?>
</div>
<?php endif ?>