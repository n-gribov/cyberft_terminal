<?php
use addons\swiftfin\models\documents\mt\mtUniversal\Collection;
use yii\web\View;

/* @var $model Collection */
/* @var $this View */
/* @var $model Collection */
\addons\swiftfin\models\documents\mt\widgets\assets\CollectionAsset::register($this);
$identifier = $model->identifier;
?>

<div class="row tag-<?=strtolower($model->name);?>">
	<div class="col-xs-12 tag-data-<?=strtolower($model->name);?>">
		<?=\yii\helpers\Html::beginTag('div', [
			'id' => '_collection_wrapper'.$identifier,
			'class' => 'form-group mt-collection',
			'data-formname' => $model->value[0]->formName(),
			'data-identifier' => $model->value[0]->identifier,
			//'style' => 'border: 2px solid #000'
		]) ?>

		<?php
		$c = count($model->value);
		for ($i = 0; $i < $c; $i++) {
			print '<div class="mt_collection_item">';
			print $model->value[$i]->toHtmlForm($form);
			print '</div>';
		}
		?>
		<div class="options text-right"><font size="+1">
				<a href="#" class="mt_collection_handler" data-action="plus" data-bind="<?=$identifier?>"><i class="fa fa-plus"></i></a>
				<a href="#" class="mt_collection_handler" data-action="minus" data-bind="<?=$identifier?>"><i class="fa fa-minus"></i></a>
			</font>
		</div>
		<?=yii\helpers\Html::endTag('div')?>
	</div>
</div>