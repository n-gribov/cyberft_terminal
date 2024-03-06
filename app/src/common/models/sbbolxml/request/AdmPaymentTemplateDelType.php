<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AdmPaymentTemplateDelType
 *
 * Удаление документа "Шаблон внесения средств"
 * XSD Type: AdmPaymentTemplateDel
 */
class AdmPaymentTemplateDelType
{

    /**
     * Идентификатор документа
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Идентификатор сущности в УС.
     *
     * @property string $docExtId
     */
    private $docExtId = null;

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

    /**
     * Gets as docExtId
     *
     * Идентификатор сущности в УС.
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
     * Идентификатор сущности в УС.
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

