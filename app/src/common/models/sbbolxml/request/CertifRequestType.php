<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CertifRequestType
 *
 *
 * XSD Type: CertifRequest
 */
class CertifRequestType extends DocBaseType
{

    /**
     * Идентификатор запроса на сертификат для идентификации запроса при загрузке сертификата
     *  из УЦ
     *
     * @property string $requestId
     */
    private $requestId = null;

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
     * Уникальный идентификатор средства подписи. Соответствует значению атрибута из ответа
     *  Responce/OrgsInfo/SignDevices/SignDevice/SignDeviceId
     *
     * @property string $idCrypto
     */
    private $idCrypto = null;

    /**
     * Ссылка на закрытый ключ
     *
     * @property string $containerName
     */
    private $containerName = null;

    /**
     * Стандарт алгоритма формирования и проверки ЭЦП
     *  Возможные значения:
     *  GOST_2012
     *  GOST_2001
     *  Если поле не передается, по умолчанию используется GOST_2001
     *
     * @property string $cryptoAlgorithmStandard
     */
    private $cryptoAlgorithmStandard = null;

    /**
     * ФИО - фамилия, имя и отчество уполномоченного лица клиента
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
     * Наименование подразделения банка, где заключен контракт с организацией. Для СБ РФ
     *  должно передаваться значение Response/OrgsInfo/Branches/Branch/BankName, соответствующее
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
     * Страна. Должен передаваться 2-х символьный код страны из справочника стран. RU
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
     * Должность физического лица. Должно передаваться значение из
     *  Response/OrgsInfo/AuthPersons/AuthPerson/Position
     *
     * @property string $position
     */
    private $position = null;

    /**
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ; последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @property \common\models\sbbolxml\request\ParamType[] $params
     */
    private $params = null;

    /**
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @property \common\models\sbbolxml\request\CertifRequestType\DocsAType\DocAType[] $docs
     */
    private $docs = null;

    /**
     * Gets as requestId
     *
     * Идентификатор запроса на сертификат для идентификации запроса при загрузке сертификата
     *  из УЦ
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
     * Идентификатор запроса на сертификат для идентификации запроса при загрузке сертификата
     *  из УЦ
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
     * Уникальный идентификатор средства подписи. Соответствует значению атрибута из ответа
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
     * Уникальный идентификатор средства подписи. Соответствует значению атрибута из ответа
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
     * Gets as containerName
     *
     * Ссылка на закрытый ключ
     *
     * @return string
     */
    public function getContainerName()
    {
        return $this->containerName;
    }

    /**
     * Sets a new containerName
     *
     * Ссылка на закрытый ключ
     *
     * @param string $containerName
     * @return static
     */
    public function setContainerName($containerName)
    {
        $this->containerName = $containerName;
        return $this;
    }

    /**
     * Gets as cryptoAlgorithmStandard
     *
     * Стандарт алгоритма формирования и проверки ЭЦП
     *  Возможные значения:
     *  GOST_2012
     *  GOST_2001
     *  Если поле не передается, по умолчанию используется GOST_2001
     *
     * @return string
     */
    public function getCryptoAlgorithmStandard()
    {
        return $this->cryptoAlgorithmStandard;
    }

    /**
     * Sets a new cryptoAlgorithmStandard
     *
     * Стандарт алгоритма формирования и проверки ЭЦП
     *  Возможные значения:
     *  GOST_2012
     *  GOST_2001
     *  Если поле не передается, по умолчанию используется GOST_2001
     *
     * @param string $cryptoAlgorithmStandard
     * @return static
     */
    public function setCryptoAlgorithmStandard($cryptoAlgorithmStandard)
    {
        $this->cryptoAlgorithmStandard = $cryptoAlgorithmStandard;
        return $this;
    }

    /**
     * Gets as commonName
     *
     * ФИО - фамилия, имя и отчество уполномоченного лица клиента
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
     * ФИО - фамилия, имя и отчество уполномоченного лица клиента
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
     * Наименование подразделения банка, где заключен контракт с организацией. Для СБ РФ
     *  должно передаваться значение Response/OrgsInfo/Branches/Branch/BankName, соответствующее
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
     * Наименование подразделения банка, где заключен контракт с организацией. Для СБ РФ
     *  должно передаваться значение Response/OrgsInfo/Branches/Branch/BankName, соответствующее
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
     * Страна. Должен передаваться 2-х символьный код страны из справочника стран. RU
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
     * Страна. Должен передаваться 2-х символьный код страны из справочника стран. RU
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
     * Должность физического лица. Должно передаваться значение из
     *  Response/OrgsInfo/AuthPersons/AuthPerson/Position
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
     * Должность физического лица. Должно передаваться значение из
     *  Response/OrgsInfo/AuthPersons/AuthPerson/Position
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
     * Adds as param
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ; последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @return static
     * @param \common\models\sbbolxml\request\ParamType $param
     */
    public function addToParams(\common\models\sbbolxml\request\ParamType $param)
    {
        $this->params[] = $param;
        return $this;
    }

    /**
     * isset params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ; последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetParams($index)
    {
        return isset($this->params[$index]);
    }

    /**
     * unset params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ; последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetParams($index)
    {
        unset($this->params[$index]);
    }

    /**
     * Gets as params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ; последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @return \common\models\sbbolxml\request\ParamType[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Sets a new params
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие параметры:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/CertAuthId ), следующие символы до 8 - это
     *  порядковый код ключа (необходимо генерить следующий порядковый номер сертификата в УЦ; последний
     *  порядковый номер сертификата в УЦ передается в теге:
     *  Response/OrgsInfo/OrgsData/OrgData/OtherOrgData/LastCertifNum). Не разрешается использовать
     *  пробел в начале и в конце текста),
     *  tokenId - идентификатор токена,
     *  tokenTlsVersion - версия прошивки токена,
     *  pin- пин, под которым был сделан запрос.
     *
     * @param \common\models\sbbolxml\request\ParamType[] $params
     * @return static
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Adds as doc
     *
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @return static
     * @param \common\models\sbbolxml\request\CertifRequestType\DocsAType\DocAType $doc
     */
    public function addToDocs(\common\models\sbbolxml\request\CertifRequestType\DocsAType\DocAType $doc)
    {
        $this->docs[] = $doc;
        return $this;
    }

    /**
     * isset docs
     *
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDocs($index)
    {
        return isset($this->docs[$index]);
    }

    /**
     * unset docs
     *
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDocs($index)
    {
        unset($this->docs[$index]);
    }

    /**
     * Gets as docs
     *
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @return \common\models\sbbolxml\request\CertifRequestType\DocsAType\DocAType[]
     */
    public function getDocs()
    {
        return $this->docs;
    }

    /**
     * Sets a new docs
     *
     * Содержит данные одного клиентского документа "Запрос на новый сертификат"
     *
     * @param \common\models\sbbolxml\request\CertifRequestType\DocsAType\DocAType[] $docs
     * @return static
     */
    public function setDocs(array $docs)
    {
        $this->docs = $docs;
        return $this;
    }


}

