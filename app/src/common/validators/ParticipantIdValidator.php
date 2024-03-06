<?php

namespace common\validators;

/**
 * Participant validator class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package addons
 * @subpackage validators
 */
class ParticipantIdValidator extends \yii\validators\Validator
{
    /**
     * @var string $_pattern Regular expression pattern
     */
    private $_pattern="/[a-zA-Z0-9@]{11}/";

    /**
     * Validates a single attribute.
     * Child classes must implement this method to provide the actual validation logic.
     * @param \yii\base\Model $model the data model to be validated
     * @param string $attribute the name of the attribute to be validated.
     */
    public function validateAttribute($model, $attribute){
        if(preg_match($this->_pattern, $model->$attribute) !== 1){
            $this->addError($model, $attribute, \Yii::t('app/terminal', 'Participant ID must have only letters, digits, @ and must be 11 symbols strictly'));
         }
    }
}