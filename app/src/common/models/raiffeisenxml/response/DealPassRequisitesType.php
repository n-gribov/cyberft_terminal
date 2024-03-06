<?php

namespace common\models\raiffeisenxml\response;

/**
 * Class representing DealPassRequisitesType
 *
 * Реквизиты паспорта сделки в дополнительной информации по нему в соответствующем квитке
 * XSD Type: DealPassRequisites
 */
class DealPassRequisitesType
{

    /**
     * Номер ПС (полный)
     *
     * @property string $numFull
     */
    private $numFull = null;

    /**
     * Номер ПС (по частям)
     *
     * @property \common\models\raiffeisenxml\response\DealPassRequisitesType\NumPartsAType $numParts
     */
    private $numParts = null;

    /**
     * Дата ПС
     *
     * @property \DateTime $date
     */
    private $date = null;

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

    /**
     * Gets as numParts
     *
     * Номер ПС (по частям)
     *
     * @return \common\models\raiffeisenxml\response\DealPassRequisitesType\NumPartsAType
     */
    public function getNumParts()
    {
        return $this->numParts;
    }

    /**
     * Sets a new numParts
     *
     * Номер ПС (по частям)
     *
     * @param \common\models\raiffeisenxml\response\DealPassRequisitesType\NumPartsAType $numParts
     * @return static
     */
    public function setNumParts(\common\models\raiffeisenxml\response\DealPassRequisitesType\NumPartsAType $numParts)
    {
        $this->numParts = $numParts;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата ПС
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
     * Дата ПС
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }


}

