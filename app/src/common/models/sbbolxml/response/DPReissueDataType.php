<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DPReissueDataType
 *
 * Сведения о переоформлении паспорта сделки
 * XSD Type: DPReissueData
 */
class DPReissueDataType
{

    /**
     * Номер переоформления
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата переоформления
     *
     * @property \DateTime $dPReDate
     */
    private $dPReDate = null;

    /**
     * Номер документа, на основании которого внесены изменения в ПС
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата документа, на основании которого внесены изменения в ПС
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Результат обработки ПС: 1 - ПС переоформлен, 0 - отказ в переоформлении
     *
     * @property boolean $result
     */
    private $result = null;

    /**
     * Причина отказа в части представленных документов
     *
     * @property \common\models\sbbolxml\response\CaseType $formalCause
     */
    private $formalCause = null;

    /**
     * Причина отказа в части данных заявления
     *
     * @property \common\models\sbbolxml\response\FormalCauseDataType $formalCauseData
     */
    private $formalCauseData = null;

    /**
     * Gets as num
     *
     * Номер переоформления
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Sets a new num
     *
     * Номер переоформления
     *
     * @param string $num
     * @return static
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * Gets as dPReDate
     *
     * Дата переоформления
     *
     * @return \DateTime
     */
    public function getDPReDate()
    {
        return $this->dPReDate;
    }

    /**
     * Sets a new dPReDate
     *
     * Дата переоформления
     *
     * @param \DateTime $dPReDate
     * @return static
     */
    public function setDPReDate(\DateTime $dPReDate)
    {
        $this->dPReDate = $dPReDate;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер документа, на основании которого внесены изменения в ПС
     *
     * @return string
     */
    public function getDocNum()
    {
        return $this->docNum;
    }

    /**
     * Sets a new docNum
     *
     * Номер документа, на основании которого внесены изменения в ПС
     *
     * @param string $docNum
     * @return static
     */
    public function setDocNum($docNum)
    {
        $this->docNum = $docNum;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата документа, на основании которого внесены изменения в ПС
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата документа, на основании которого внесены изменения в ПС
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }

    /**
     * Gets as result
     *
     * Результат обработки ПС: 1 - ПС переоформлен, 0 - отказ в переоформлении
     *
     * @return boolean
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets a new result
     *
     * Результат обработки ПС: 1 - ПС переоформлен, 0 - отказ в переоформлении
     *
     * @param boolean $result
     * @return static
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }

    /**
     * Gets as formalCause
     *
     * Причина отказа в части представленных документов
     *
     * @return \common\models\sbbolxml\response\CaseType
     */
    public function getFormalCause()
    {
        return $this->formalCause;
    }

    /**
     * Sets a new formalCause
     *
     * Причина отказа в части представленных документов
     *
     * @param \common\models\sbbolxml\response\CaseType $formalCause
     * @return static
     */
    public function setFormalCause(\common\models\sbbolxml\response\CaseType $formalCause)
    {
        $this->formalCause = $formalCause;
        return $this;
    }

    /**
     * Gets as formalCauseData
     *
     * Причина отказа в части данных заявления
     *
     * @return \common\models\sbbolxml\response\FormalCauseDataType
     */
    public function getFormalCauseData()
    {
        return $this->formalCauseData;
    }

    /**
     * Sets a new formalCauseData
     *
     * Причина отказа в части данных заявления
     *
     * @param \common\models\sbbolxml\response\FormalCauseDataType $formalCauseData
     * @return static
     */
    public function setFormalCauseData(\common\models\sbbolxml\response\FormalCauseDataType $formalCauseData)
    {
        $this->formalCauseData = $formalCauseData;
        return $this;
    }


}

