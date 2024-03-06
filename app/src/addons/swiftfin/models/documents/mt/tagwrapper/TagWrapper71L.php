<?php
namespace addons\swiftfin\models\documents\mt\tagwrapper;

use common\helpers\NumericHelper;
/**
 * Класс для помощи в кастомном отображении тэга 71F
 *
 * @author nikolaenko
 */
class TagWrapper71L
{
    private $_tag;

    public function __construct($tag)
    {
        $this->_tag = $tag;
    }

    public function getReadable()
    {
        $value = $this->_tag->getValue();

        if (!$value) {
            return null;
        }

        $scheme = $this->_tag->scheme;
        $currency = $scheme['currency']['label'];

        if (mb_strlen($currency) < 8) {
            $currency .= "\t";
        }

        return $currency . ":\t" . substr($value, 0, 3) . "\n"
        . $scheme['sum']['label'] . "\t:\t" . NumericHelper::getFloatValue(substr($value, 3));
    }
}