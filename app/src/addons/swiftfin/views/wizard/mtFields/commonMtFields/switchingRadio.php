<?php

use kartik\widgets\ActiveForm;
use yii\helpers\Html;
use yii\widgets\MaskedInput;

/* @var $form ActiveForm */
/* @var $model MtXXXDocument */
/** @var $radioList array
 *    Список радиобаттонов
 *  ['option_name' => 'option_text', ...]
 */
/** @var $name string
Имя переменной, которой соответствует радиобаттон
 */
/** @var $fieldGroups array
 * Вектор описаний для каждого из полей группы, соответствующей радиобаттону
 * из списка $radioList['option_name']
 * 'option_name' => [
 *        Тип поля
 *        'type' => ['text'|'textarea'|'maskedinput'],
 *        Суффикс для формирования имени переменной, соответствующей полю
 *        Имя составляется из имени $name и суффикса
 *        'suffix' => '',
 *        Маска для поля (только для type='maskedinput')
 *        'textInputMask' => '...'
 * ]
 */

// Суффикс для имени переменной, содержащей значение радиобаттона
$nameSuffix = isset($nameSuffix) ? $nameSuffix : 'Variation';
?>

<?=$form->beginField($model, $name)?>
<?=Html::activeLabel($model, $name, [
	'class' => 'col-md-4 control-label',
	'label' => ((isset($mandatory) && $mandatory) ? '* ' : null).$model->getAttributeLabel($name)
])?>
<div class="col-md-8">
	<?=Html::activeRadioList($model,
		$name.$nameSuffix,
		$radioList,
		[
			'class'       => 'form-control',
			'itemOptions' => ['data-bind' => $name.$nameSuffix, 'class' => 'options-switcher']
		])?>
</div>
<?=$form->endField()?>
<?php
if (!isset($fieldGroups) || !is_array($fieldGroups)) {
	return;
} ?>
<?php // Цикл по всем возможным опциям, представленным группами полей
foreach ($radioList as $optionName => $optionValue) : ?>
	<?php
	if (isset($fieldGroups[$optionName]) && is_array($fieldGroups[$optionName])) : ?>
		<?=$form->beginField($model, $name.$optionName, [
			'options' => [
				'data-bind' => $name.$nameSuffix.$optionName,
				'class'     => 'form-group'
			]
		]);?>
		<?=Html::activeLabel($model, $name.$optionName, ['class' => 'col-md-4 control-label']);?>
		<div class="col-md-8">
			<div class="input-group">
				<span class="input-group-addon"><?=$model->getAttributeTag($name.$optionName)?></span>
				<?php // Цикл по всем полям, составляющим группу полей, соответствующую опции
				foreach ($fieldGroups[$optionName] as $index => $myCurrentField) : ?>
					<?php // Выбор типа поля, которое требуется сгененрировать
					switch ($myCurrentField['type']) {
						case 'text': // Текстовое поле
							echo Html::activeTextInput($model,
								$name.$myCurrentField['suffix'].$optionName, [
									'class' => 'form-control'
								]);
							break;
						case 'textarea': // Многострочный текст
							echo Html::activeTextarea($model,
								$name.$myCurrentField['suffix'].$optionName, [
									'class' => 'form-control',
									'rows'  => $myCurrentField['rows']
								]);
							break;
						case 'maskedinput': // Поле с маской
							echo MaskedInput::widget([
								'model'     => $model,
								'attribute' => $name.$myCurrentField['suffix'].$optionName,
								'mask'      => $myCurrentField['textInputMask']
							]);
							break;
					}?>
				<?php endforeach ?>
			</div>
		</div>
		<?=$form->endField()?>
	<?php endif ?>
<?php endforeach ?>
