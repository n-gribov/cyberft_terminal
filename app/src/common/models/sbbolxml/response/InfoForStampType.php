<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing InfoForStampType
 *
 *
 * XSD Type: InfoForStampType
 */
class InfoForStampType
{

    /**
     * ОАО "Сбербанк России"
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Московский банк ОАО "Сбербанк России"
     *
     * @property string $branchName
     */
    private $branchName = null;

    /**
     * Специализированный дополнительный офис №1766
     *
     * @property string $subBranchName
     */
    private $subBranchName = null;

    /**
     * Филиал № 1766
     *
     * @property string $subBranchNum
     */
    private $subBranchNum = null;

    /**
     * Gets as bankName
     *
     * ОАО "Сбербанк России"
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * ОАО "Сбербанк России"
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }

    /**
     * Gets as branchName
     *
     * Московский банк ОАО "Сбербанк России"
     *
     * @return string
     */
    public function getBranchName()
    {
        return $this->branchName;
    }

    /**
     * Sets a new branchName
     *
     * Московский банк ОАО "Сбербанк России"
     *
     * @param string $branchName
     * @return static
     */
    public function setBranchName($branchName)
    {
        $this->branchName = $branchName;
        return $this;
    }

    /**
     * Gets as subBranchName
     *
     * Специализированный дополнительный офис №1766
     *
     * @return string
     */
    public function getSubBranchName()
    {
        return $this->subBranchName;
    }

    /**
     * Sets a new subBranchName
     *
     * Специализированный дополнительный офис №1766
     *
     * @param string $subBranchName
     * @return static
     */
    public function setSubBranchName($subBranchName)
    {
        $this->subBranchName = $subBranchName;
        return $this;
    }

    /**
     * Gets as subBranchNum
     *
     * Филиал № 1766
     *
     * @return string
     */
    public function getSubBranchNum()
    {
        return $this->subBranchNum;
    }

    /**
     * Sets a new subBranchNum
     *
     * Филиал № 1766
     *
     * @param string $subBranchNum
     * @return static
     */
    public function setSubBranchNum($subBranchNum)
    {
        $this->subBranchNum = $subBranchNum;
        return $this;
    }


}

