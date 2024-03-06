<?php

namespace common\validators;

use Yii;
use yii\validators\Validator;

class BICDirValidator extends Validator
{
    private $_pattern="/^[A-Z0-9@]{11}$/";

    public function validateAttribute($model, $attribute)
    {
        if (preg_match($this->_pattern, $model->$attribute) !== 1) {
            $this->addError(
                    $model,
                    $attribute,
                    Yii::t('app/participant',
                            'Terminal ID must have only letters, digits, @ and must be 11 symbols strictly'));
        }
    }
}