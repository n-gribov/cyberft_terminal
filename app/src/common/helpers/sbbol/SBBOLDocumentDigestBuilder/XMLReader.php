<?php

namespace common\helpers\sbbol\SBBOLDocumentDigestBuilder;

class XMLReader
{
    private $parser;
    private $xml;

    /** @var XMLReader\Tag[] */
    private $tagsPath = [];
    private $prevTagOpenIndex;
    private $prevTagCloseIndex;

    /** @var callable|null */
    private $onTagOpen;

    /** @var callable|null */
    private $onTagClose;

    public function __construct($xml)
    {
        $this->xml = $xml;

        $this->parser = xml_parser_create();
        xml_parser_set_option($this->parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parser_set_option($this->parser, XML_OPTION_CASE_FOLDING, 0);
        xml_set_object($this->parser, $this);
        xml_set_element_handler($this->parser, 'tagOpen', 'tagClose');;
    }

    public function __destruct()
    {
        xml_parser_free($this->parser);
        unset($this->parser);
    }

    public function read()
    {
        $this->tagsPath = [];
        $this->prevTagOpenIndex = 0;
        $this->prevTagCloseIndex = 0;

        xml_parse($this->parser, $this->xml);
    }

    private function tagOpen($parser, $tagName, $attributes)
    {
        $openingTagStartIndex = strpos($this->xml, "<$tagName", max($this->prevTagOpenIndex, $this->prevTagCloseIndex));
        if ($openingTagStartIndex === false) {
            throw new \Exception("Cannot find start position for tag $tagName");
        }
        $currentByteIndex = xml_get_current_byte_index($parser);
        $isSelfClosingTag = substr($this->xml, $currentByteIndex, 1) === '/';
        $hasAttributes = !empty($attributes);
        $openingTagEndIndex = $currentByteIndex + ($isSelfClosingTag ? 2 : 1);
        $level = count($this->tagsPath) + 1;

        $tag = new XMLReader\Tag($tagName, $level, $openingTagStartIndex, $openingTagEndIndex, $hasAttributes, $isSelfClosingTag);
        $this->tagsPath[] = $tag;

        if ($this->onTagOpen) {
            call_user_func($this->onTagOpen, $tag);
        }

        $this->prevTagOpenIndex = $currentByteIndex;

        return;
    }

    private function tagClose($parser, $tag)
    {
        $currentTag = array_pop($this->tagsPath);
        $currentByteIndex = xml_get_current_byte_index($parser);
        if (!$currentTag->isSelfClosing()) {
            $closingTagStartIndex = strpos($this->xml, "</$tag", $this->prevTagCloseIndex);
            $currentTag->close($closingTagStartIndex, $currentByteIndex);
        }

        $this->prevTagCloseIndex = $currentByteIndex;

        if ($this->onTagClose) {
            call_user_func($this->onTagClose, $currentTag);
        }

        return;
    }

    private function getPath()
    {
        return array_map(
            function (XMLReader\Tag $tag) {
                return $tag->getTagName();
            },
            $this->tagsPath
        );
    }

    public function onTagOpen(callable $onTagOpen)
    {
        $this->onTagOpen = $onTagOpen;
    }

    public function onTagClose(callable $onTagClose)
    {
        $this->onTagClose = $onTagClose;
    }
}
