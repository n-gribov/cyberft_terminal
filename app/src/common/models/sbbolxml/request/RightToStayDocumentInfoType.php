<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RightToStayDocumentInfoType
 *
 * Данные документа, подтверждающего право иностранного гражданина или лица без гражданства на пребывание (проживание) в
 *  РФ
 * XSD Type: RightToStayDocumentInfo
 */
class RightToStayDocumentInfoType
{

    /**
     * Документ Не требуется
     *
     * @property boolean $rightToStayDocumentNotRequired
     */
    private $rightToStayDocumentNotRequired = null;

    /**
     * Тип документа, подтверждающего право иностранного гражданина или лица без гражданства на пребывание (проживание)
     *  в РФ
     *
     * @property \common\models\sbbolxml\request\RightToStayDocumentType $rightToStayDocumentType
     */
    private $rightToStayDocumentType = null;

    /**
     * Gets as rightToStayDocumentNotRequired
     *
     * Документ Не требуется
     *
     * @return boolean
     */
    public function getRightToStayDocumentNotRequired()
    {
        return $this->rightToStayDocumentNotRequired;
    }

    /**
     * Sets a new rightToStayDocumentNotRequired
     *
     * Документ Не требуется
     *
     * @param boolean $rightToStayDocumentNotRequired
     * @return static
     */
    public function setRightToStayDocumentNotRequired($rightToStayDocumentNotRequired)
    {
        $this->rightToStayDocumentNotRequired = $rightToStayDocumentNotRequired;
        return $this;
    }

    /**
     * Gets as rightToStayDocumentType
     *
     * Тип документа, подтверждающего право иностранного гражданина или лица без гражданства на пребывание (проживание)
     *  в РФ
     *
     * @return \common\models\sbbolxml\request\RightToStayDocumentType
     */
    public function getRightToStayDocumentType()
    {
        return $this->rightToStayDocumentType;
    }

    /**
     * Sets a new rightToStayDocumentType
     *
     * Тип документа, подтверждающего право иностранного гражданина или лица без гражданства на пребывание (проживание)
     *  в РФ
     *
     * @param \common\models\sbbolxml\request\RightToStayDocumentType $rightToStayDocumentType
     * @return static
     */
    public function setRightToStayDocumentType(\common\models\sbbolxml\request\RightToStayDocumentType $rightToStayDocumentType)
    {
        $this->rightToStayDocumentType = $rightToStayDocumentType;
        return $this;
    }


}

