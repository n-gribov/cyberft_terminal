<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing InquiryInfoType
 *
 * Реквизиты Запроса справки
 * XSD Type: InquiryInfo
 */
class InquiryInfoType
{

    /**
     * Код справки
     *
     * @property string $inquiryCode
     */
    private $inquiryCode = null;

    /**
     * Способ получения справки: POST - Почта, VSP - ВСП и EI - E-Invoicing
     *
     * @property string $inquiryGet
     */
    private $inquiryGet = null;

    /**
     * Тип ценной бумаги
     *
     * @property string $secType
     */
    private $secType = null;

    /**
     * Почтовый адрес получателя (заполняется только если ./inquiryGet принимает значение: 2)
     *
     * @property string $inquiryGetAddress
     */
    private $inquiryGetAddress = null;

    /**
     * Количество экземпляров
     *
     * @property string $amount
     */
    private $amount = null;

    /**
     * Период с
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Начало периода
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Параметры запроса:
     *  TOTAL_SUM_TYPE5 - общей суммой;
     *  DETAILS_TYPE5 - с расшифровкой; (Заполняется при указании типа справки Type 5 )
     *  CURRENCY_ACCOUNT_DAILY - в валюте счета посуточно;
     *  CURRENCY_ACCOUNT_PER_MONTH - в валюте счета помесячно;
     *  CURRENCY_ACCOUNT_WITHOUT_LOANS - в валюте счета без учета кредитов;
     *  NATIONAL_DAILY - в нац. покрытии посуточно;
     *  NATIONAL_PER_MONTH - в нац. покрытии помесячно;
     *  NATIONAL_WITHOUT_LOANS - в нац. покрытии без учета кредитов; (Заполняется при указании типа справки Type 6 )
     *  TOTAL_SUM_TYPE7 - общей суммой;
     *  DETAILS_TYPE7 - с расшифровкой; (Заполняется при указании типа справки Type 7 )
     *  TOTAL_SUM_TYPE8 - общей суммой;
     *  DETAILS_TYPE8 - с расшифровкой; (Заполняется при указании типа справки Type 8 )
     *
     * @property string $typeOfFilling
     */
    private $typeOfFilling = null;

    /**
     * Платежные документы/реестры на зачисление
     *
     * @property \common\models\sbbolxml\request\PayDocInfoType[] $payDocsInfo
     */
    private $payDocsInfo = null;

    /**
     * Справка необходима для (место предоставления справки)
     *
     * @property string $requiredFor
     */
    private $requiredFor = null;

    /**
     * Дополнительная информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Счета
     *
     * @property \common\models\sbbolxml\request\AccNumBicType[] $accounts
     */
    private $accounts = null;

    /**
     * Gets as inquiryCode
     *
     * Код справки
     *
     * @return string
     */
    public function getInquiryCode()
    {
        return $this->inquiryCode;
    }

    /**
     * Sets a new inquiryCode
     *
     * Код справки
     *
     * @param string $inquiryCode
     * @return static
     */
    public function setInquiryCode($inquiryCode)
    {
        $this->inquiryCode = $inquiryCode;
        return $this;
    }

    /**
     * Gets as inquiryGet
     *
     * Способ получения справки: POST - Почта, VSP - ВСП и EI - E-Invoicing
     *
     * @return string
     */
    public function getInquiryGet()
    {
        return $this->inquiryGet;
    }

    /**
     * Sets a new inquiryGet
     *
     * Способ получения справки: POST - Почта, VSP - ВСП и EI - E-Invoicing
     *
     * @param string $inquiryGet
     * @return static
     */
    public function setInquiryGet($inquiryGet)
    {
        $this->inquiryGet = $inquiryGet;
        return $this;
    }

    /**
     * Gets as secType
     *
     * Тип ценной бумаги
     *
     * @return string
     */
    public function getSecType()
    {
        return $this->secType;
    }

    /**
     * Sets a new secType
     *
     * Тип ценной бумаги
     *
     * @param string $secType
     * @return static
     */
    public function setSecType($secType)
    {
        $this->secType = $secType;
        return $this;
    }

    /**
     * Gets as inquiryGetAddress
     *
     * Почтовый адрес получателя (заполняется только если ./inquiryGet принимает значение: 2)
     *
     * @return string
     */
    public function getInquiryGetAddress()
    {
        return $this->inquiryGetAddress;
    }

    /**
     * Sets a new inquiryGetAddress
     *
     * Почтовый адрес получателя (заполняется только если ./inquiryGet принимает значение: 2)
     *
     * @param string $inquiryGetAddress
     * @return static
     */
    public function setInquiryGetAddress($inquiryGetAddress)
    {
        $this->inquiryGetAddress = $inquiryGetAddress;
        return $this;
    }

