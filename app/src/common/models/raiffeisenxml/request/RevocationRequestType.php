<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing RevocationRequestType
 *
 *
 * XSD Type: RevocationRequest
 */
class RevocationRequestType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * @property \common\models\raiffeisenxml\request\RevocationRequestType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в УС
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
     * Идентификатор документа в УС
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

    /**
     * Gets as docData
     *
     * @return \common\models\raiffeisenxml\request\RevocationRequestType\DocDataAType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * @param \common\models\raiffeisenxml\request\RevocationRequestType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\RevocationRequestType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }


}

