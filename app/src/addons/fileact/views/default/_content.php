<?php
use common\widgets\AdvancedTabs;
if (!empty($model)) {
	$data = [
	    'action' => 'tabMode',
	    'defaultTab' => 'tabDownload',
		'tabs' => [
			'tabDownload' => [
				    'label' => Yii::t('app', 'Download'),
					'content' => '@addons/fileact/views/default/_download',
				],
			],
		];

	echo AdvancedTabs::widget([
		'data' => $data,
		'params' => [
			'model' => $model
		]
	]);
} else {
?>
	<div class="alert alert-danger"><?=Yii::t('app/error', 'Document not found')?></div>
<?php
}
?>