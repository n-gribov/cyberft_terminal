<?php

namespace common\models\raiffeisenxml\request\CreditRaifType\MainAType;

/**
 * Class representing AgreementAType
 */
class AgreementAType
{

    /**
     * Без номера. 0 - с номером, 1 - без номера.
     *
     * @property bool $checkNum
     */
    private $checkNum = null;

    /**
     * №
     *
     * @property string $num
     */
    private $num = null;

    /**
     * От.
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Действуя в соответствии с.
     *
     * @property string $agreementType
     */
    private $agreementType = null;

    /**
     * Gets as checkNum
     *
     * Без номера. 0 - с номером, 1 - без номера.
     *
     * @return bool
     */
    public function getCheckNum()
    {
        return $this->checkNum;
    }

    /**
     * Sets a new checkNum
     *
     * Без номера. 0 - с номером, 1 - без номера.
     *
     * @param bool $checkNum
     * @return static
     */
    public function setCheckNum($checkNum)
    {
        $this->checkNum = $checkNum;
        return $this;
    }

    /**
     * Gets as num
     *
     * №
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
     * №
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
     * Gets as date
     *
     * От.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * От.
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as agreementType
     *
     * Действуя в соответствии с.
     *
     * @return string
     */
    public function getAgreementType()
    {
        return $this->agreementType;
    }

    /**
     * Sets a new agreementType
     *
     * Действуя в соответствии с.
     *
     * @param string $agreementType
     * @return static
     */
    public function setAgreementType($agreementType)
    {
        $this->agreementType = $agreementType;
        return $this;
    }


}

