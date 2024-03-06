<?php

namespace common\models\sbbolxml\request\DpInfosType;

/**
 * Class representing DpInfoAType
 */
class DpInfoAType
{

    /**
     * Номер ПС
     *
     * @property string $dpNum
     */
    private $dpNum = null;

    /**
     * Дата ПС
     *
     * @property \DateTime $dpDate
     */
    private $dpDate = null;

    /**
     * @property \common\models\sbbolxml\request\DpInfosType\DpInfoAType\ReasonsAType $reasons
     */
    private $reasons = null;

    /**
     * Приложения
     *
     * @property string $attach
     */
    private $attach = null;

    /**
     * Приложения на x листах
     *
     * @property string $sheetsNum
     */
    private $sheetsNum = null;

    /**
     * Gets as dpNum
     *
     * Номер ПС
     *
     * @return string
     */
    public function getDpNum()
    {
        return $this->dpNum;
    }

    /**
     * Sets a new dpNum
     *
     * Номер ПС
     *
     * @param string $dpNum
     * @return static
     */
    public function setDpNum($dpNum)
    {
        $this->dpNum = $dpNum;
        return $this;
    }

    /**
     * Gets as dpDate
     *
     * Дата ПС
     *
     * @return \DateTime
     */
    public function getDpDate()
    {
        return $this->dpDate;
    }

    /**
     * Sets a new dpDate
     *
     * Дата ПС
     *
     * @param \DateTime $dpDate
     * @return static
     */
    public function setDpDate(\DateTime $dpDate)
    {
        $this->dpDate = $dpDate;
        return $this;
    }

    /**
     * Gets as reasons
     *
     * @return \common\models\sbbolxml\request\DpInfosType\DpInfoAType\ReasonsAType
     */
    public function getReasons()
    {
        return $this->reasons;
    }

    /**
     * Sets a new reasons
     *
     * @param \common\models\sbbolxml\request\DpInfosType\DpInfoAType\ReasonsAType $reasons
     * @return static
     */
    public function setReasons(\common\models\sbbolxml\request\DpInfosType\DpInfoAType\ReasonsAType $reasons)
    {
        $this->reasons = $reasons;
        return $this;
    }

    /**
     * Gets as attach
     *
     * Приложения
     *
     * @return string
     */
    public function getAttach()
    {
        return $this->attach;
    }

    /**
     * Sets a new attach
     *
     * Приложения
     *
     * @param string $attach
     * @return static
     */
    public function setAttach($attach)
    {
        $this->attach = $attach;
        return $this;
    }

    /**
     * Gets as sheetsNum
     *
     * Приложения на x листах
     *
     * @return string
     */
    public function getSheetsNum()
    {
        return $this->sheetsNum;
    }

    /**
     * Sets a new sheetsNum
     *
     * Приложения на x листах
     *
     * @param string $sheetsNum
     * @return static
     */
    public function setSheetsNum($sheetsNum)
    {
        $this->sheetsNum = $sheetsNum;
        return $this;
    }


}

