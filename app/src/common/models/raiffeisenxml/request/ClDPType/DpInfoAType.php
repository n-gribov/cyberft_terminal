<?php

namespace common\models\raiffeisenxml\request\ClDPType;

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
     * @property \common\models\raiffeisenxml\request\ClDPType\DpInfoAType\ReasonsAType $reasons
     */
    private $reasons = null;

    /**
     * @property \common\models\raiffeisenxml\request\ClDPType\DpInfoAType\OperAType[] $oper
     */
    private $oper = [
        
    ];

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
     * @return \common\models\raiffeisenxml\request\ClDPType\DpInfoAType\ReasonsAType
     */
    public function getReasons()
    {
        return $this->reasons;
    }

    /**
     * Sets a new reasons
     *
     * @param \common\models\raiffeisenxml\request\ClDPType\DpInfoAType\ReasonsAType $reasons
     * @return static
     */
    public function setReasons(\common\models\raiffeisenxml\request\ClDPType\DpInfoAType\ReasonsAType $reasons)
    {
        $this->reasons = $reasons;
        return $this;
    }

    /**
     * Adds as oper
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ClDPType\DpInfoAType\OperAType $oper
     */
    public function addToOper(\common\models\raiffeisenxml\request\ClDPType\DpInfoAType\OperAType $oper)
    {
        $this->oper[] = $oper;
        return $this;
    }

    /**
     * isset oper
     *
     * @param int|string $index
     * @return bool
     */
    public function issetOper($index)
    {
        return isset($this->oper[$index]);
    }

    /**
     * unset oper
     *
     * @param int|string $index
     * @return void
     */
    public function unsetOper($index)
    {
        unset($this->oper[$index]);
    }

    /**
     * Gets as oper
     *
     * @return \common\models\raiffeisenxml\request\ClDPType\DpInfoAType\OperAType[]
     */
    public function getOper()
    {
        return $this->oper;
    }

    /**
     * Sets a new oper
     *
     * @param \common\models\raiffeisenxml\request\ClDPType\DpInfoAType\OperAType[] $oper
     * @return static
     */
    public function setOper(array $oper)
    {
        $this->oper = $oper;
        return $this;
    }


}

