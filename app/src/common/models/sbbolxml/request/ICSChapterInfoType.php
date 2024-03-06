<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ICSChapterInfoType
 *
 * Сведения о переоформляемых разделах/подразделах
 * XSD Type: ICSChapterInfo
 */
class ICSChapterInfoType
{

    /**
     * Сведения о резиденте
     *
     * @property \common\models\sbbolxml\request\ResInfoICSType $resInfo
     */
    private $resInfo = null;

    /**
     * Реквизиты иностранного контрагента
     *
     * @property \common\models\sbbolxml\request\BeneficiarInfoISCType[] $beneficiarInfo
     */
    private $beneficiarInfo = null;

    /**
     * Сведения о контракте, кредитном договоре
     *
     * @property \common\models\sbbolxml\request\ConCredInfoType $conCredInfo
     */
    private $conCredInfo = null;

    /**
     * Особые условия кредитного договора
     *
     * @property \common\models\sbbolxml\request\SpecConditionsICSType $specConditions
     */
    private $specConditions = null;

    /**
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @property \common\models\sbbolxml\request\TrancheOperType[] $trancheInfo
     */
    private $trancheInfo = null;

    /**
     * Номер контракта/кредитного договора, ранее оформленного в другом
     *  уполномоченном банке
     *
     * @property string $numOtherBank
     */
    private $numOtherBank = null;

    /**
     * Специальные сведения о кредитном договоре
     *
     * @property \common\models\sbbolxml\request\CredSpecDataICSType $specData
     */
    private $specData = null;

    /**
     * Справочная информация о кредитном договоре
     *
     * @property \common\models\sbbolxml\request\CredHelpInfoICSType $helpInfo
     */
    private $helpInfo = null;

    /**
     * Gets as resInfo
     *
     * Сведения о резиденте
     *
     * @return \common\models\sbbolxml\request\ResInfoICSType
     */
    public function getResInfo()
    {
        return $this->resInfo;
    }

    /**
     * Sets a new resInfo
     *
     * Сведения о резиденте
     *
     * @param \common\models\sbbolxml\request\ResInfoICSType $resInfo
     * @return static
     */
    public function setResInfo(\common\models\sbbolxml\request\ResInfoICSType $resInfo)
    {
        $this->resInfo = $resInfo;
        return $this;
    }

    /**
     * Adds as beneficiar
     *
     * Реквизиты иностранного контрагента
     *
     * @return static
     * @param \common\models\sbbolxml\request\BeneficiarInfoISCType $beneficiar
     */
    public function addToBeneficiarInfo(\common\models\sbbolxml\request\BeneficiarInfoISCType $beneficiar)
    {
        $this->beneficiarInfo[] = $beneficiar;
        return $this;
    }

    /**
     * isset beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBeneficiarInfo($index)
    {
        return isset($this->beneficiarInfo[$index]);
    }

    /**
     * unset beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBeneficiarInfo($index)
    {
        unset($this->beneficiarInfo[$index]);
    }

    /**
     * Gets as beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @return \common\models\sbbolxml\request\BeneficiarInfoISCType[]
     */
    public function getBeneficiarInfo()
    {
        return $this->beneficiarInfo;
    }

    /**
     * Sets a new beneficiarInfo
     *
     * Реквизиты иностранного контрагента
     *
     * @param \common\models\sbbolxml\request\BeneficiarInfoISCType[] $beneficiarInfo
     * @return static
     */
    public function setBeneficiarInfo(array $beneficiarInfo)
    {
        $this->beneficiarInfo = $beneficiarInfo;
        return $this;
    }

    /**
     * Gets as conCredInfo
     *
     * Сведения о контракте, кредитном договоре
     *
     * @return \common\models\sbbolxml\request\ConCredInfoType
     */
    public function getConCredInfo()
    {
        return $this->conCredInfo;
    }

