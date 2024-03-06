<?php

namespace common\helpers\sbbol;

use common\helpers\sbbol\SBBOLDocumentDigestBuilder\DigestConfigFactory;
use common\models\sbbolxml\digest\DigestConfig;
use common\models\sbbolxml\digest\DigestEntryConfig;
use common\models\sbbolxml\digest\DigestTableConfig;

class SBBOLDocumentDigestBuilder
{
    public static function build(string $xml, string $documentType, $documentIndex = 0)
    {
        $config = DigestConfigFactory::create($documentType);
        if ($config === null) {
            return null;
        }

        $paths = static::createCollectedPaths($config);
        $dataExtractor = new SBBOLDocumentDigestBuilder\XMLDataExtractor($xml, $paths, $documentIndex, $config->getDocumentPath());
        $digestData = $dataExtractor->extract();
        $digest = new SBBOLDocumentDigestBuilder\Digest($config, $digestData);

        return (string)$digest;
    }

    private static function createCollectedPaths(DigestConfig $config)
    {
        return array_merge(
            static::getEntriesPaths($config->getAttributes()),
            static::getEntriesPaths($config->getFields()),
            static::createTableCollectedPaths($config->getTables())
        );
    }

    private static function createTableCollectedPaths(array $tablesConfigs)
    {
        return array_reduce(
            $tablesConfigs,
            function ($carry, DigestTableConfig $tableConfig) {
                $fieldsPaths = static::getEntriesPaths($tableConfig->getFields());
                $nestedTablesPaths = static::createTableCollectedPaths($tableConfig->getNestedTables());
                $carry[$tableConfig->getPath()] = array_merge($fieldsPaths, $nestedTablesPaths);

                return $carry;
            },
            []
        );
    }

    private static function getEntriesPaths($entriesMap)
    {
        return array_map(
            function (DigestEntryConfig $entryConfig) {
                return $entryConfig->getPath();
            },
            array_values($entriesMap)
        );
    }
}
