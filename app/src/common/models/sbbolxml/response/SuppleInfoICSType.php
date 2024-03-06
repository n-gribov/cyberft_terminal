<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing SuppleInfoICSType
 *
 * Справочная информация
 * XSD Type: SuppleInfoICS
 */
class SuppleInfoICSType
{

    /**
     * Признак присвоения УН в соответствии с п. 8.5 Инструкции №159-И
     *
     * @property boolean $hasUniqueNumber
     */
    private $hasUniqueNumber = null;

    /**
     * Периодичность платежей
     *
     * @property string $periodicity
     */
    private $periodicity = null;

    /**
     * Gets as hasUniqueNumber
     *
     * Признак присвоения УН в соответствии с п. 8.5 Инструкции №159-И
     *
     * @return boolean
     */
    public function getHasUniqueNumber()
    {
        return $this->hasUniqueNumber;
    }

    /**
     * Sets a new hasUniqueNumber
     *
     * Признак присвоения УН в соответствии с п. 8.5 Инструкции №159-И
     *
     * @param boolean $hasUniqueNumber
     * @return static
     */
    public function setHasUniqueNumber($hasUniqueNumber)
    {
        $this->hasUniqueNumber = $hasUniqueNumber;
        return $this;
    }

    /**
     * Gets as periodicity
     *
     * Периодичность платежей
     *
     * @return string
     */
    public function getPeriodicity()
    {
        return $this->periodicity;
    }

    /**
     * Sets a new periodicity
     *
     * Периодичность платежей
     *
     * @param string $periodicity
     * @return static
     */
    public function setPeriodicity($periodicity)
    {
        $this->periodicity = $periodicity;
        return $this;
    }


}

