<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing FeesRegistryAcceptType
 *
 * Запрос изменения статуса Реестра платежей при выгрузке вложений
 * XSD Type: FeesRegistryAccept
 */
class FeesRegistryAcceptType
{

    /**
     * Идентификатор документа
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Gets as docId
     *
     * Идентификатор документа
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
     * Идентификатор документа
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }


}

