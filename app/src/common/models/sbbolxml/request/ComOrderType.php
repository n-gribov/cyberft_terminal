<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ComOrderType
 *
 *
 * XSD Type: ComOrder
 */
class ComOrderType
{

    /**
     * Номер платёжного поручения
     *
     * @property string $numPayDoc
     */
    private $numPayDoc = null;

    /**
     * Дата платёжного поручения по местному времени
     *
     * @property \DateTime $datePayDoc
     */
    private $datePayDoc = null;

    /**
     * Назначение платежа
     *
     * @property string $purposePayDoc
     */
    private $purposePayDoc = null;

    /**
     * Gets as numPayDoc
     *
     * Номер платёжного поручения
     *
     * @return string
     */
    public function getNumPayDoc()
    {
        return $this->numPayDoc;
    }

    /**
     * Sets a new numPayDoc
     *
     * Номер платёжного поручения
     *
     * @param string $numPayDoc
     * @return static
     */
    public function setNumPayDoc($numPayDoc)
    {
        $this->numPayDoc = $numPayDoc;
        return $this;
    }

    /**
     * Gets as datePayDoc
     *
     * Дата платёжного поручения по местному времени
     *
     * @return \DateTime
     */
    public function getDatePayDoc()
    {
        return $this->datePayDoc;
    }

    /**
     * Sets a new datePayDoc
     *
     * Дата платёжного поручения по местному времени
     *
     * @param \DateTime $datePayDoc
     * @return static
     */
    public function setDatePayDoc(\DateTime $datePayDoc)
    {
        $this->datePayDoc = $datePayDoc;
        return $this;
    }

    /**
     * Gets as purposePayDoc
     *
     * Назначение платежа
     *
     * @return string
     */
    public function getPurposePayDoc()
    {
        return $this->purposePayDoc;
    }

    /**
     * Sets a new purposePayDoc
     *
     * Назначение платежа
     *
     * @param string $purposePayDoc
     * @return static
     */
    public function setPurposePayDoc($purposePayDoc)
    {
        $this->purposePayDoc = $purposePayDoc;
        return $this;
    }


}

