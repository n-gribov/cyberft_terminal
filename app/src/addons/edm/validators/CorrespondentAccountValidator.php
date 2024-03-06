<?php

namespace addons\edm\validators;


use Exception;
use yii\helpers\StringHelper;
use yii\validators\Validator;

class CorrespondentAccountValidator extends Validator
{
    public $bikKey;

    public function validateAttribute($model, $attribute)
    {
        $defaultErrorMessage = 'Номер счета указан неверно';

        $bik = $this->getBikValue($model);
        $correspondentAccount = $model->$attribute;

        if (empty($bik)) {
            $this->addError($model, $this->bikKey, 'Значение БИК не задано');
            return;
        }

        $isBankBik = StringHelper::startsWith($bik, '04');
        if (!$isBankBik) {
            return;
        }

        $bikLast3Digits = substr($bik, -3, 3);
        if (!preg_match('/^\d{3}$/', $bikLast3Digits)) {
            $this->addError($model, $attribute, $defaultErrorMessage);
            return;
        }
        if (!preg_match("/^301\\d{14}$bikLast3Digits$/", $correspondentAccount)) {
            $this->addError($model, $attribute, $defaultErrorMessage);
            return;
        }
    }

    private function getBikValue($model)
    {
        if (isset($model->{$this->bikKey})) {
            return $model->{$this->bikKey};
        }
        throw new Exception('Undefined key bankBik property');
    }
}
