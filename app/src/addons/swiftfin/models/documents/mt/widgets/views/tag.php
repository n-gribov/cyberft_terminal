<?php
use addons\swiftfin\models\documents\mt\mtUniversal\Entity;
use addons\swiftfin\models\documents\mt\mtUniversal\Tag;
use addons\swiftfin\models\documents\mt\mtUniversal\MtMask;
use yii\helpers\Html;
//use kartik\widgets\ActiveForm;
use yii\widgets\ActiveForm;

\addons\swiftfin\models\documents\mt\widgets\assets\TagAsset::register($this);
/* @var $this \yii\web\View */
/* @var $model Tag */
/* @var $form ActiveForm */

$field = 'textInput';
$maskScheme = $model->getMaskScheme();
$fieldOptions = [
	'readonly'  => (bool)$model->constant,
	'disabled'  => (bool)$model->constant,
	'maxlength' => true,
	'placeholder' => $model->getAttributeLabel('value'),
	'title' => $model->label . (isset($model->maskScheme['mask']) ? ' (' . $model->maskScheme['mask'] . ')' : null),
];

$formOptions = [
	'showLabels' => false,
	//'template' => '{input}{error}',
];

if ($maskScheme) {
	if (isset($maskScheme['scheme']) && count($maskScheme['scheme']) === 1) {
		$maskScheme = $maskScheme['scheme'][0];
	}

    $requireRowsValid = false;

	if (isset($maskScheme['rows']) && $maskScheme['rows'] > 1) {
		$field = 'textarea';
        $requireRowsValid = true;
		$fieldOptions['rows'] = ($maskScheme['rows'] > 10 ? 10 : $maskScheme['rows']);
	} else if (isset($maskScheme['length']) && $maskScheme['length'] > 300) {
		$field = 'textarea';
		$fieldOptions['rows'] = 6;
	} else if (isset($maskScheme['length'])) {
		$fieldOptions['maxlength'] = $maskScheme['length'];
	}

    if ($field == 'textarea' && $requireRowsValid) {
        if ($model->name == '72') {
            $fieldOptions['data-textarea-type'] = "72";
        }

        $fieldOptions['class'] = "mtmultiline";
        $fieldOptions['data-limit'] = $maskScheme['rows'] . ', ' . $maskScheme['length'];
    }

	if (isset($maskScheme['mask'])) {
		$fieldOptions['data-filter'] = MtMask::maskFilterRegexp($maskScheme['mask']);
	}
}
?>

<div class="row tag-block tag-<?=strtolower($model->name);?>">
	<div class="col-xs-1 swt-tag-name">

        <?php
            if (isset($model->parent->owner)) {
                if (isset($model->parent->owner->bankPriority) && $model->parent->owner->bankPriority == 'RUR6') {
                    if ($model->name == '72') {
                        $model->status = Entity::STATUS_MANDATORY;
                    }
                }
            }
        ?>

		<div
			title="<?=$model->label . (isset($model->maskScheme['mask']) ? ' (' . $model->maskScheme['mask'] . ')' : null); ?>"
			class="<?=($model->status === Entity::STATUS_MANDATORY) ? 'mt-required' : null ?>">
			<?=$model->name;?>
		</div>
	</div>
	<div class="col-xs-11 tag-data-<?=strtolower($model->name);?>">
		<?php
			if ($model->hidden) {
				echo Html::activeHiddenInput($model, 'value', ['data-constant' => $model->constant ? 'true' : '']);
			} else {
				echo $form->field($model, 'value', $formOptions)->{$field}($fieldOptions)->label(false);
			}
		?>
	</div>
</div>