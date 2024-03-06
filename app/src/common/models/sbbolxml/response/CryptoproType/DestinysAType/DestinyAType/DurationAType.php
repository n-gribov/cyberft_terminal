<?php

namespace common\models\sbbolxml\response\CryptoproType\DestinysAType\DestinyAType;

/**
 * Class representing DurationAType
 */
class DurationAType
{

    /**
     * Дата начала срока действия полномочий
     *  криптопрофиля
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Дата окончания срока действия полномочий
     *  криптопрофиля
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Признак того, что срок действия криптопрофиля
     *  неограниченный:
     *  1 - признак установлен
     *  0 - признак не установлен
     *
     * @property boolean $unlimited
     */
    private $unlimited = null;

    /**
     * Gets as beginDate
     *
     * Дата начала срока действия полномочий
     *  криптопрофиля
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Sets a new beginDate
     *
     * Дата начала срока действия полномочий
     *  криптопрофиля
     *
     * @param \DateTime $beginDate
     * @return static
     */
    public function setBeginDate(\DateTime $beginDate)
    {
        $this->beginDate = $beginDate;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата окончания срока действия полномочий
     *  криптопрофиля
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Sets a new endDate
     *
     * Дата окончания срока действия полномочий
     *  криптопрофиля
     *
     * @param \DateTime $endDate
     * @return static
     */
    public function setEndDate(\DateTime $endDate)
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * Gets as unlimited
     *
     * Признак того, что срок действия криптопрофиля
     *  неограниченный:
     *  1 - признак установлен
     *  0 - признак не установлен
     *
     * @return boolean
     */
    public function getUnlimited()
    {
        return $this->unlimited;
    }

    /**
     * Sets a new unlimited
     *
     * Признак того, что срок действия криптопрофиля
     *  неограниченный:
     *  1 - признак установлен
     *  0 - признак не установлен
     *
     * @param boolean $unlimited
     * @return static
     */
    public function setUnlimited($unlimited)
    {
        $this->unlimited = $unlimited;
        return $this;
    }


}

