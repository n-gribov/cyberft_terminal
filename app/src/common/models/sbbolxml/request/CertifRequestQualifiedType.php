<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CertifRequestQualifiedType
 *
 *
 * XSD Type: CertifRequestQualified
 */
class CertifRequestQualifiedType extends DocBaseType
{

    /**
     * Дата составления документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Ссылка на закрытый ключ
     *
     * @property string $containerName
     */
    private $containerName = null;

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Идентификатор уполномоченного лица. Соответствует значению
     *  OrgsInfo/AuthPersons/AuthPerson/AuthPersonId
     *
     * @property string $authPersonId
     */
    private $authPersonId = null;

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
     * Наименование подразделения банка, где заключен контракт с организацией.
     *  Для СБ РФ должно передаваться
     *  значение
     *  Response/OrgsInfo/Branches/Branch/BankName, соответствующее
     *  Response/OrgsInfo/Contracts/ContractInfo/BranchId
     *
     * @property string $organizationUnit
     */
    private $organizationUnit = null;

    /**
     * Владелец ключа ЭП
     *
     * @property \common\models\sbbolxml\request\CertifRequestQualifiedType\SignHolderAType $signHolder
     */
    private $signHolder = null;

    /**
     * Организация
     *
     * @property \common\models\sbbolxml\request\CertifRequestQualifiedType\OrganizationAType $organization
     */
    private $organization = null;

    /**
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие
     *  параметры:
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
     * @property \common\models\sbbolxml\request\CertifRequestQualifiedType\DocsAType\DocAType[] $docs
     */
    private $docs = null;

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
     * Gets as organizationUnit
     *
     * Наименование подразделения банка, где заключен контракт с организацией.
     *  Для СБ РФ должно передаваться
     *  значение
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
     * Наименование подразделения банка, где заключен контракт с организацией.
     *  Для СБ РФ должно передаваться
     *  значение
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
     * Gets as signHolder
     *
     * Владелец ключа ЭП
     *
     * @return \common\models\sbbolxml\request\CertifRequestQualifiedType\SignHolderAType
     */
    public function getSignHolder()
    {
        return $this->signHolder;
    }

    /**
     * Sets a new signHolder
     *
     * Владелец ключа ЭП
     *
     * @param \common\models\sbbolxml\request\CertifRequestQualifiedType\SignHolderAType $signHolder
     * @return static
     */
    public function setSignHolder(\common\models\sbbolxml\request\CertifRequestQualifiedType\SignHolderAType $signHolder)
    {
        $this->signHolder = $signHolder;
        return $this;
    }

    /**
     * Gets as organization
     *
     * Организация
     *
     * @return \common\models\sbbolxml\request\CertifRequestQualifiedType\OrganizationAType
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * Sets a new organization
     *
     * Организация
     *
     * @param \common\models\sbbolxml\request\CertifRequestQualifiedType\OrganizationAType $organization
     * @return static
     */
    public function setOrganization(\common\models\sbbolxml\request\CertifRequestQualifiedType\OrganizationAType $organization)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * Adds as param
     *
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие
     *  параметры:
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
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие
     *  параметры:
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
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие
     *  параметры:
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
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие
     *  параметры:
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
     * Дополнительные параметры. Для СБ РФ будут передаваться следующие
     *  параметры:
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
     * @param \common\models\sbbolxml\request\CertifRequestQualifiedType\DocsAType\DocAType $doc
     */
    public function addToDocs(\common\models\sbbolxml\request\CertifRequestQualifiedType\DocsAType\DocAType $doc)
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
     * @return \common\models\sbbolxml\request\CertifRequestQualifiedType\DocsAType\DocAType[]
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
     * @param \common\models\sbbolxml\request\CertifRequestQualifiedType\DocsAType\DocAType[] $docs
     * @return static
     */
    public function setDocs(array $docs)
    {
        $this->docs = $docs;
        return $this;
    }


}

