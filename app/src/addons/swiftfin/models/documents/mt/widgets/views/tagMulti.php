<?php
use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use addons\swiftfin\models\documents\mt\mtUniversal\Tag;
use yii\widgets\ActiveForm;

/* @var $model Tag */
/* @var $form ActiveForm */
?>

<div class="row tag-block tag-<?=strtolower($model->name);?>">
	<?php if ($model->dataType == 'choice') : ?>
		<div class="col-xs-11 col-xs-offset-1" style="padding-top:20px;">
	<?php else : ?>

        <?php

            if (isset($model->parent->owner)) {
                if (isset($model->parent->owner->bankPriority) && $model->parent->owner->bankPriority == 'RUR6') {
                    if ($model->name == '72') {
                        $model->status = Entity::STATUS_MANDATORY;
                    }
                }
            }

        ?>

		<div class="col-xs-1 swt-tag-name" title=" <?=$model->getMaskScheme()['mask']?>">
			<div class="<?=($model->status === Entity::STATUS_MANDATORY) ? 'mt-required' : null ?>">
				<?php
			if ($model->name) {
				echo $model->name;
			}
			?>
			</div>
		</div>
		<div class="col-xs-11 tag-data-<?=strtolower($model->name);?>">
	<?php endif; ?>
		<div class="row">
		<?php foreach ($model->attributes() as $v): ?>
			<?=$model->attributeToHtmlForm($v, $form, ['disableContainer' => false])?>
		<?php endforeach ?>
			</div>
		<?php if ($model->hasErrors('value')): ?>
			<div class="help-block" style="clear: both"><?=implode("</br>", $model->getErrors('value'))?></div>
		<?php endif; ?>
	</div>
</div>    
