<?php

namespace common\models\raiffeisenxml\request\CertifRequestType\DocsAType;

/**
 * Class representing DocAType
 */
class DocAType
{

    /**
     * Идентификатор запроса на сертификат для идентификации запроса
     *  при загрузке сертификата из УЦ
     *
     * @property string $requestId
     */
    private $requestId = null;

    /**
     * Тип запроса, передается константа:
     *  «sign» - для запроса сертификата для подписи,
     *  «tls» - для запроса сертификата tls.
     *  При необходимости список может быть расширен.
     *
     * @property string $type
     */
    private $type = null;

    /**
     * Дата составления документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Уникальный идентификатор средства подписи. Соответствует
     *  значению атрибута из ответа
     *  Responce/OrgsInfo/SignDevices/SignDevice/SignDeviceId
     *
     * @property string $idCrypto
     */
    private $idCrypto = null;

    /**
     * Идентификатор уполномоченного лица. Соответствует значению
     *  OrgsInfo/AuthPersons/AuthPerson/AuthPersonId
     *
     * @property string $authPersonId
     */
    private $authPersonId = null;

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * ФИО - фамилия, имя и отчество уполномоченного лица
     *  клиента
     *
     * @property string $commonName
     */
    private $commonName = null;

    /**
     * Краткое наименование организации
     *
     * @property string $organization
     */
    private $organization = null;

    /**
     * Наименование подразделения банка, где заключен контракт с
     *  организацией. Для СБ РФ должно передаваться значение
     *  Response/OrgsInfo/Branches/Branch/BankName, соответствующее
     *  Response/OrgsInfo/Contracts/ContractInfo/BranchId
     *
     * @property string $organizationUnit
     */
    private $organizationUnit = null;

    /**
     * Город местонахождения ЮЛ
     *
     * @property string $locality
     */
    private $locality = null;

    /**
     * Страна. Должен передаваться 2-х символьный код страны из
     *  справочника стран. RU
     *
     * @property string $country
     */
    private $country = null;

    /**
     * Е-мейл
     *
     * @property string $email
     */
    private $email = null;

    /**
     * Должность физического лица. Должно передаваться значение
     *  из Response/OrgsInfo/AuthPersons/AuthPerson/Position
     *
     * @property string $position
     */
    private $position = null;

    /**
     * Отдельное вложение
     *
     * @property \common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType\AttachmentAType $attachment
     */
    private $attachment = null;

    /**
     * Дополнительные параметры. Для СБ РФ будут передаваться
     *  следующие параметры:
     *  bicryptId - идентификатор Бикрипт (строка, где первые 4 или 6 символов –
     *  код УЦ Клиента владельца ключа (идентификатор организации в УЦ,
     *  передается в персональных данных в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие
     *  символы до 8 - это порядковый код ключа (необходимо генерить следующий
     *  порядковый номер сертификата в УЦ; последний порядковый номер
     *  сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не
     *  разрешается использовать пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @property \common\models\raiffeisenxml\request\ParamType[] $params
     */
    private $params = null;

    /**
     * Gets as requestId
     *
     * Идентификатор запроса на сертификат для идентификации запроса
     *  при загрузке сертификата из УЦ
     *
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * Sets a new requestId
     *
     * Идентификатор запроса на сертификат для идентификации запроса
     *  при загрузке сертификата из УЦ
     *
     * @param string $requestId
     * @return static
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    /**
     * Gets as type
     *
     * Тип запроса, передается константа:
     *  «sign» - для запроса сертификата для подписи,
     *  «tls» - для запроса сертификата tls.
     *  При необходимости список может быть расширен.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * Тип запроса, передается константа:
     *  «sign» - для запроса сертификата для подписи,
     *  «tls» - для запроса сертификата tls.
     *  При необходимости список может быть расширен.
     *
     * @param string $type
     * @return static
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата составления документа
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата составления документа
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер документа
     *
     * @return string
     */
    public function getDocNum()
    {
        return $this->docNum;
    }

    /**
     * Sets a new docNum
     *
     * Номер документа
     *
     * @param string $docNum
     * @return static
     */
    public function setDocNum($docNum)
    {
        $this->docNum = $docNum;
        return $this;
    }

    /**
     * Gets as idCrypto
     *
     * Уникальный идентификатор средства подписи. Соответствует
     *  значению атрибута из ответа
     *  Responce/OrgsInfo/SignDevices/SignDevice/SignDeviceId
     *
     * @return string
     */
    public function getIdCrypto()
    {
        return $this->idCrypto;
    }

    /**
     * Sets a new idCrypto
     *
     * Уникальный идентификатор средства подписи. Соответствует
     *  значению атрибута из ответа
     *  Responce/OrgsInfo/SignDevices/SignDevice/SignDeviceId
     *
     * @param string $idCrypto
     * @return static
     */
    public function setIdCrypto($idCrypto)
    {
        $this->idCrypto = $idCrypto;
        return $this;
    }

    /**
     * Gets as authPersonId
     *
     * Идентификатор уполномоченного лица. Соответствует значению
     *  OrgsInfo/AuthPersons/AuthPerson/AuthPersonId
     *
     * @return string
     */
    public function getAuthPersonId()
    {
        return $this->authPersonId;
    }

    /**
     * Sets a new authPersonId
     *
     * Идентификатор уполномоченного лица. Соответствует значению
     *  OrgsInfo/AuthPersons/AuthPerson/AuthPersonId
     *
     * @param string $authPersonId
     * @return static
     */
    public function setAuthPersonId($authPersonId)
    {
        $this->authPersonId = $authPersonId;
        return $this;
    }

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в УС
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор документа в УС
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

    /**
     * Gets as commonName
     *
     * ФИО - фамилия, имя и отчество уполномоченного лица
     *  клиента
     *
     * @return string
     */
    public function getCommonName()
    {
        return $this->commonName;
    }

