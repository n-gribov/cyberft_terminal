<?php

namespace common\helpers;

class SimpleXMLHelper
{
    public static function insertAfterTags(\SimpleXMLElement $element, \SimpleXMLElement $parent, array $tags): \SimpleXMLElement
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

    public static function prependChild(\SimpleXMLElement $element, \SimpleXMLElement $parent): \SimpleXMLElement
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

    private static function insertAfter(\SimpleXMLElement $element, \SimpleXMLElement $sibling): void
    {
        $siblingDom = dom_import_simplexml($sibling);
        $elementDom = $siblingDom->ownerDocument->importNode(dom_import_simplexml($element), true);
        if ($siblingDom->nextSibling) {
            $siblingDom->parentNode->insertBefore($elementDom, $siblingDom->nextSibling);
        } else {
            $siblingDom->parentNode->appendChild($elementDom);
        }
    }
}
