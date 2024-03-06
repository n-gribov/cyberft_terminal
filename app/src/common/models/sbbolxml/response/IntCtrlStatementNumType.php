<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing IntCtrlStatementNumType
 *
 * Основные данные контракта, кредитного договора
 * XSD Type: IntCtrlStatementNum
 */
class IntCtrlStatementNumType
{

    /**
     * Дата паспорта сделки
     *
     * @property \DateTime $bankDate
     */
    private $bankDate = null;

    /**
     * Номер ПС (полный)
     *
     * @property string $numFull
     */
    private $numFull = null;

    /**
     * Gets as bankDate
     *
     * Дата паспорта сделки
     *
     * @return \DateTime
     */
    public function getBankDate()
    {
        return $this->bankDate;
    }

    /**
     * Sets a new bankDate
     *
     * Дата паспорта сделки
     *
     * @param \DateTime $bankDate
     * @return static
     */
    public function setBankDate(\DateTime $bankDate)
    {
        $this->bankDate = $bankDate;
        return $this;
    }

    /**
     * Gets as numFull
     *
     * Номер ПС (полный)
     *
     * @return string
     */
    public function getNumFull()
    {
        return $this->numFull;
    }

    /**
     * Sets a new numFull
     *
     * Номер ПС (полный)
     *
     * @param string $numFull
     * @return static
     */
    public function setNumFull($numFull)
    {
        $this->numFull = $numFull;
        return $this;
    }


}

