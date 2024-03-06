<?php

namespace common\helpers\sbbol\SBBOLDocumentDigestBuilder;

use common\models\sbbolxml\digest\DigestConfig;
use Yii;

class DigestConfigFactory
{
    const METADATA_DIR = '@common/models/sbbolxml/digest/_metadata';

    public static function create(string $documentType): DigestConfig
    {
        $configPath = Yii::getAlias(static::METADATA_DIR . "/$documentType.yml");
        if (!is_file($configPath)) {
            throw new \Exception("Unsupported document type $documentType");
        }

        return new DigestConfig($configPath);
    }
}
