<?php
namespace addons\swiftfin\models\documents\mt\tagwrapper;

use common\helpers\NumericHelper;
/**
 * Класс для помощи в кастомном отображении тэга 32A
 *
 * @author nikolaenko
 */
class TagWrapper32A
{
    private $_tag;

    public function __construct($tag)
    {
        $this->_tag = $tag;
    }

    public function getReadable()
    {
        $scheme = $this->_tag->scheme;
        $date = date_create_from_format('ymd', $this->_tag->date);

        $currency = $scheme['currency']['label'];
        if (mb_strlen($currency) < 8) {
            $currency .= "\t";
        }

        return $scheme['date']['label'] . "\t:\t" . $date->format('d.m.Y') . "\n"
                . $currency . ":\t" . $this->_tag->currency . "\n"
                . $scheme['sum']['label'] . "\t:\t" . NumericHelper::getFloatValue($this->_tag->sum);
    }
}