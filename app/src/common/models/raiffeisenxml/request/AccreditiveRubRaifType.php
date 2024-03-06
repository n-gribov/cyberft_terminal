<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing AccreditiveRubRaifType
 *
 *
 * XSD Type: AccreditiveRubRaif
 */
class AccreditiveRubRaifType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты аккредитива
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Адрес
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Основная информация
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\MainAType $main
     */
    private $main = null;

    /**
     * Получатель
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType $receiver
     */
    private $receiver = null;

    /**
     * Наименование товаров (работ, услуг)
     *
     * @property string $goods
     */
    private $goods = null;

    /**
     * Отгрузка
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ShippingAType $shipping
     */
    private $shipping = null;

    /**
     * Документы
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\DocumentsAType $documents
     */
    private $documents = null;

    /**
     * Исполняющий банк
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ExecutiveBankAType $executiveBank
     */
    private $executiveBank = null;

    /**
     * Расходы по аккредитиву просим списать с нашего счета у Вас
     *
     * @property \common\models\raiffeisenxml\request\AccountType $accrCosts
     */
    private $accrCosts = null;

    /**
     * Покрытие по аккредитиву просим списать с нашего счета у Вас.
     *
     * @property \common\models\raiffeisenxml\request\AccountType $accrCover
     */
    private $accrCover = null;

    /**
     * Прочие условия
     *
     * @property string $addTerms
     */
    private $addTerms = null;

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
     * Gets as docData
     *
     * Общие реквизиты аккредитива
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\DocDataAType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты аккредитива
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as address
     *
     * Адрес
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets a new address
     *
     * Адрес
     *
     * @param string $address
     * @return static
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Gets as main
     *
     * Основная информация
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\MainAType
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Sets a new main
     *
     * Основная информация
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\MainAType $main
     * @return static
     */
    public function setMain(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\MainAType $main)
    {
        $this->main = $main;
        return $this;
    }

    /**
     * Gets as receiver
     *
     * Получатель
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Sets a new receiver
     *
     * Получатель
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType $receiver
     * @return static
     */
    public function setReceiver(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\ReceiverAType $receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * Gets as goods
     *
     * Наименование товаров (работ, услуг)
     *
     * @return string
     */
    public function getGoods()
    {
        return $this->goods;
    }

    /**
     * Sets a new goods
     *
     * Наименование товаров (работ, услуг)
     *
     * @param string $goods
     * @return static
     */
    public function setGoods($goods)
    {
        $this->goods = $goods;
        return $this;
    }

    /**
     * Gets as shipping
     *
     * Отгрузка
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ShippingAType
     */
    public function getShipping()
    {
        return $this->shipping;
    }

    /**
     * Sets a new shipping
     *
     * Отгрузка
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ShippingAType $shipping
     * @return static
     */
    public function setShipping(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\ShippingAType $shipping)
    {
        $this->shipping = $shipping;
        return $this;
    }

    /**
     * Gets as documents
     *
     * Документы
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\DocumentsAType
     */
    public function getDocuments()
    {
        return $this->documents;
    }

    /**
     * Sets a new documents
     *
     * Документы
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\DocumentsAType $documents
     * @return static
     */
    public function setDocuments(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\DocumentsAType $documents)
    {
        $this->documents = $documents;
        return $this;
    }

    /**
     * Gets as executiveBank
     *
     * Исполняющий банк
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ExecutiveBankAType
     */
    public function getExecutiveBank()
    {
        return $this->executiveBank;
    }

    /**
     * Sets a new executiveBank
     *
     * Исполняющий банк
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType\ExecutiveBankAType $executiveBank
     * @return static
     */
    public function setExecutiveBank(\common\models\raiffeisenxml\request\AccreditiveRubRaifType\ExecutiveBankAType $executiveBank)
    {
        $this->executiveBank = $executiveBank;
        return $this;
    }

    /**
     * Gets as accrCosts
     *
     * Расходы по аккредитиву просим списать с нашего счета у Вас
     *
     * @return \common\models\raiffeisenxml\request\AccountType
     */
    public function getAccrCosts()
    {
        return $this->accrCosts;
    }

    /**
     * Sets a new accrCosts
     *
     * Расходы по аккредитиву просим списать с нашего счета у Вас
     *
     * @param \common\models\raiffeisenxml\request\AccountType $accrCosts
     * @return static
     */
    public function setAccrCosts(\common\models\raiffeisenxml\request\AccountType $accrCosts)
    {
        $this->accrCosts = $accrCosts;
        return $this;
    }

    /**
     * Gets as accrCover
     *
     * Покрытие по аккредитиву просим списать с нашего счета у Вас.
     *
     * @return \common\models\raiffeisenxml\request\AccountType
     */
    public function getAccrCover()
    {
        return $this->accrCover;
    }

    /**
     * Sets a new accrCover
     *
     * Покрытие по аккредитиву просим списать с нашего счета у Вас.
     *
     * @param \common\models\raiffeisenxml\request\AccountType $accrCover
     * @return static
     */
    public function setAccrCover(\common\models\raiffeisenxml\request\AccountType $accrCover)
    {
        $this->accrCover = $accrCover;
        return $this;
    }

    /**
     * Gets as addTerms
     *
     * Прочие условия
     *
     * @return string
     */
    public function getAddTerms()
    {
        return $this->addTerms;
    }

    /**
     * Sets a new addTerms
     *
     * Прочие условия
     *
     * @param string $addTerms
     * @return static
     */
    public function setAddTerms($addTerms)
    {
        $this->addTerms = $addTerms;
        return $this;
    }


}

