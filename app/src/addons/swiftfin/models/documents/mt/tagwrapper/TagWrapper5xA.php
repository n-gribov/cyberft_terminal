<?php
namespace addons\swiftfin\models\documents\mt\tagwrapper;

use addons\swiftfin\models\SwiftFinDictBank;

/**
 * Класс для помощи в кастомном отображении тэгов 50-й серии
 *
 * @author nikolaenko
 */
class TagWrapper5xA
{
    private $_tag;

    public function __construct($tag)
    {
        $this->_tag = $tag;
    }

    public function getReadable()
    {
        $bik = trim($this->_tag->getValue());

        $len = strlen($bik);
        $code = substr($bik, $len - 11 ,$len);

        if (strlen($code) == 11) {
            $swiftCode = substr($code, 0, 8);
            $branchCode = substr($code, 8);

            $bank = SwiftFinDictBank::findOne([
                'swiftCode' => $swiftCode,
                'branchCode' => $branchCode
            ]);
            if (!empty($bank)) {
                $bik .= PHP_EOL . $bank->name;
                if (!empty($bank->address)) {
                    $bik .= PHP_EOL . $bank->address;
                }
            }
        }

        return $bik;
    }

    public function getPrintable()
    {
        $bik = trim($this->_tag->getValue());

        $len = strlen($bik);
        $code = substr($bik, $len - 11 ,$len);

        if (strlen($code) == 11) {
            $swiftCode = substr($code, 0, 8);
            $branchCode = substr($code, 8);

            $bank = SwiftFinDictBank::findOne([
                'swiftCode' => $swiftCode,
                'branchCode' => $branchCode
            ]);
            if (!empty($bank)) {
                $bik .= PHP_EOL . $bank->name;
                if (!empty($bank->address)) {
                    $bik .= PHP_EOL . $bank->address;
                }
            }
        }

        return $bik;
    }
}