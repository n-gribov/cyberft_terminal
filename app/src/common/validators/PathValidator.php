<?php

namespace common\validators;

use yii\validators\Validator;

/**
 * @todo Мутная логика, лучше разбить на два валидатора FilePath и DirPath
 */
class PathValidator extends Validator
{
    public $checkFile = false;

    public function validateAttribute($model, $attribute)
    {
        if (!is_dir($model->$attribute) && ($this->checkFile && !is_file($model->$attribute))) {
            $this->addError($model, $attribute, \Yii::t('app', 'Path {value} not found'));
        }

        if (!is_readable($model->$attribute) || !is_writable($model->$attribute)) {
            $this->addError($model, $attribute, \Yii::t('app', 'No access to the directory {value}'));
        }
    }
}
