<?php

namespace common\validators;

use yii\validators\Validator;

/**
 * Terminal ID validator class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage validators
 */
class TerminalIdValidator extends Validator
{
    /**
     * @var string $_pattern Regular expression pattern
     */
    private $_pattern="/^[A-Z0-9@]{12}$/";

    /**
     * Validates a single attribute.
     * Child classes must implement this method to provide the actual validation logic.
     * @param \yii\base\Model $model the data model to be validated
     * @param string $attribute the name of the attribute to be validated.
     */
    public function validateAttribute($model, $attribute)
    {
        if (preg_match($this->_pattern, $model->$attribute) !== 1) {
            $this->addError($model, $attribute, \Yii::t('app/terminal', 'Terminal ID must have only letters, digits, @ and must be 12 symbols strictly'));
        }

    }
}