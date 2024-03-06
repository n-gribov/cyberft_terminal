<?php

namespace common\helpers\sbbol\SBBOLDocumentDigestBuilder;

class XMLDataExtractor
{
    private $xmlContent;
    private $collectedPaths;
    private $documentIndex;
    private $documentPath;
    private $collectedData;
    private $pathParts;
    private $currentDocumentIndex;
    private $isInDocument;

    public function __construct($xmlContent, $collectedPaths, $documentIndex = null, $documentPath = null)
    {
        $this->xmlContent = $xmlContent;
        $this->collectedPaths = static::normalizeCollectedPaths($collectedPaths);
        $this->documentIndex = $documentIndex;
        $this->documentPath = $documentPath;
    }

    public function extract()
    {
        $this->collectedData = [];
        $this->pathParts = [];
        $this->currentDocumentIndex = -1;
        $this->isInDocument = false;

        $xmlReader = new XMLReader($this->xmlContent);

        $xmlReader->onTagOpen(function (XMLReader\Tag $tag) {
            $this->pathParts[] = $tag->getTagName();
            if ($this->isDocumentNode()) {
                $this->isInDocument = true;
                $this->currentDocumentIndex++;
            }
        });

        $xmlReader->onTagClose(function (XMLReader\Tag $tag) {
            if (!$this->isInDocument || $this->currentDocumentIndex === $this->documentIndex) {
                $this->collectValue($tag);
                $this->collectAttributes($tag);
                $this->collectTable($tag);
            }

            if ($this->isDocumentNode()) {
                $this->isInDocument = false;
            }

            array_pop($this->pathParts);
        });
        $xmlReader->read();

        return $this->collectedData;
    }

    private function isDocumentNode()
    {
        return !empty($this->documentPath) && $this->buildPath($this->pathParts) === $this->documentPath;
    }

    private function collectValue(XMLReader\Tag $tag)
    {
        if ($this->isCollectedValuePath($this->pathParts)) {
            $this->collectedData[$this->buildPath($this->pathParts)] = $tag->extractInnerXml($this->xmlContent);
        }
    }

    private function collectAttributes(XMLReader\Tag $tag)
    {
        if (!$tag->hasAttributes()) {
            return;
        }

        $attributes = static::extractRawAttributes($tag->extractOpeningTag($this->xmlContent), $tag->getTagName());
        foreach ($attributes as $key => $value) {
            $attributePathParts = array_merge($this->pathParts, ["@$key"]);
            if ($this->isCollectedValuePath($attributePathParts)) {
                $this->collectedData[$this->buildPath($attributePathParts)] = $value;
            }
        }
    }

    private function collectTable(XMLReader\Tag $tag)
    {
        if (!$this->isCollectedTablePath($this->pathParts)) {
            return;
        }

        $path = $this->buildPath($this->pathParts);
        $tableCollectedPaths = $this->collectedPaths[$path];
        $collectedTableData = $this->collectedData[$path] ?? [];

        $tableNodeXml = $tag->extractOuterXml($this->xmlContent);
        $tableNodeReader = new XMLReader($tableNodeXml);
        $tableNodeReader->onTagClose(function (XMLReader\Tag $tag) use ($tableNodeXml, $tableCollectedPaths, &$collectedTableData) {
            if ($tag->getLevel() === 2) {
                $tableNodeItemXml = $tag->extractOuterXml($tableNodeXml);
                $dataExtractor = new static(
                    $tableNodeItemXml,
                    $tableCollectedPaths
                );
                $tableItemData = $dataExtractor->extract();
                if (!empty($tableItemData)) {
                    $collectedTableData[] = $tableItemData;
                }
            }
        });
        $tableNodeReader->read();

        $this->collectedData[$path] = $collectedTableData;
    }

    private static function normalizeCollectedPaths($paths)
    {
        return array_reduce(
            array_keys($paths),
            function ($carry, $key) use ($paths) {
                $value = $paths[$key];
                if (is_numeric($key)) {
                    $carry[$value] = $value;
                } else {
                    $carry[$key] = $value;
                }
                return $carry;
            },
            []
        );
    }

    private function isCollectedValuePath(array $pathParts): bool
    {
        $pathString = $this->buildPath($pathParts);

        return array_key_exists($pathString, $this->collectedPaths)
            && !is_array($this->collectedPaths[$pathString]);
    }

    private function isCollectedTablePath(array $pathParts): bool
    {
        $pathString = $this->buildPath($pathParts);

        return array_key_exists($pathString, $this->collectedPaths)
            && is_array($this->collectedPaths[$pathString]);
    }

    private function buildPath(array $pathParts): string
    {
        return '/' . implode('/', $pathParts);
    }

    private static function extractRawAttributes($xml, $tagName)
    {
        $tagRe = "/(<$tagName\s+.*?\/?>)/s";
        if (!preg_match($tagRe, $xml, $matches)) {
            return [];
        }
        $tagXmlContent = $matches[1];

        $attributeRe = '/(?<key>\w+)="(?<value>.*?)"/';
        if (preg_match_all($attributeRe, $tagXmlContent, $matches, PREG_SET_ORDER)) {
            return array_reduce(
                $matches,
                function ($carry, $match) {
                    $carry[$match['key']] = $match['value'];
                    return $carry;
                },
                []
            );
        }

        return [];
    }
}
