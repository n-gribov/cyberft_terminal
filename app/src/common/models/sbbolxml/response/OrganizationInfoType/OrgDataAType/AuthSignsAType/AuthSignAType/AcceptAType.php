<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType;

/**
 * Class representing AcceptAType
 */
class AcceptAType
{

    /**
     * Признак "Полномочия акцепта"
     *
     * @property boolean $credentials
     */
    private $credentials = null;

    /**
     * Срок полномочий с
     *
     * @property \DateTime $durationFrom
     */
    private $durationFrom = null;

    /**
     * Срок полномочий по
     *
     * @property \DateTime $durationTill
     */
    private $durationTill = null;

    /**
     * Gets as credentials
     *
     * Признак "Полномочия акцепта"
     *
     * @return boolean
     */
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Sets a new credentials
     *
     * Признак "Полномочия акцепта"
     *
     * @param boolean $credentials
     * @return static
     */
    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;
        return $this;
    }

    /**
     * Gets as durationFrom
     *
     * Срок полномочий с
     *
     * @return \DateTime
     */
    public function getDurationFrom()
    {
        return $this->durationFrom;
    }

    /**
     * Sets a new durationFrom
     *
     * Срок полномочий с
     *
     * @param \DateTime $durationFrom
     * @return static
     */
    public function setDurationFrom(\DateTime $durationFrom)
    {
        $this->durationFrom = $durationFrom;
        return $this;
    }

    /**
     * Gets as durationTill
     *
     * Срок полномочий по
     *
     * @return \DateTime
     */
    public function getDurationTill()
    {
        return $this->durationTill;
    }

    /**
     * Sets a new durationTill
     *
     * Срок полномочий по
     *
     * @param \DateTime $durationTill
     * @return static
     */
    public function setDurationTill(\DateTime $durationTill)
    {
        $this->durationTill = $durationTill;
        return $this;
    }


}