    /**
     * Gets as amount
     *
     * Количество экземпляров
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets a new amount
     *
     * Количество экземпляров
     *
     * @param string $amount
     * @return static
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Gets as beginDate
     *
     * Период с
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
     * Период с
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
     * Начало периода
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
     * Начало периода
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
     * Gets as typeOfFilling
     *
     * Параметры запроса:
     *  TOTAL_SUM_TYPE5 - общей суммой;
     *  DETAILS_TYPE5 - с расшифровкой; (Заполняется при указании типа справки Type 5 )
     *  CURRENCY_ACCOUNT_DAILY - в валюте счета посуточно;
     *  CURRENCY_ACCOUNT_PER_MONTH - в валюте счета помесячно;
     *  CURRENCY_ACCOUNT_WITHOUT_LOANS - в валюте счета без учета кредитов;
     *  NATIONAL_DAILY - в нац. покрытии посуточно;
     *  NATIONAL_PER_MONTH - в нац. покрытии помесячно;
     *  NATIONAL_WITHOUT_LOANS - в нац. покрытии без учета кредитов; (Заполняется при указании типа справки Type 6 )
     *  TOTAL_SUM_TYPE7 - общей суммой;
     *  DETAILS_TYPE7 - с расшифровкой; (Заполняется при указании типа справки Type 7 )
     *  TOTAL_SUM_TYPE8 - общей суммой;
     *  DETAILS_TYPE8 - с расшифровкой; (Заполняется при указании типа справки Type 8 )
     *
     * @return string
     */
    public function getTypeOfFilling()
    {
        return $this->typeOfFilling;
    }

    /**
     * Sets a new typeOfFilling
     *
     * Параметры запроса:
     *  TOTAL_SUM_TYPE5 - общей суммой;
     *  DETAILS_TYPE5 - с расшифровкой; (Заполняется при указании типа справки Type 5 )
     *  CURRENCY_ACCOUNT_DAILY - в валюте счета посуточно;
     *  CURRENCY_ACCOUNT_PER_MONTH - в валюте счета помесячно;
     *  CURRENCY_ACCOUNT_WITHOUT_LOANS - в валюте счета без учета кредитов;
     *  NATIONAL_DAILY - в нац. покрытии посуточно;
     *  NATIONAL_PER_MONTH - в нац. покрытии помесячно;
     *  NATIONAL_WITHOUT_LOANS - в нац. покрытии без учета кредитов; (Заполняется при указании типа справки Type 6 )
     *  TOTAL_SUM_TYPE7 - общей суммой;
     *  DETAILS_TYPE7 - с расшифровкой; (Заполняется при указании типа справки Type 7 )
     *  TOTAL_SUM_TYPE8 - общей суммой;
     *  DETAILS_TYPE8 - с расшифровкой; (Заполняется при указании типа справки Type 8 )
     *
     * @param string $typeOfFilling
     * @return static
     */
    public function setTypeOfFilling($typeOfFilling)
    {
        $this->typeOfFilling = $typeOfFilling;
        return $this;
    }

    /**
     * Adds as payDocInfo
     *
     * Платежные документы/реестры на зачисление
     *
     * @return static
     * @param \common\models\sbbolxml\request\PayDocInfoType $payDocInfo
     */
    public function addToPayDocsInfo(\common\models\sbbolxml\request\PayDocInfoType $payDocInfo)
    {
        $this->payDocsInfo[] = $payDocInfo;
        return $this;
    }

    /**
     * isset payDocsInfo
     *
     * Платежные документы/реестры на зачисление
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPayDocsInfo($index)
    {
        return isset($this->payDocsInfo[$index]);
    }

    /**
     * unset payDocsInfo
     *
     * Платежные документы/реестры на зачисление
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPayDocsInfo($index)
    {
        unset($this->payDocsInfo[$index]);
    }

    /**
     * Gets as payDocsInfo
     *
     * Платежные документы/реестры на зачисление
     *
     * @return \common\models\sbbolxml\request\PayDocInfoType[]
     */
    public function getPayDocsInfo()
    {
        return $this->payDocsInfo;
    }

    /**
     * Sets a new payDocsInfo
     *
     * Платежные документы/реестры на зачисление
     *
     * @param \common\models\sbbolxml\request\PayDocInfoType[] $payDocsInfo
     * @return static
     */
    public function setPayDocsInfo(array $payDocsInfo)
    {
        $this->payDocsInfo = $payDocsInfo;
        return $this;
    }

    /**
     * Gets as requiredFor
     *
     * Справка необходима для (место предоставления справки)
     *
     * @return string
     */
    public function getRequiredFor()
    {
        return $this->requiredFor;
    }

    /**
     * Sets a new requiredFor
     *
     * Справка необходима для (место предоставления справки)
     *
     * @param string $requiredFor
     * @return static
     */
    public function setRequiredFor($requiredFor)
    {
        $this->requiredFor = $requiredFor;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Дополнительная информация
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * Дополнительная информация
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }

    /**
     * Adds as account
     *
     * Счета
     *
     * @return static
     * @param \common\models\sbbolxml\request\AccNumBicType $account
     */
    public function addToAccounts(\common\models\sbbolxml\request\AccNumBicType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * Счета
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAccounts($index)
    {
        return isset($this->accounts[$index]);
    }

    /**
     * unset accounts
     *
     * Счета
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAccounts($index)
    {
        unset($this->accounts[$index]);
    }

    /**
     * Gets as accounts
     *
     * Счета
     *
     * @return \common\models\sbbolxml\request\AccNumBicType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * Счета
     *
     * @param \common\models\sbbolxml\request\AccNumBicType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }


}

