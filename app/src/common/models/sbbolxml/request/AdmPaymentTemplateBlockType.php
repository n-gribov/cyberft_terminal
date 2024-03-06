<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AdmPaymentTemplateBlockType
 *
 * Блокировка\разблокировка элемента справочника Шаблоны внесения средств
 * XSD Type: AdmPaymentTemplateBlock
 */
class AdmPaymentTemplateBlockType
{

    /**
     * Id элемента справочника в СББОЛ
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Признак блокировки шаблона, 1 - установить, 0 - снять
     *
     * @property boolean $blocked
     */
    private $blocked = null;

    /**
     * Gets as docId
     *
     * Id элемента справочника в СББОЛ
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Id элемента справочника в СББОЛ
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as blocked
     *
     * Признак блокировки шаблона, 1 - установить, 0 - снять
     *
     * @return boolean
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Sets a new blocked
     *
     * Признак блокировки шаблона, 1 - установить, 0 - снять
     *
     * @param boolean $blocked
     * @return static
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
        return $this;
    }


}

