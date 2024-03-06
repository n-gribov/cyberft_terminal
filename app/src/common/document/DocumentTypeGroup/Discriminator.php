<?php

namespace common\document\DocumentTypeGroup;

use yii\base\BaseObject;

class Discriminator extends BaseObject
{
    public $attribute;
    public $value;

    public function match(array $extModelAttributes): bool
    {
        if (array_key_exists($this->attribute, $extModelAttributes)) {
            return $extModelAttributes[$this->attribute] === $this->value;
        }

        return false;
    }
}
