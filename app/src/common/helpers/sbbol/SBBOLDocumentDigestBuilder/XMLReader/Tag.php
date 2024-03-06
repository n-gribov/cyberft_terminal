<?php

namespace common\helpers\sbbol\SBBOLDocumentDigestBuilder\XMLReader;

class Tag {
    private $tagName;
    private $level;
    private $openingTagStartIndex;
    private $openingTagEndIndex;
    private $closingTagStartIndex;
    private $closingTagEndIndex;
    private $isSelfClosing;
    private $hasAttributes;

    public function __construct($tagName, $level, $openingTagStartIndex, $openingTagEndIndex, $hasAttributes, $isSelfClosing)
    {
        $this->tagName = $tagName;
        $this->level = $level;
        $this->openingTagStartIndex = $openingTagStartIndex;
        $this->openingTagEndIndex = $openingTagEndIndex;
        $this->hasAttributes = $hasAttributes;
        $this->isSelfClosing = $isSelfClosing;
    }

    public function close($closingTagStartIndex, $closingTagEndIndex)
    {
        if (!$this->isSelfClosing) {
            $this->closingTagStartIndex = $closingTagStartIndex;
            $this->closingTagEndIndex = $closingTagEndIndex;
        }
    }

    public function extractOpeningTag($xml)
    {
        return substr(
            $xml,
            $this->openingTagStartIndex,
            $this->openingTagEndIndex - $this->openingTagStartIndex
        );
    }

    public function extractInnerXml($xml)
    {
        if ($this->isSelfClosing) {
            return null;
        }

        return trim(
            substr(
                $xml,
                $this->openingTagEndIndex,
                $this->closingTagStartIndex - $this->openingTagEndIndex
            )
        );
    }

    public function extractOuterXml($xml)
    {
        $length = $this->isSelfClosing
            ? $this->openingTagEndIndex - $this->openingTagStartIndex
            : $this->closingTagEndIndex - $this->openingTagStartIndex;

        return substr(
            $xml,
            $this->openingTagStartIndex,
            $length
        );
    }

    public function getTagName()
    {
        return $this->tagName;
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function isSelfClosing()
    {
        return $this->isSelfClosing;
    }

    public function hasAttributes()
    {
        return $this->hasAttributes;
    }
}
