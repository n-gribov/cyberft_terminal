<?php

namespace common\helpers\sbbol\SBBOLDocumentDigestBuilder;

use common\models\sbbolxml\digest\DigestConfig;
use common\models\sbbolxml\digest\DigestEntryConfig;
use common\models\sbbolxml\digest\DigestTableConfig;

class Digest
{
    /** @var DigestConfig */
    private $config;
    private $data;

    public function __construct(DigestConfig $config, array $data)
    {
        $this->config = $config;
        $this->data = $data;
    }

    public function __toString()
    {
        return implode(
            "\n",
            array_filter([
                $this->createAttributesDigest(),
                $this->createFieldsDigest(),
                $this->createTablesDigest(),
            ])
        );
    }

    public function getAttributes(): array
    {
        return static::mapData($this->data, $this->config->getAttributes());
    }

    public function getFields(): array
    {
        return static::mapData($this->data, $this->config->getFields());
    }

    public function getTables(): array
    {
        if (empty($this->config->getTables())) {
            return [];
        }

        $tablesConfig = $this->config->getTables();
        return array_reduce(
            $tablesConfig,
            function (array $carry, DigestTableConfig $tableConfig) {
                $tableData = static::getTable($tableConfig, $this->data);
                if (!empty($tableData)) {
                    $carry[$tableConfig->getName()] = $tableData;
                }

                return $carry;
            },
            []
        );
    }

    private static function getTable(DigestTableConfig $tableConfig, $data)
    {
        $dataKey = $tableConfig->getPath();
        if (!array_key_exists($dataKey, $data)) {
            return null;
        }

        $tableData = array_map(
            function($rowData) use ($tableConfig) {
                return static::createTableRow($tableConfig, $rowData);
            },
            $data[$dataKey]
        );

        if (!empty($tableData)) {
            return static::sortData($tableData, $tableConfig->getSortKeyBuilder());
        }

        return null;
    }

    private static function createTableRow(DigestTableConfig $tableConfig, $rowData) {
        $fieldsMapping = $tableConfig->getFields();
        $fieldsData = static::mapData($rowData, $fieldsMapping);

        $nestedTablesData = array_reduce(
            $tableConfig->getNestedTables(),
            function ($carry, DigestTableConfig $nestedTableConfig) use ($tableConfig, $rowData) {
                $nestedTable = static::getTable($nestedTableConfig, $rowData);

                if (!empty($nestedTable)) {
                    $carry[$nestedTableConfig->getName()] = $nestedTable;
                }

                return $carry;
            },
            []
        );

        return array_merge($fieldsData, $nestedTablesData);
    }

    private static function mapData($data, $mapping)
    {
        return array_reduce(
            array_keys($mapping),
            function ($carry, $key) use ($mapping, $data) {

                /** @var DigestEntryConfig $entryConfig */
                $entryConfig = $dataKey = $mapping[$key];
                $dataKey = $entryConfig->getPath();
                $value = $data[$dataKey] ?? null;
                if ($value !== null && $value !== '') {
                    $valueForDigest = $entryConfig->getFormat()
                        ? static::formatValue($value, $entryConfig->getFormat())
                        : static::unescapeXml($value);
                    $carry[$key] = $valueForDigest;
                }
                return $carry;
            },
            []
        );
    }

    private static function sortData(array $tableData, $sortKeyBuilder)
    {
        if ($sortKeyBuilder === null) {
            return $tableData;
        }

        usort(
            $tableData,
            function ($a, $b) use ($sortKeyBuilder) {
                $aKey = $sortKeyBuilder($a);
                $bKey = $sortKeyBuilder($b);

                return strcmp($aKey, $bKey);
            }
        );

        return $tableData;
    }

    private function createAttributesDigest()
    {
        $attributes = $this->getAttributes();
        if (!$this->config->hasAttributesSection()) {
            return null;
        }

        $stings = array_merge(['ATTRIBUTES'], static::digestDataToStrings($attributes));

        return implode("\n", $stings);
    }

    private function createFieldsDigest()
    {
        $fields = $this->getFields();
        if (!$this->config->hasFieldsSection()) {
            return null;
        }

        $stings = array_merge(['FIELDS'], static::digestDataToStrings($fields));

        return implode("\n", $stings);
    }

    private function createTablesDigest()
    {
        $tables = $this->getTables();
        if (empty($tables)) {
            return null;
        }

        $tableDigests = array_map(
            function($tableKey) use ($tables) {
                $tableData = $tables[$tableKey];

                return static::createTableDigest($tableKey, $tableData);
            },
            array_keys($tables)
        );
        $parts = array_merge(['TABLES'], $tableDigests);

        return implode("\n", $parts);
    }

    public static function createTableDigest($tableName, $tableData)
    {
        $rowsDigests = array_map(
            function ($rowData) {
                return implode("\n", static::digestDataToStrings($rowData)) . "\n#";
            },
            $tableData
        );

        $parts = array_merge(["Table=$tableName"], $rowsDigests);

        return implode("\n", $parts);
    }

    private static function fixNewLines($value)
    {
        return preg_replace('/(?:\r\n|\r|\n)/', "\n", $value);
    }

    private static function digestDataToStrings(array $data)
    {
        return array_map(
            function ($key) use ($data) {
                $value = $data[$key];

                if (is_array($value)) {
                    return static::createTableDigest($key, $value);
                } else {
                    return "$key="  . static::fixNewLines($data[$key]);
                }
            },
            array_keys($data)
        );
    }

    private static function formatValue($value, $format)
    {
        switch ($format) {
            case 'currency':
                return sprintf('%0.2f', $value);
            case 'datetime':
                return (new \DateTime($value))->format('Y-m-d\\TH:i:s');
            case 'date':
                return (new \DateTime($value))->format('Y-m-d');
            default:
                throw new \Exception("Unsupported format: $format");
        }
    }

    private static function unescapeXml($escaped)
    {
        $document = new \DOMDocument();
        $fragment = $document->createDocumentFragment();
        $fragment->appendXML($escaped);

        return $fragment->textContent;
    }
}