    /**
     * Sets a new commonName
     *
     * ФИО - фамилия, имя и отчество уполномоченного лица
     *  клиента
     *
     * @param string $commonName
     * @return static
     */
    public function setCommonName($commonName)
    {
        $this->commonName = $commonName;
        return $this;
    }

    /**
     * Gets as organization
     *
     * Краткое наименование организации
     *
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Sets a new organization
     *
     * Краткое наименование организации
     *
     * @param string $organization
     * @return static
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * Gets as organizationUnit
     *
     * Наименование подразделения банка, где заключен контракт с
     *  организацией. Для СБ РФ должно передаваться значение
     *  Response/OrgsInfo/Branches/Branch/BankName, соответствующее
     *  Response/OrgsInfo/Contracts/ContractInfo/BranchId
     *
     * @return string
     */
    public function getOrganizationUnit()
    {
        return $this->organizationUnit;
    }

    /**
     * Sets a new organizationUnit
     *
     * Наименование подразделения банка, где заключен контракт с
     *  организацией. Для СБ РФ должно передаваться значение
     *  Response/OrgsInfo/Branches/Branch/BankName, соответствующее
     *  Response/OrgsInfo/Contracts/ContractInfo/BranchId
     *
     * @param string $organizationUnit
     * @return static
     */
    public function setOrganizationUnit($organizationUnit)
    {
        $this->organizationUnit = $organizationUnit;
        return $this;
    }

    /**
     * Gets as locality
     *
     * Город местонахождения ЮЛ
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Sets a new locality
     *
     * Город местонахождения ЮЛ
     *
     * @param string $locality
     * @return static
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
        return $this;
    }

    /**
     * Gets as country
     *
     * Страна. Должен передаваться 2-х символьный код страны из
     *  справочника стран. RU
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Страна. Должен передаваться 2-х символьный код страны из
     *  справочника стран. RU
     *
     * @param string $country
     * @return static
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Gets as email
     *
     * Е-мейл
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets a new email
     *
     * Е-мейл
     *
     * @param string $email
     * @return static
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Gets as position
     *
     * Должность физического лица. Должно передаваться значение
     *  из Response/OrgsInfo/AuthPersons/AuthPerson/Position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets a new position
     *
     * Должность физического лица. Должно передаваться значение
     *  из Response/OrgsInfo/AuthPersons/AuthPerson/Position
     *
     * @param string $position
     * @return static
     */
    public function setPosition($position)
    {
        $this->position = $position;
        return $this;
    }

    /**
     * Gets as attachment
     *
     * Отдельное вложение
     *
     * @return \common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType\AttachmentAType
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * Sets a new attachment
     *
     * Отдельное вложение
     *
     * @param \common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType\AttachmentAType $attachment
     * @return static
     */
    public function setAttachment(\common\models\raiffeisenxml\request\CertifRequestType\DocsAType\DocAType\AttachmentAType $attachment)
    {
        $this->attachment = $attachment;
        return $this;
    }

    /**
     * Adds as param
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться
     *  следующие параметры:
     *  bicryptId - идентификатор Бикрипт (строка, где первые 4 или 6 символов –
     *  код УЦ Клиента владельца ключа (идентификатор организации в УЦ,
     *  передается в персональных данных в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие
     *  символы до 8 - это порядковый код ключа (необходимо генерить следующий
     *  порядковый номер сертификата в УЦ; последний порядковый номер
     *  сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не
     *  разрешается использовать пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\ParamType $param
     */
    public function addToParams(\common\models\raiffeisenxml\request\ParamType $param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * isset params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться
     *  следующие параметры:
     *  bicryptId - идентификатор Бикрипт (строка, где первые 4 или 6 символов –
     *  код УЦ Клиента владельца ключа (идентификатор организации в УЦ,
     *  передается в персональных данных в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие
     *  символы до 8 - это порядковый код ключа (необходимо генерить следующий
     *  порядковый номер сертификата в УЦ; последний порядковый номер
     *  сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не
     *  разрешается использовать пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetParams($index)
    {
        return isset($this->params[$index]);
    }

    /**
     * unset params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться
     *  следующие параметры:
     *  bicryptId - идентификатор Бикрипт (строка, где первые 4 или 6 символов –
     *  код УЦ Клиента владельца ключа (идентификатор организации в УЦ,
     *  передается в персональных данных в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие
     *  символы до 8 - это порядковый код ключа (необходимо генерить следующий
     *  порядковый номер сертификата в УЦ; последний порядковый номер
     *  сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не
     *  разрешается использовать пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetParams($index)
    {
        unset($this->params[$index]);
    }

    /**
     * Gets as params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться
     *  следующие параметры:
     *  bicryptId - идентификатор Бикрипт (строка, где первые 4 или 6 символов –
     *  код УЦ Клиента владельца ключа (идентификатор организации в УЦ,
     *  передается в персональных данных в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие
     *  символы до 8 - это порядковый код ключа (необходимо генерить следующий
     *  порядковый номер сертификата в УЦ; последний порядковый номер
     *  сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не
     *  разрешается использовать пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @return \common\models\raiffeisenxml\request\ParamType[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sets a new params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться
     *  следующие параметры:
     *  bicryptId - идентификатор Бикрипт (строка, где первые 4 или 6 символов –
     *  код УЦ Клиента владельца ключа (идентификатор организации в УЦ,
     *  передается в персональных данных в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие
     *  символы до 8 - это порядковый код ключа (необходимо генерить следующий
     *  порядковый номер сертификата в УЦ; последний порядковый номер
     *  сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не
     *  разрешается использовать пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @param \common\models\raiffeisenxml\request\ParamType[] $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }


}

