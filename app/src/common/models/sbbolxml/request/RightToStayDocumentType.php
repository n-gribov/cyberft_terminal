<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RightToStayDocumentType
 *
 * Тип документа, подтверждающего право иностранного гражданина или лица без гражданства на пребывание (проживание) в РФ
 * XSD Type: RightToStayDocumentType
 */
class RightToStayDocumentType
{

    /**
     * Виза
     *
     * @property boolean $visa
     */
    private $visa = null;

    /**
     * Вид на жительство
     *
     * @property boolean $residencePermit
     */
    private $residencePermit = null;

    /**
     * Разрешение на временное пребывание
     *
     * @property boolean $temporaryResidencePermit
     */
    private $temporaryResidencePermit = null;

    /**
     * @property \common\models\sbbolxml\request\RightToStayDocumentType\OtherRightToStayDocumentAType $otherRightToStayDocument
     */
    private $otherRightToStayDocument = null;

    /**
     * Gets as visa
     *
     * Виза
     *
     * @return boolean
     */
    public function getVisa()
    {
        return $this->visa;
    }

    /**
     * Sets a new visa
     *
     * Виза
     *
     * @param boolean $visa
     * @return static
     */
    public function setVisa($visa)
    {
        $this->visa = $visa;
        return $this;
    }

    /**
     * Gets as residencePermit
     *
     * Вид на жительство
     *
     * @return boolean
     */
    public function getResidencePermit()
    {
        return $this->residencePermit;
    }

    /**
     * Sets a new residencePermit
     *
     * Вид на жительство
     *
     * @param boolean $residencePermit
     * @return static
     */
    public function setResidencePermit($residencePermit)
    {
        $this->residencePermit = $residencePermit;
        return $this;
    }

    /**
     * Gets as temporaryResidencePermit
     *
     * Разрешение на временное пребывание
     *
     * @return boolean
     */
    public function getTemporaryResidencePermit()
    {
        return $this->temporaryResidencePermit;
    }

    /**
     * Sets a new temporaryResidencePermit
     *
     * Разрешение на временное пребывание
     *
     * @param boolean $temporaryResidencePermit
     * @return static
     */
    public function setTemporaryResidencePermit($temporaryResidencePermit)
    {
        $this->temporaryResidencePermit = $temporaryResidencePermit;
        return $this;
    }

    /**
     * Gets as otherRightToStayDocument
     *
     * @return \common\models\sbbolxml\request\RightToStayDocumentType\OtherRightToStayDocumentAType
     */
    public function getOtherRightToStayDocument()
    {
        return $this->otherRightToStayDocument;
    }

    /**
     * Sets a new otherRightToStayDocument
     *
     * @param \common\models\sbbolxml\request\RightToStayDocumentType\OtherRightToStayDocumentAType $otherRightToStayDocument
     * @return static
     */
    public function setOtherRightToStayDocument(\common\models\sbbolxml\request\RightToStayDocumentType\OtherRightToStayDocumentAType $otherRightToStayDocument)
    {
        $this->otherRightToStayDocument = $otherRightToStayDocument;
        return $this;
    }


}

