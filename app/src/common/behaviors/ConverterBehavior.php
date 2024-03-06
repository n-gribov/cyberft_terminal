<?php
namespace common\behaviors;

/**
 * Description of ConverterBehavior
 *
 * @author fuzz
 */
abstract class ConverterBehavior extends \yii\base\Behavior
{
    public $attributes = [];

    public function canGetProperty($name, $checkVars = true)
    {
        return isset($this->attributes[$name]) || parent::canGetProperty($name, $checkVars);
    }

    public function canSetProperty($name, $checkVars = true)
    {
        return isset($this->attributes[$name]) || parent::canSetProperty($name, $checkVars);
    }

    public function __get($param)
    {
        if (isset($this->attributes[$param])) {
            return $this->convertFromStoredFormat($this->owner->{$this->attributes[$param]});
        } else {
            return parent::__get($param);
        }
    }

    public function __set($param, $value)
    {
        if (isset($this->attributes[$param])) {
            $this->owner->{$this->attributes[$param]} = $this->convertToStoredFormat($value);
        } else {
            parent::__set($param, $value);
        }
    }

    abstract protected function convertToStoredFormat($value); //

    abstract protected function convertFromStoredFormat($value);

}