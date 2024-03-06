<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType;

/**
 * Class representing AuthSignAType
 */
class AuthSignAType
{

    /**
     * Идентификатор криптопрофиля
     *
     * @property string $signDeviceId
     */
    private $signDeviceId = null;

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
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DurationAType $duration
     */
    private $duration = null;

    /**
     * Полномочия акцепта
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AcceptAType $accept
     */
    private $accept = null;

    /**
     * Счета, доступные уполномоченному лицу в рамках
     *
     *  полномочия
     *
     *  подписи
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AccountsAType $accounts
     */
    private $accounts = null;

    /**
     * Типы документов, доступные уполномоченному
     *  лицу
     *
     *  в рамках
     *
     *  полномочия подписи
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DocsAType $docs
     */
    private $docs = null;

    /**
     * Gets as signDeviceId
     *
     * Идентификатор криптопрофиля
     *
     * @return string
     */
    public function getSignDeviceId()
    {
        return $this->signDeviceId;
    }

    /**
     * Sets a new signDeviceId
     *
     * Идентификатор криптопрофиля
     *
     * @param string $signDeviceId
     * @return static
     */
    public function setSignDeviceId($signDeviceId)
    {
        $this->signDeviceId = $signDeviceId;
        return $this;
    }

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
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DurationAType
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DurationAType $duration
     * @return static
     */
    public function setDuration(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DurationAType $duration)
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * Gets as accept
     *
     * Полномочия акцепта
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AcceptAType
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Sets a new accept
     *
     * Полномочия акцепта
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AcceptAType $accept
     * @return static
     */
    public function setAccept(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AcceptAType $accept)
    {
        $this->accept = $accept;
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
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AccountsAType
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AccountsAType $accounts
     * @return static
     */
    public function setAccounts(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\AccountsAType $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }

    /**
     * Gets as docs
     *
     * Типы документов, доступные уполномоченному
     *  лицу
     *
     *  в рамках
     *
     *  полномочия подписи
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DocsAType
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     * Sets a new docs
     *
     * Типы документов, доступные уполномоченному
     *  лицу
     *
     *  в рамках
     *
     *  полномочия подписи
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DocsAType $docs
     * @return static
     */
    public function setDocs(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\AuthSignsAType\AuthSignAType\DocsAType $docs)
    {
        $this->docs = $docs;
        return $this;
    }


}

