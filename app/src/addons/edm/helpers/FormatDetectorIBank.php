<?php

namespace addons\edm\helpers;

use addons\edm\models\IBank\V1\IBankV1Parser;
use addons\edm\models\IBank\V2\IBankV2Parser;
use common\base\interfaces\FormatDetectorInterface;
use yii\base\ErrorException;

class FormatDetectorIBank implements FormatDetectorInterface
{
    public static function detect($filePath, $options = [])
    {
        $rawData = file_get_contents($filePath);

        if ($rawData === false) {
            throw new ErrorException('Error: cannot read file ' . $filePath);
        }

        if (IBankV1Parser::isIBankV1Document($rawData)) {
            return IBankV1Parser::parse($rawData, $filePath);
        }

        if (IBankV2Parser::isIBankV2Document($rawData)) {
            return IBankV2Parser::parse($rawData, $filePath);
        }

        return false;
    }

}
