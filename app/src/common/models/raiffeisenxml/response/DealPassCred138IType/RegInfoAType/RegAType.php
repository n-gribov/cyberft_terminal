<?php

namespace common\models\raiffeisenxml\response\DealPassCred138IType\RegInfoAType;

/**
 * Class representing RegAType
 */
class RegAType
{

    /**
     * Код основания закрытия ПС в соответствии с Инструкцией
     *  138-И
     *
     * @property string $refClose
     */
    private $refClose = null;

    /**
     * Регистрационный номер банка ПС
     *
     * @property string $bankRegNum
     */
    private $bankRegNum = null;

    /**
     * Дата принятия ПС
     *
     * @property \DateTime $dateIn
     */
    private $dateIn = null;

    /**
     * Дата закрытия ПС
     *
     * @property \DateTime $dateClose
     */
    private $dateClose = null;

    /**
     * Порядковый номер табличной записи
     *
     * @property string $reqNumber
     */
    private $reqNumber = null;

    /**
     * Gets as refClose
     *
     * Код основания закрытия ПС в соответствии с Инструкцией
     *  138-И
     *
     * @return string
     */
    public function getRefClose()
    {
        return $this->refClose;
    }

    /**
     * Sets a new refClose
     *
     * Код основания закрытия ПС в соответствии с Инструкцией
     *  138-И
     *
     * @param string $refClose
     * @return static
     */
    public function setRefClose($refClose)
    {
        $this->refClose = $refClose;
        return $this;
    }

    /**
     * Gets as bankRegNum
     *
     * Регистрационный номер банка ПС
     *
     * @return string
     */
    public function getBankRegNum()
    {
        return $this->bankRegNum;
    }

    /**
     * Sets a new bankRegNum
     *
     * Регистрационный номер банка ПС
     *
     * @param string $bankRegNum
     * @return static
     */
    public function setBankRegNum($bankRegNum)
    {
        $this->bankRegNum = $bankRegNum;
        return $this;
    }

    /**
     * Gets as dateIn
     *
     * Дата принятия ПС
     *
     * @return \DateTime
     */
    public function getDateIn()
    {
        return $this->dateIn;
    }

    /**
     * Sets a new dateIn
     *
     * Дата принятия ПС
     *
     * @param \DateTime $dateIn
     * @return static
     */
    public function setDateIn(\DateTime $dateIn)
    {
        $this->dateIn = $dateIn;
        return $this;
    }

    /**
     * Gets as dateClose
     *
     * Дата закрытия ПС
     *
     * @return \DateTime
     */
    public function getDateClose()
    {
        return $this->dateClose;
    }

    /**
     * Sets a new dateClose
     *
     * Дата закрытия ПС
     *
     * @param \DateTime $dateClose
     * @return static
     */
    public function setDateClose(\DateTime $dateClose)
    {
        $this->dateClose = $dateClose;
        return $this;
    }

    /**
     * Gets as reqNumber
     *
     * Порядковый номер табличной записи
     *
     * @return string
     */
    public function getReqNumber()
    {
        return $this->reqNumber;
    }

    /**
     * Sets a new reqNumber
     *
     * Порядковый номер табличной записи
     *
     * @param string $reqNumber
     * @return static
     */
    public function setReqNumber($reqNumber)
    {
        $this->reqNumber = $reqNumber;
        return $this;
    }


}

