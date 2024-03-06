<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DzoAccAttachType
 *
 * Заявка на оплату документа таможни
 * XSD Type: DzoAccAttach
 */
class DzoAccAttachType extends DocBaseType
{

    /**
     * Тип действия, возможные значения:
     *  add - прикрепление счетов
     *  rem - открепление счетов
     *
     * @property string $operationType
     */
    private $operationType = null;

    /**
     * Идентификатор Головной Компании
     *
     * @property string $headOrgId
     */
    private $headOrgId = null;

    /**
     * Реквизиты документа
     *
     * @property \common\models\sbbolxml\request\DzoAccAttachDocDataType $docData
     */
    private $docData = null;

    /**
     * Основные реквизиты дочерней организации
     *
     * @property \common\models\sbbolxml\request\DzoOrgDataType $dzoOrgData
     */
    private $dzoOrgData = null;

    /**
     * Счета ДЗО, прикрепляемые к головной компании холдинга
     *
     * @property \common\models\sbbolxml\request\AccountType[] $accounts
     */
    private $accounts = null;

    /**
     * Gets as operationType
     *
     * Тип действия, возможные значения:
     *  add - прикрепление счетов
     *  rem - открепление счетов
     *
     * @return string
     */
    public function getOperationType()
    {
        return $this->operationType;
    }

    /**
     * Sets a new operationType
     *
     * Тип действия, возможные значения:
     *  add - прикрепление счетов
     *  rem - открепление счетов
     *
     * @param string $operationType
     * @return static
     */
    public function setOperationType($operationType)
    {
        $this->operationType = $operationType;
        return $this;
    }

    /**
     * Gets as headOrgId
     *
     * Идентификатор Головной Компании
     *
     * @return string
     */
    public function getHeadOrgId()
    {
        return $this->headOrgId;
    }

    /**
     * Sets a new headOrgId
     *
     * Идентификатор Головной Компании
     *
     * @param string $headOrgId
     * @return static
     */
    public function setHeadOrgId($headOrgId)
    {
        $this->headOrgId = $headOrgId;
        return $this;
    }

    /**
     * Gets as docData
     *
     * Реквизиты документа
     *
     * @return \common\models\sbbolxml\request\DzoAccAttachDocDataType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Реквизиты документа
     *
     * @param \common\models\sbbolxml\request\DzoAccAttachDocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DzoAccAttachDocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as dzoOrgData
     *
     * Основные реквизиты дочерней организации
     *
     * @return \common\models\sbbolxml\request\DzoOrgDataType
     */
    public function getDzoOrgData()
    {
        return $this->dzoOrgData;
    }

    /**
     * Sets a new dzoOrgData
     *
     * Основные реквизиты дочерней организации
     *
     * @param \common\models\sbbolxml\request\DzoOrgDataType $dzoOrgData
     * @return static
     */
    public function setDzoOrgData(\common\models\sbbolxml\request\DzoOrgDataType $dzoOrgData)
    {
        $this->dzoOrgData = $dzoOrgData;
        return $this;
    }

    /**
     * Adds as account
     *
     * Счета ДЗО, прикрепляемые к головной компании холдинга
     *
     * @return static
     * @param \common\models\sbbolxml\request\AccountType $account
     */
    public function addToAccounts(\common\models\sbbolxml\request\AccountType $account)
    {
        $this->accounts[] = $account;
        return $this;
    }

    /**
     * isset accounts
     *
     * Счета ДЗО, прикрепляемые к головной компании холдинга
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
     * Счета ДЗО, прикрепляемые к головной компании холдинга
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
     * Счета ДЗО, прикрепляемые к головной компании холдинга
     *
     * @return \common\models\sbbolxml\request\AccountType[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * Sets a new accounts
     *
     * Счета ДЗО, прикрепляемые к головной компании холдинга
     *
     * @param \common\models\sbbolxml\request\AccountType[] $accounts
     * @return static
     */
    public function setAccounts(array $accounts)
    {
        $this->accounts = $accounts;
        return $this;
    }


}

