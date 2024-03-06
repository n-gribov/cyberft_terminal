<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DocsAType;

/**
 * Class representing DocAType
 */
class DocAType
{

    /**
     * Тип документа
     *
     * @property string $docType
     */
    private $docType = null;

    /**
     * Gets as docType
     *
     * Тип документа
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
     * Тип документа
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

