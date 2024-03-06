<?php

namespace common\models\sbbolxml\request\RequestType\DocDigestAType;

/**
 * Class representing DocIdAType
 */
class DocIdAType
{

    /**
     * Тикет СББОЛ (UUID документа)
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Идентификатор документа в УС.
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Gets as docId
     *
     * Тикет СББОЛ (UUID документа)
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
     * Тикет СББОЛ (UUID документа)
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
     * Gets as docExtId
     *
     * Идентификатор документа в УС.
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор документа в УС.
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }


}

