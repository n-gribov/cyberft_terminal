<?php
namespace addons\edm\helpers;

use addons\edm\models\PaymentOrder\PaymentOrderType;
use addons\edm\models\PaymentRegister\PaymentRegisterType;
use common\base\interfaces\FormatDetectorInterface;
use common\helpers\StringHelper;
use yii\base\ErrorException;

/**
 * Format Detector for 1C import files
 */
class FormatDetector1C implements FormatDetectorInterface
{
    const DOCHEADER = 'СекцияДокумент=Платежное поручение';

    public static function getTypeByDocHeaders($content, $encoding = null)
    {
        $headerCount = static::countHeaders(StringHelper::utf8($content, $encoding));
        if ($headerCount == 1) {
            return PaymentOrderType::TYPE;
        } else if ($headerCount > 1) {
            return PaymentRegisterType::TYPE;
        } else {
            return null;
        }
    }

    /**
     * @param string $filePath
     * @param array $options
     * @return PaymentOrderType|PaymentOrderType[]|bool
     * @throws ErrorException
     *
     * Supported options:
     * - skipInvalidDocuments, true by default
     */
    public static function detect($filePath, $options = [])
    {
        $skipInvalidDocuments = !array_key_exists('skipInvalidDocuments', $options) || $options['skipInvalidDocuments'] === true;

        $rawData = file_get_contents($filePath);

        if ($rawData === false) {
            throw new ErrorException('Error: cannot read file ' . $filePath);
        }

        $rawData = StringHelper::utf8($rawData, PaymentOrderType::EXTERNAL_ENCODING);

        $headerCount = static::countHeaders($rawData);
        if ($headerCount === 0) {
            return false;
        }

        $rows = preg_split('/[\\r\\n]+/', $rawData);
        $startBody = false;
        $bodyData = '';
        $headerData = '';

        /**
         * FormatDetector1C dсегда возвращает массив моделей, даже если в файле только одна платежка.
         * Все, кто используют этот детектор, будут получать тип PaymentRegister, а не PaymentOrder
         */

        $arrModels = [];

        foreach ($rows as $row) {

            if ($row == static::DOCHEADER) {
                $startBody = true;
                $bodyData = '';

                continue;
            }

            if ($row == 'КонецДокумента') {
                $startBody = false;

                $model = new PaymentOrderType();
                $model->scenario = PaymentOrderType::SCENARIO_1C_IMPORT;

                $documentBody =
                    $headerData
                    . static::DOCHEADER
                    . static::detectNewLineCharacter($rawData)
                    . $bodyData
                    . 'КонецДокумента';
                $model->loadFromString($documentBody);

                if ($model->hasErrors() && $skipInvalidDocuments) {
                    continue;
                }

                $arrModels[] = $model;

                continue;
            }

            if ($startBody) {
                $bodyData .= $row . "\r\n";
            } else {
                $headerData .= $row . "\r\n";
            }
        }

        if (empty($arrModels)) {
            return false;
        }

        return $arrModels;
    }

    private static function detectNewLineCharacter($string)
    {
        preg_match('/(\r\n|\n|\r)/', $string, $matches);

        return empty($matches) ? null : $matches[0];
    }

    private static function countHeaders($data)
    {
        return substr_count($data, static::DOCHEADER);
    }

}