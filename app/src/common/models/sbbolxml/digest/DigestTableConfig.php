<?php

namespace common\models\sbbolxml\digest;

class DigestTableConfig
{
    private $name;
    private $path;
    private $fields;
    private $sortKeyBuilder;

    /** @var DigestTableConfig[] */
    private $nestedTables;

    /**
     * DigestTableConfig constructor.
     * @param string $name
     * @param string $path
     * @param array $fields
     * @param array|null $sortFields
     * @param DigestTableConfig[] $nestedTables
     */
    public function __construct(string $name, string $path, array $fields, $sortFields, array $nestedTables)
    {
        $this->name = $name;
        $this->path = $path;
        $this->fields = array_reduce(
            array_keys($fields),
            function ($carry, $key) use ($fields) {
                $carry[$key] = new DigestEntryConfig($fields[$key]);
                return $carry;
            },
            []
        );
        $this->nestedTables = $nestedTables;

        if (!empty($sortFields)) {
            $this->sortKeyBuilder = function ($item) use ($sortFields) {
                return array_reduce(
                    $sortFields,
                    function ($carry, $field) use ($item) {
                        return $carry . @$item[$field];
                    },
                    ''
                );
            };
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getFields(): array
    {
        return $this->fields;
    }

    /**
     * @return DigestTableConfig[]
     */
    public function getNestedTables()
    {
        return $this->nestedTables;
    }

    /**
     * @return \Closure|null
     */
    public function getSortKeyBuilder()
    {
        return $this->sortKeyBuilder;
    }
}
