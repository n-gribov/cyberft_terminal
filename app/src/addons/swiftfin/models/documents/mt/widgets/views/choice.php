<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use addons\swiftfin\models\documents\mt\mtUniversal\Choice;
use addons\swiftfin\models\documents\mt\mtUniversal\Entity;

\addons\swiftfin\models\documents\mt\widgets\assets\ChoiceAsset::register($this);
/* @var $form ActiveForm */
/* @var $model Choice */
?>

<?=$form->beginField($model, 'choice')?>

<div class="row tag-block tag-<?=strtolower($model->name);?>">
	<div class="col-xs-1 swt-tag-name">
		<div class="<?=($model->status === Entity::STATUS_MANDATORY) ? 'mt-required' : null ?>"><?=$model->name;?></div>
	</div>
	<div class="col-xs-11">
		<?php
			$choiceBlocks = null;
			$choiceSelect = [];

			foreach ($model->getModel() as $choice => $block) {
				$choiceBlocks .= $form->beginField($block, 'choice', [
					'options' => [
						'data-bind' => $block->identifier,
						'id' => $block->identifier,
						'style' => 'display:none;'
					]
				]);

				$choiceSelect[] = ['data-bind' => $block->identifier];
				$choiceBlocks .= $block->toHtmlForm($form);
				$choiceBlocks .= $form->endField();
			}

		    /*echo Html::activeRadioList($model, 'choice',
			$model->getChoiceLabels(), [
				'class'       => 'form-control',
				'itemOptions' => ['data-bind' => $model->identifier, 'class' => 'mt-choice-switcher']
			]);*/

			echo Html::activeDropDownList($model, 'choice',
				$model->getChoiceLabels(), [
					'class' => 'form-control',
					'data-master' => $model->identifier,
					'prompt' => $model->getAttributeLabel('value'),
					'title' => $model->getAttributeLabel('value'),
					'options' => array_combine(array_keys($model->getChoiceLabels()), $choiceSelect)
			]);


		?>
	</div>
	<div class="col-xs-12 tag-data-<?=strtolower($model->name);?>" data-container="<?=$model->identifier;?>">
		<?=$choiceBlocks;?>
	</div>
</div>

<?=$form->endField()?>
