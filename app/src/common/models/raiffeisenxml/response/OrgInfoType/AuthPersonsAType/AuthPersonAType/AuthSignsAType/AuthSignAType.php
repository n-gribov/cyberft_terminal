<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType;

/**
 * Class representing AuthSignAType
 */
class AuthSignAType
{

    /**
     * Тип подписи:
     *
     *  0- единственная
     *
     *  1- первая
     *
     *  2- вторая
     *
     *  3- визирующая
     *
     *  4- технологическая подпись банка
     *
     *  5- акцептующая
     *
     *  6- подпись операциониста банка
     *
     *  7- акцептующая подпись
     *
     *  8- подпись для ВК
     *
     *  9- визирующая подпись
     *
     * @property string $signType
     */
    private $signType = null;

    /**
     * Срок полномочий
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DurationAType $duration
     */
    private $duration = null;

    /**
     * Средства подписи, доступные уполномоченному лицу
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\SignDevicesAType $signDevices
     */
    private $signDevices = null;

    /**
     * Счета, доступные уполномоченному лицу в рамках
     *
     *  полномочия
     *
     *  подписи
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType $accounts
     */
    private $accounts = null;

    /**
     * Типы документов, доступные уполномоченному лицу
     *
     *  в рамках
     *
     *  полномочия подписи
     *
     * @property \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DocsAType $docs
     */
    private $docs = null;

    /**
     * Gets as signType
     *
     * Тип подписи:
     *
     *  0- единственная
     *
     *  1- первая
     *
     *  2- вторая
     *
     *  3- визирующая
     *
     *  4- технологическая подпись банка
     *
     *  5- акцептующая
     *
     *  6- подпись операциониста банка
     *
     *  7- акцептующая подпись
     *
     *  8- подпись для ВК
     *
     *  9- визирующая подпись
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
     * Тип подписи:
     *
     *  0- единственная
     *
     *  1- первая
     *
     *  2- вторая
     *
     *  3- визирующая
     *
     *  4- технологическая подпись банка
     *
     *  5- акцептующая
     *
     *  6- подпись операциониста банка
     *
     *  7- акцептующая подпись
     *
     *  8- подпись для ВК
     *
     *  9- визирующая подпись
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
     * @return \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DurationAType
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
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DurationAType $duration
     * @return static
     */
    public function setDuration(\common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DurationAType $duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * Gets as signDevices
     *
     * Средства подписи, доступные уполномоченному лицу
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\SignDevicesAType
     */
    public function getSignDevices()
    {
        return $this->signDevices;
    }

    /**
     * Sets a new signDevices
     *
     * Средства подписи, доступные уполномоченному лицу
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\SignDevicesAType $signDevices
     * @return static
     */
    public function setSignDevices(\common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\SignDevicesAType $signDevices)
    {
        $this->signDevices = $signDevices;
        return $this;
    }

    /**
     * Gets as accounts
     *
     * Счета, доступные уполномоченному лицу в рамках
     *
     *  полномочия
     *
     *  подписи
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * Счета, доступные уполномоченному лицу в рамках
     *
     *  полномочия
     *
     *  подписи
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType $accounts
     * @return static
     */
    public function setAccounts(\common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * Gets as docs
     *
     * Типы документов, доступные уполномоченному лицу
     *
     *  в рамках
     *
     *  полномочия подписи
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DocsAType
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     * Sets a new docs
     *
     * Типы документов, доступные уполномоченному лицу
     *
     *  в рамках
     *
     *  полномочия подписи
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DocsAType $docs
     * @return static
     */
    public function setDocs(\common\models\raiffeisenxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\DocsAType $docs)
    {
        $this->docs = $docs;
        return $this;
    }


}

