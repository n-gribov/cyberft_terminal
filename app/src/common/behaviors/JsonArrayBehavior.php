<?php
namespace common\behaviors;

class JsonArrayBehavior extends ConverterBehavior
{
    protected function convertToStoredFormat($value)
    {
        return json_encode($value);
    }

    protected function convertFromStoredFormat($value)
    {
        $array = json_decode($value, true);
        return $array;
    }
}