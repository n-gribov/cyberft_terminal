<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ResInfoICSType
 *
 * Сведения о резиденте
 * XSD Type: ResInfoICS
 */
class ResInfoICSType
{

    /**
     * Филиал организации-резидента
     *
     * @property string $branchName
     */
    private $branchName = null;

    /**
     * КПП организации-резидента
     *
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * Gets as branchName
     *
     * Филиал организации-резидента
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
     * Филиал организации-резидента
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
     * Gets as kpp
     *
     * КПП организации-резидента
     *
     * @return string
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Sets a new kpp
     *
     * КПП организации-резидента
     *
     * @param string $kpp
     * @return static
     */
    public function setKpp($kpp)
    {
        $this->kpp = $kpp;
        return $this;
    }


}

