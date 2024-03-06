<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing FormalCauseDataType
 *
 *
 * XSD Type: FormalCauseData
 */
class FormalCauseDataType
{

    /**
     * Поле заявления о переоформлении
     *
     * @property string $docField
     */
    private $docField = null;

    /**
     * Правило заполнения/замечания
     *
     * @property string $reasonComment
     */
    private $reasonComment = null;

    /**
     * Комментарий
     *
     * @property string $returnComment
     */
    private $returnComment = null;

    /**
     * Gets as docField
     *
     * Поле заявления о переоформлении
     *
     * @return string
     */
    public function getDocField()
    {
        return $this->docField;
    }

    /**
     * Sets a new docField
     *
     * Поле заявления о переоформлении
     *
     * @param string $docField
     * @return static
     */
    public function setDocField($docField)
    {
        $this->docField = $docField;
        return $this;
    }

    /**
     * Gets as reasonComment
     *
     * Правило заполнения/замечания
     *
     * @return string
     */
    public function getReasonComment()
    {
        return $this->reasonComment;
    }

    /**
     * Sets a new reasonComment
     *
     * Правило заполнения/замечания
     *
     * @param string $reasonComment
     * @return static
     */
    public function setReasonComment($reasonComment)
    {
        $this->reasonComment = $reasonComment;
        return $this;
    }

    /**
     * Gets as returnComment
     *
     * Комментарий
     *
     * @return string
     */
    public function getReturnComment()
    {
        return $this->returnComment;
    }

    /**
     * Sets a new returnComment
     *
     * Комментарий
     *
     * @param string $returnComment
     * @return static
     */
    public function setReturnComment($returnComment)
    {
        $this->returnComment = $returnComment;
        return $this;
    }


}

