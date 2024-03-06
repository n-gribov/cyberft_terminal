<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType\RerInfoAType;

/**
 * Class representing RerAType
 */
class RerAType
{

    /**
     * Номер переоформления
     *
     * @property string $rerNumber
     */
    private $rerNumber = null;

    /**
     * Дата переоформления
     *
     * @property \DateTime $rerDate
     */
    private $rerDate = null;

    /**
     * Номер документа осеования
     *
     * @property string $rerRefNum
     */
    private $rerRefNum = null;

    /**
     * Дата документа основания
     *
     * @property \DateTime $rerRefDate
     */
    private $rerRefDate = null;

    /**
     * Gets as rerNumber
     *
     * Номер переоформления
     *
     * @return string
     */
    public function getRerNumber()
    {
        return $this->rerNumber;
    }

    /**
     * Sets a new rerNumber
     *
     * Номер переоформления
     *
     * @param string $rerNumber
     * @return static
     */
    public function setRerNumber($rerNumber)
    {
        $this->rerNumber = $rerNumber;
        return $this;
    }

    /**
     * Gets as rerDate
     *
     * Дата переоформления
     *
     * @return \DateTime
     */
    public function getRerDate()
    {
        return $this->rerDate;
    }

    /**
     * Sets a new rerDate
     *
     * Дата переоформления
     *
     * @param \DateTime $rerDate
     * @return static
     */
    public function setRerDate(\DateTime $rerDate)
    {
        $this->rerDate = $rerDate;
        return $this;
    }

    /**
     * Gets as rerRefNum
     *
     * Номер документа осеования
     *
     * @return string
     */
    public function getRerRefNum()
    {
        return $this->rerRefNum;
    }

    /**
     * Sets a new rerRefNum
     *
     * Номер документа осеования
     *
     * @param string $rerRefNum
     * @return static
     */
    public function setRerRefNum($rerRefNum)
    {
        $this->rerRefNum = $rerRefNum;
        return $this;
    }

    /**
     * Gets as rerRefDate
     *
     * Дата документа основания
     *
     * @return \DateTime
     */
    public function getRerRefDate()
    {
        return $this->rerRefDate;
    }

    /**
     * Sets a new rerRefDate
     *
     * Дата документа основания
     *
     * @param \DateTime $rerRefDate
     * @return static
     */
    public function setRerRefDate(\DateTime $rerRefDate)
    {
        $this->rerRefDate = $rerRefDate;
        return $this;
    }


}

