<?php
namespace common\base;
/**
 * Description of DOMCyberXml
 *
 * @author nikolaenko
 */
class DOMCyberXml
{
    /**
     * Каждая операция вставки в DOM узла с объявленным неймспейсом порождает префиксы default или другие,
     * если они нашлись ранее в этом неймспейсе.
     *
     * Это приводит к изменению исходного документа и ошибкам валидации и т.д.
     * Вариантов сделать это штатными методами и избежать вставки префиксов нет.
     * DOMDocument не дает это сделать, а SimpleXML требует итеративной вставки, которая начинает
     * глючить при работе с неймспейсами.
     *
     * Обход узла в DOM и выпиливание префиксов также не помогает, потому что префикс исчезает,
     * но остается двоеточие от него.
     *
     * Единственный найденный выход - вставлять узел в виде строки. Для этого вместо реальной вставки
     * сначала вставляется элемент PLACEHOLDER, затем документ переводится в строку, затем в строке PLACEHOLDER
     * заменяется на значение из узла, также сохраненного в строку.
     *
     * После этого получившаяся строка снова грузится в DOM.
     *
     * @param DOMNode $targetNode узел который нужно заменить
     * @param type $xmlString строка, которую надо вставить
     */
    public static function replaceChild($targetNode, $xmlString, $placeholder = 'PLACEHOLDER')
    {
        $placeholder = static::convertPlaceholder($placeholder);

        $dom = $targetNode->ownerDocument;
        $newFrag = $dom->createDocumentFragment();
        $newFrag->appendXML($placeholder);
        $parent = $targetNode->parentNode;
        $parent->replaceChild($newFrag, $targetNode);
        $result = str_replace($placeholder, $xmlString, $dom->saveXML());

        $dom->loadXML($result, LIBXML_PARSEHUGE);
    }

    public static function insertBefore($targetNode, $xmlString, $beforeNode = null, $placeholder = 'PLACEHOLDER')
    {
        $placeholder = static::convertPlaceholder($placeholder);

        $dom = $targetNode->ownerDocument;

        $newFrag = $dom->createDocumentFragment();
        $newFrag->appendXML($placeholder);

		if (is_null($beforeNode)) {
			$targetNode->insertBefore($newFrag);
		} else {
			$targetNode->insertBefore($newFrag, $beforeNode);
		}

        $result = str_replace($placeholder, $xmlString, $dom->saveXML());

        $dom->loadXML($result, LIBXML_PARSEHUGE);
    }

    protected static function convertPlaceholder($placeholder)
    {
        return '<' . $placeholder . '/>';
    }

}