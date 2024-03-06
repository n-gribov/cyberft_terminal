<?php

namespace common\models\sbbolxml\digest;

use Symfony\Component\Yaml\Yaml;

class DigestConfig
{
    /** @var DigestEntryConfig[] */
    private $attributes = [];

    /** @var DigestEntryConfig[] */
    private $fields = [];

    /** @var DigestTableConfig[] */
    private $tables = [];

    private $documentPath = null;

    private $hasAttributesSection;
    private $hasFieldsSection;

    /**
     * DigestConfig constructor.
     * @param string $configPath
     */
    public function __construct(string $configPath)
    {
        $config = Yaml::parseFile($configPath);
        $pathPrefix = $config['path_prefix'] ?? null;

        $this->hasFieldsSection = array_key_exists('fields', $config);
        $this->hasAttributesSection = array_key_exists('attributes', $config);

        $this->attributes = static::createDigestEntriesMap($config['attributes'] ?? [], $pathPrefix);
        $this->fields = static::createDigestEntriesMap($config['fields'] ?? [], $pathPrefix);

        $this->tables = array_map(
            function ($tableConfigData) use ($pathPrefix) {
                return static::createTableConfig($tableConfigData, $pathPrefix);
            },
            $config['tables'] ?? []
        );

        $documentPath = $config['document_path'] ?? null;
        $this->documentPath = $documentPath === null ? null : $pathPrefix . $documentPath;
    }

    private static function createTableConfig(array $tableConfigData, $pathPrefix)
    {
        $nestedTablesConfigs = array_map(
            function (array $nestedTableConfigData) use ($tableConfigData, $pathPrefix) {
                return static::createTableConfig($nestedTableConfigData, '');
            },
            $tableConfigData['tables'] ?? []
        );

        return new DigestTableConfig(
            $tableConfigData['name'],
            $pathPrefix . $tableConfigData['path'],
            $tableConfigData['fields'],
            $tableConfigData['sort_by'] ?? [],
            $nestedTablesConfigs
        );
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    public function getDocumentPath()
    {
        return $this->documentPath;
    }

    /**
     * @return DigestTableConfig[]
     */
    public function getTables(): array
    {
        return $this->tables;
    }

    public function hasAttributesSection(): bool
    {
        return $this->hasAttributesSection;
    }

    public function hasFieldsSection(): bool
    {
        return $this->hasFieldsSection;
    }

    private static function createDigestEntriesMap(array $mapping, $pathPrefix)
    {
        return array_reduce(
            array_keys($mapping),
            function($carry, $key) use ($mapping, $pathPrefix) {
                $entrySpec = $mapping[$key];
                $carry[$key] = new DigestEntryConfig($entrySpec, $pathPrefix);
                return $carry;
            },
            []
        );
    }
}
