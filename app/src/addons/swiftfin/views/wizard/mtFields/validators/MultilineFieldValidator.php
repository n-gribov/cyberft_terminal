<?php
namespace addons\swiftfin\views\wizard\mtFields\validators;

use yii\validators\Validator;

class MultilineFieldValidator extends Validator
{
	public $maxLines;
	public $maxLineLength;

    public function init()
    {
        parent::init();
        $this->enableClientValidation = true;
    }

	/**
	 * Функция-валидатор. Проверяет число строк в многострочном поле ввода,
	 * а также предельно допустимую длину каждой из строк.
	 */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;
		$textLines = explode("\r\n", $value);
		if (count($textLines) > $this->maxLines) {
			$this->addError($model, $attribute,
				\Yii::t('doc/mt', '{attr}: Too much lines per field, only {max} allowed', [
					'attr' => $model->getAttributeLabel($attribute),
					'max' => $this->maxLines,
				])
			);
		}
		foreach($textLines as $line) {
			if (strlen($line) > $this->maxLineLength) {
				$this->addError($model, $attribute,
					\Yii::t('doc/mt', '{attr}: Line length can not exceed {chr} characters', [
						'attr' => $model->getAttributeLabel($attribute),
						'chr' => $this->maxLineLength,
					])
				);
			}
		}
    }

	public function clientValidateAttribute($model, $attribute, $view)
    {
		$message[] = json_encode(\Yii::t('doc/mt', '{attr}: Too much lines per field, only {max} allowed', [
			'attr' => $model->getAttributeLabel($attribute),
			'max' => $this->maxLines,
		]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		$message[] = json_encode(\Yii::t('doc/mt', '{attr}: Line length can not exceed {chr} characters', [
			'attr' => $model->getAttributeLabel($attribute),
			'chr' => $this->maxLineLength,
		]), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
		return <<<JS
var charactersPerLine = {$this->maxLineLength};
var lines = {$this->maxLines};
// Maximum possible printable characters
var max = charactersPerLine * lines;
var text = value; // Text from field
// Calculate length excluding newline characters
var length = text.length - ((text.match(/\\n/g)||[]).length);
// Get all lines in textarea as array
var allLines = text.split("\n");
if (allLines.length > lines ) {
	messages.push({$message[0]});
}
for (cnt = 0; cnt < allLines.length; cnt++) {
	if (allLines[cnt].length > charactersPerLine) {
		messages.push({$message[1]});
		break;
	}
}
JS;
    }
}