    /**
     * Sets a new conCredInfo
     *
     * Сведения о контракте, кредитном договоре
     *
     * @param \common\models\sbbolxml\request\ConCredInfoType $conCredInfo
     * @return static
     */
    public function setConCredInfo(\common\models\sbbolxml\request\ConCredInfoType $conCredInfo)
    {
        $this->conCredInfo = $conCredInfo;
        return $this;
    }

    /**
     * Gets as specConditions
     *
     * Особые условия кредитного договора
     *
     * @return \common\models\sbbolxml\request\SpecConditionsICSType
     */
    public function getSpecConditions()
    {
        return $this->specConditions;
    }

    /**
     * Sets a new specConditions
     *
     * Особые условия кредитного договора
     *
     * @param \common\models\sbbolxml\request\SpecConditionsICSType $specConditions
     * @return static
     */
    public function setSpecConditions(\common\models\sbbolxml\request\SpecConditionsICSType $specConditions)
    {
        $this->specConditions = $specConditions;
        return $this;
    }

    /**
     * Adds as oper
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @return static
     * @param \common\models\sbbolxml\request\TrancheOperType $oper
     */
    public function addToTrancheInfo(\common\models\sbbolxml\request\TrancheOperType $oper)
    {
        $this->trancheInfo[] = $oper;
        return $this;
    }

    /**
     * isset trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetTrancheInfo($index)
    {
        return isset($this->trancheInfo[$index]);
    }

    /**
     * unset trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @param scalar $index
     * @return void
     */
    public function unsetTrancheInfo($index)
    {
        unset($this->trancheInfo[$index]);
    }

    /**
     * Gets as trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @return \common\models\sbbolxml\request\TrancheOperType[]
     */
    public function getTrancheInfo()
    {
        return $this->trancheInfo;
    }

    /**
     * Sets a new trancheInfo
     *
     * Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @param \common\models\sbbolxml\request\TrancheOperType[] $trancheInfo
     * @return static
     */
    public function setTrancheInfo(array $trancheInfo)
    {
        $this->trancheInfo = $trancheInfo;
        return $this;
    }

    /**
     * Gets as numOtherBank
     *
     * Номер контракта/кредитного договора, ранее оформленного в другом
     *  уполномоченном банке
     *
     * @return string
     */
    public function getNumOtherBank()
    {
        return $this->numOtherBank;
    }

    /**
     * Sets a new numOtherBank
     *
     * Номер контракта/кредитного договора, ранее оформленного в другом
     *  уполномоченном банке
     *
     * @param string $numOtherBank
     * @return static
     */
    public function setNumOtherBank($numOtherBank)
    {
        $this->numOtherBank = $numOtherBank;
        return $this;
    }

    /**
     * Gets as specData
     *
     * Специальные сведения о кредитном договоре
     *
     * @return \common\models\sbbolxml\request\CredSpecDataICSType
     */
    public function getSpecData()
    {
        return $this->specData;
    }

    /**
     * Sets a new specData
     *
     * Специальные сведения о кредитном договоре
     *
     * @param \common\models\sbbolxml\request\CredSpecDataICSType $specData
     * @return static
     */
    public function setSpecData(\common\models\sbbolxml\request\CredSpecDataICSType $specData)
    {
        $this->specData = $specData;
        return $this;
    }

    /**
     * Gets as helpInfo
     *
     * Справочная информация о кредитном договоре
     *
     * @return \common\models\sbbolxml\request\CredHelpInfoICSType
     */
    public function getHelpInfo()
    {
        return $this->helpInfo;
    }

    /**
     * Sets a new helpInfo
     *
     * Справочная информация о кредитном договоре
     *
     * @param \common\models\sbbolxml\request\CredHelpInfoICSType $helpInfo
     * @return static
     */
    public function setHelpInfo(\common\models\sbbolxml\request\CredHelpInfoICSType $helpInfo)
    {
        $this->helpInfo = $helpInfo;
        return $this;
    }


}

