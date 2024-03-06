<?php

namespace addons\edm\helpers;

use addons\edm\models\EdmPayerAccount;
use addons\ISO20022\models\Auth024Type;
use addons\ISO20022\models\ISO20022Type;
use common\base\BaseType;
use common\base\interfaces\FormatDetectorInterface;
use Yii;

final class FormatDetectorAuth024 implements FormatDetectorInterface
{
    use DetectsXml;

    public static function detect($filePath, $options = [])
    {
        try {
            $body = file_get_contents($filePath);
            if (!self::isXml($body)) {
                return false;
            }

            $typeModel = ISO20022Type::getModelFromString($body);
        } catch (\Exception $ex) {
            Yii::error(__METHOD__ . ': ' . $ex->getMessage());
            return false;
        }

        if (!$typeModel) {
            return false;
        }

        if (!static::isForeignCurrencyInformation($typeModel)) {
            return false;
        }

        $typeModel->sender = self::findSenderAddress($typeModel);

        return $typeModel;
    }

    public static function isForeignCurrencyInformation(BaseType $typeModel): bool
    {
        return $typeModel instanceof Auth024Type;
    }

    private static function findSenderAddress(Auth024Type $typeModel): ?string
    {
        $account = EdmPayerAccount::findOne(['number' => $typeModel->getSenderAccountNumber()]);
        return $account ? $account->edmDictOrganization->terminal->terminalId : null;
    }
}
