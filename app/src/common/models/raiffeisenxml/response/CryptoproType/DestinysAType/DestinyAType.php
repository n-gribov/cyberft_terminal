<?php

namespace common\models\raiffeisenxml\response\CryptoproType\DestinysAType;

/**
 * Class representing DestinyAType
 */
class DestinyAType
{

    /**
     * Уникальный идентификатор организации в ДБО
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * ИНН организации
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Тип подписи
     *
     * @property string $signType
     */
    private $signType = null;

    /**
     * Срок полномочий
     *
     * @property \common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType\DurationAType $duration
     */
    private $duration = null;

    /**
     * Gets as orgId
     *
     * Уникальный идентификатор организации в ДБО
     *
     * @return string
     */
    public function getOrgId()
    {
        return $this->orgId;
    }

    /**
     * Sets a new orgId
     *
     * Уникальный идентификатор организации в ДБО
     *
     * @param string $orgId
     * @return static
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;
        return $this;
    }

    /**
     * Gets as inn
     *
     * ИНН организации
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Sets a new inn
     *
     * ИНН организации
     *
     * @param string $inn
     * @return static
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * Gets as signType
     *
     * Тип подписи
     *
     * @return string
     */
    public function getSignType()
    {
        return $this->signType;
    }

    /**
     * Sets a new signType
     *
     * Тип подписи
     *
     * @param string $signType
     * @return static
     */
    public function setSignType($signType)
    {
        $this->signType = $signType;
        return $this;
    }

    /**
     * Gets as duration
     *
     * Срок полномочий
     *
     * @return \common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType\DurationAType
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * Sets a new duration
     *
     * Срок полномочий
     *
     * @param \common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType\DurationAType $duration
     * @return static
     */
    public function setDuration(\common\models\raiffeisenxml\response\CryptoproType\DestinysAType\DestinyAType\DurationAType $duration)
    {
        $this->duration = $duration;
        return $this;
    }


}

