<?php

namespace addons\edm\helpers;

use addons\edm\models\DictOrganization;
use addons\ISO20022\models\Auth025Type;
use addons\ISO20022\models\ISO20022Type;
use common\base\interfaces\FormatDetectorInterface;
use Yii;

final class FormatDetectorAuth025 implements FormatDetectorInterface
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

        if (!$typeModel instanceof Auth025Type) {
            return false;
        }

        $typeModel->sender = self::findSenderAddress($typeModel);

        return $typeModel;
    }

    private static function findSenderAddress(Auth025Type $typeModel): ?string
    {
        $inn = $typeModel->getSenderTxId();
        if (!$inn) {
            Yii::info('Missing sender INN in imported auth.025 document');
            return null;
        }
        $organization = DictOrganization::findOne(['inn' => $inn]);
        if ($organization === null) {
            Yii::info("Error while importing auth.025 document, organization with INN $inn is not found");
        }
        return $organization->terminal->terminalId;
    }
}
