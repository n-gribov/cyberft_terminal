<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing VBKInfoReqParamType
 *
 *
 * XSD Type: VBKInfoReqParam
 */
class VBKInfoReqParamType
{

    /**
     * Предоставить ВБК полностью или частично. Возможные значения: 1 - полностью, 0 - частично
     *
     * @property boolean $fullPart
     */
    private $fullPart = null;

    /**
     * Предоставить ВБК по контракту или кредитному договору. Возможные значения: 1 - по контракту, 0 - по кредитному
     *  договору
     *
     * @property boolean $contractCredit
     */
    private $contractCredit = null;

    /**
     * Раздел II. Сведения о платежах
     *
     * @property boolean $part2
     */
    private $part2 = null;

    /**
     * Раздел III. Сведения о подтвержающих документах
     *
     * @property boolean $part3Contract
     */
    private $part3Contract = null;

    /**
     * Раздел V. Итоговые данные расчетов по контракту
     *
     * @property boolean $part5Contract
     */
    private $part5Contract = null;

    /**
     * Раздел III. Сведения об исполнении обязательств по основному долгу по иным основаниям, отличным от проведения
     *  расчетов в денежной форме
     *
     * @property boolean $part3Credit
     */
    private $part3Credit = null;

    /**
     * Раздел IV. Расчет задолженности по основному долгу
     *
     * @property boolean $part4Credit
     */
    private $part4Credit = null;

    /**
     * Gets as fullPart
     *
     * Предоставить ВБК полностью или частично. Возможные значения: 1 - полностью, 0 - частично
     *
     * @return boolean
     */
    public function getFullPart()
    {
        return $this->fullPart;
    }

    /**
     * Sets a new fullPart
     *
     * Предоставить ВБК полностью или частично. Возможные значения: 1 - полностью, 0 - частично
     *
     * @param boolean $fullPart
     * @return static
     */
    public function setFullPart($fullPart)
    {
        $this->fullPart = $fullPart;
        return $this;
    }

    /**
     * Gets as contractCredit
     *
     * Предоставить ВБК по контракту или кредитному договору. Возможные значения: 1 - по контракту, 0 - по кредитному
     *  договору
     *
     * @return boolean
     */
    public function getContractCredit()
    {
        return $this->contractCredit;
    }

    /**
     * Sets a new contractCredit
     *
     * Предоставить ВБК по контракту или кредитному договору. Возможные значения: 1 - по контракту, 0 - по кредитному
     *  договору
     *
     * @param boolean $contractCredit
     * @return static
     */
    public function setContractCredit($contractCredit)
    {
        $this->contractCredit = $contractCredit;
        return $this;
    }

    /**
     * Gets as part2
     *
     * Раздел II. Сведения о платежах
     *
     * @return boolean
     */
    public function getPart2()
    {
        return $this->part2;
    }

    /**
     * Sets a new part2
     *
     * Раздел II. Сведения о платежах
     *
     * @param boolean $part2
     * @return static
     */
    public function setPart2($part2)
    {
        $this->part2 = $part2;
        return $this;
    }

    /**
     * Gets as part3Contract
     *
     * Раздел III. Сведения о подтвержающих документах
     *
     * @return boolean
     */
    public function getPart3Contract()
    {
        return $this->part3Contract;
    }

    /**
     * Sets a new part3Contract
     *
     * Раздел III. Сведения о подтвержающих документах
     *
     * @param boolean $part3Contract
     * @return static
     */
    public function setPart3Contract($part3Contract)
    {
        $this->part3Contract = $part3Contract;
        return $this;
    }

    /**
     * Gets as part5Contract
     *
     * Раздел V. Итоговые данные расчетов по контракту
     *
     * @return boolean
     */
    public function getPart5Contract()
    {
        return $this->part5Contract;
    }

    /**
     * Sets a new part5Contract
     *
     * Раздел V. Итоговые данные расчетов по контракту
     *
     * @param boolean $part5Contract
     * @return static
     */
    public function setPart5Contract($part5Contract)
    {
        $this->part5Contract = $part5Contract;
        return $this;
    }

    /**
     * Gets as part3Credit
     *
     * Раздел III. Сведения об исполнении обязательств по основному долгу по иным основаниям, отличным от проведения
     *  расчетов в денежной форме
     *
     * @return boolean
     */
    public function getPart3Credit()
    {
        return $this->part3Credit;
    }

    /**
     * Sets a new part3Credit
     *
     * Раздел III. Сведения об исполнении обязательств по основному долгу по иным основаниям, отличным от проведения
     *  расчетов в денежной форме
     *
     * @param boolean $part3Credit
     * @return static
     */
    public function setPart3Credit($part3Credit)
    {
        $this->part3Credit = $part3Credit;
        return $this;
    }

    /**
     * Gets as part4Credit
     *
     * Раздел IV. Расчет задолженности по основному долгу
     *
     * @return boolean
     */
    public function getPart4Credit()
    {
        return $this->part4Credit;
    }

    /**
     * Sets a new part4Credit
     *
     * Раздел IV. Расчет задолженности по основному долгу
     *
     * @param boolean $part4Credit
     * @return static
     */
    public function setPart4Credit($part4Credit)
    {
        $this->part4Credit = $part4Credit;
        return $this;
    }


}

