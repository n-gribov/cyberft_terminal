<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing MigrationCardInfoType
 *
 * Данные миграционной карты
 * XSD Type: MigrationCardInfo
 */
class MigrationCardInfoType
{

    /**
     * 5.1 Миграционная карта не требуется
     *
     * @property boolean $migrationCardNotRequired
     */
    private $migrationCardNotRequired = null;

    /**
     * 5.2 Номер
     *
     * @property string $migrationCardNumber
     */
    private $migrationCardNumber = null;

    /**
     * 5.3 Дата начала срока пребывания
     *
     * @property \DateTime $durationOfStayBeginDate
     */
    private $durationOfStayBeginDate = null;

    /**
     * 5.4 Дата окончания срока пребывания
     *
     * @property \DateTime $durationOfStayEndDate
     */
    private $durationOfStayEndDate = null;

    /**
     * Gets as migrationCardNotRequired
     *
     * 5.1 Миграционная карта не требуется
     *
     * @return boolean
     */
    public function getMigrationCardNotRequired()
    {
        return $this->migrationCardNotRequired;
    }

    /**
     * Sets a new migrationCardNotRequired
     *
     * 5.1 Миграционная карта не требуется
     *
     * @param boolean $migrationCardNotRequired
     * @return static
     */
    public function setMigrationCardNotRequired($migrationCardNotRequired)
    {
        $this->migrationCardNotRequired = $migrationCardNotRequired;
        return $this;
    }

    /**
     * Gets as migrationCardNumber
     *
     * 5.2 Номер
     *
     * @return string
     */
    public function getMigrationCardNumber()
    {
        return $this->migrationCardNumber;
    }

    /**
     * Sets a new migrationCardNumber
     *
     * 5.2 Номер
     *
     * @param string $migrationCardNumber
     * @return static
     */
    public function setMigrationCardNumber($migrationCardNumber)
    {
        $this->migrationCardNumber = $migrationCardNumber;
        return $this;
    }

    /**
     * Gets as durationOfStayBeginDate
     *
     * 5.3 Дата начала срока пребывания
     *
     * @return \DateTime
     */
    public function getDurationOfStayBeginDate()
    {
        return $this->durationOfStayBeginDate;
    }

    /**
     * Sets a new durationOfStayBeginDate
     *
     * 5.3 Дата начала срока пребывания
     *
     * @param \DateTime $durationOfStayBeginDate
     * @return static
     */
    public function setDurationOfStayBeginDate(\DateTime $durationOfStayBeginDate)
    {
        $this->durationOfStayBeginDate = $durationOfStayBeginDate;
        return $this;
    }

    /**
     * Gets as durationOfStayEndDate
     *
     * 5.4 Дата окончания срока пребывания
     *
     * @return \DateTime
     */
    public function getDurationOfStayEndDate()
    {
        return $this->durationOfStayEndDate;
    }

    /**
     * Sets a new durationOfStayEndDate
     *
     * 5.4 Дата окончания срока пребывания
     *
     * @param \DateTime $durationOfStayEndDate
     * @return static
     */
    public function setDurationOfStayEndDate(\DateTime $durationOfStayEndDate)
    {
        $this->durationOfStayEndDate = $durationOfStayEndDate;
        return $this;
    }


}

