<?php

namespace common\models\raiffeisenxml\request\RequestType\DocIdsAType;

/**
 * Class representing DocIdAType
 */
class DocIdAType
{

    /**
     * (UUID документа)
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * (модель документа)
     *
     * @property string $docType
     */
    private $docType = null;

    /**
     * Gets as docId
     *
     * (UUID документа)
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
     * (UUID документа)
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
     * Gets as docType
     *
     * (модель документа)
     *
     * @return string
     */
    public function getDocType()
    {
        return $this->docType;
    }

    /**
     * Sets a new docType
     *
     * (модель документа)
     *
     * @param string $docType
     * @return static
     */
    public function setDocType($docType)
    {
        $this->docType = $docType;
        return $this;
    }


}

