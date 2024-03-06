<?php

namespace common\helpers;

use SimpleXMLElement;

class SimpleXMLHelper
{
    public static function insertAfterTags(SimpleXMLElement $element, SimpleXMLElement $parent, array $tags): SimpleXMLElement
    {
        $sibling = null;
        foreach ($tags as $tag) {
            if ($parent->$tag) {
                $sibling = $parent->$tag;
            }
        }
        if ($sibling) {
            self::insertAfter($element, $sibling);
        } else {
            self::prependChild($element, $parent);
        }

        $elementTagName = $element->getName();
        return $parent->$elementTagName;
    }

    public static function prependChild(SimpleXMLElement $element, SimpleXMLElement $parent): SimpleXMLElement
    {
        $parentDom = dom_import_simplexml($parent);
        $elementDom = $parentDom->ownerDocument->importNode(dom_import_simplexml($element), true);
        if ($parentDom->childNodes->count() === 0) {
            $parentDom->appendChild($elementDom);
        } else {
            $parentDom->insertBefore($elementDom, $parentDom->firstChild);
        }

        $elementTagName = $element->getName();
        return $parent->$elementTagName;
    }

    /**
     * Метод добавляет элемент к XML
     * 
     * @param \SimpleXMLElement $from
     * @param \SimpleXMLElement $to
     */
    public static function insertAfter(SimpleXMLElement $from, SimpleXMLElement $to): void
    {
        $toDom = dom_import_simplexml($to);
        $fromDom = $toDom->ownerDocument->importNode(dom_import_simplexml($from), true);
        if ($toDom->nextSibling) {
            $toDom->parentNode->insertBefore($fromDom, $toDom->nextSibling);
        } else {
            $toDom->parentNode->appendChild($fromDom);
        }
    }

}
