<?php
namespace common\helpers;

class Html extends \yii\helpers\Html
{
    public static function a($text, $url = null, $options = [], $icon = '')
    {
        if (!empty($icon)) {
            $text = '<span class="' .$icon. '"></span> ' . $text;
        }

        return parent::a($text, $url, $options);
    }
}
