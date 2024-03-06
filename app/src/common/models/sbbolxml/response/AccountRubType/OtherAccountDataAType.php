<?php

namespace common\models\sbbolxml\response\AccountRubType;

/**
 * Class representing OtherAccountDataAType
 */
class OtherAccountDataAType
{

    /**
     * Наименование счета
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Числовой код валюты счета
     *
     *  Если не пришло, то вычисляем по номеру счета
     *
     * @property string $currencyCode
     */
    private $currencyCode = null;

    /**
     * ISO-код валюты договора (3-х буквенный код валюты)
     *
     * @property string $currCode
     */
    private $currCode = null;

    /**
     * Использовать наименование организации в документах
     *  1 - использовать
     *  0 - не использовать
     *
     * @property boolean $useOrgNameInDoc
     */
    private $useOrgNameInDoc = null;

    /**
     * Наименование орагнизации в документах
     *
     * @property string $orgNameInDoc
     */
    private $orgNameInDoc = null;

    /**
     * Тип счёта: 01 – расчетный, 02 – бюджетный, 03 – транзитный, 04- специальный транзитный валютный счет, 05 - ссудный, 06 - счет по учету обеспечения.
     *
     * @property string $accountType
     */
    private $accountType = null;

    /**
     * Тип счёта: 0 – активный, 1 – пассивный (по умолчанию)
     *
     * @property boolean $accountTypeP
     */
    private $accountTypeP = null;

    /**
     * Обслуживание в ДБО
     *  1 - обслуживается
     *  0 - не обслуживается
     *
     * @property boolean $dBO
     */
    private $dBO = null;

    /**
     * Признак бизнес-счёта
     *
     * @property string $business
     */
    private $business = null;

    /**
     * Признак бизнес-счёта "нового" типа
     *
     * @property string $isBusinessNewType
     */
    private $isBusinessNewType = null;

    /**
     * Признак возможности проведения неотложных платежей: 0 - не установлен, 1 - установлен
     *
     * @property boolean $isNotDelay
     */
    private $isNotDelay = null;

    /**
     * Признак проведения срочных платежей
     *  Возможные значения: 0 - не установлен, 1 - установлен
     *
     * @property boolean $isUrgent
     */
    private $isUrgent = null;

    /**
     * Признак возможности предоставления информации о платежах по счету третьим лицам: 0 - не установлен, 1 - установлен
     *
     * @property boolean $isIntermed
     */
    private $isIntermed = null;

    /**
     * Дата открытия счета
     *
     * @property \DateTime $createDate
     */
    private $createDate = null;

    /**
     * Дата закрытия счёта
     *
     * @property \DateTime $closeDate
     */
    private $closeDate = null;

    /**
     * Состояние счета:
     *  OPEN - открыт;
     *  BLOCKED - заблокирован;
     *  CLOSED - закрыт.
     *  По умолчанию - OPEN
     *
     * @property string $state
     */
    private $state = null;

    /**
     * Режим:
     *  1 - стандартный;
     *  2 - разрешена подготовка только выписок по счету (запрещен прием документов);
     *  3 - разрешен только прием документов (запрещено формирование выписок).
     *
     * @property string $mode
     */
    private $mode = null;

    /**
     * Блокировки средств на счетах
     *
     * @property \common\models\sbbolxml\response\BlockedFullInfoType $blockedFullInfo
     */
    private $blockedFullInfo = null;

    /**
     * Информация о документах, поставленных в Картотеку 1 и 2 к счету
     *
     * @property \common\models\sbbolxml\response\CartotecaDocType $cartotecaDocInfo
     */
    private $cartotecaDocInfo = null;

    /**
     * Минимальный поддерживаемый остаток на счете (неснижаемый остаток)
     *
     * @property float $minBalance
     */
    private $minBalance = null;

    /**
     * Сумма общего лимита овердрафта в валюте счёта
     *
     * @property float $sumOvd
     */
    private $sumOvd = null;

    /**
     * Дополнительная информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * @property \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\AddInfoSBKAType $addInfoSBK
     */
    private $addInfoSBK = null;

    /**
     * @property \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\CompensatingProductsAType $compensatingProducts
     */
    private $compensatingProducts = null;

    /**
     * Если счет является ссудным, дополнительно может передаваться информация по кредитным договорам.
     *
     * @property \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\CreditContractsAType\CreditContractAType[] $creditContracts
     */
    private $creditContracts = null;

    /**
     * Номер договора государственного оборонного заказа (ГОЗ)
     *
     * @property string $contractOfTheStateDefenseOrder
     */
    private $contractOfTheStateDefenseOrder = null;

    /**
     * Счет подключен к устройству
     *  самообслуживания на территории
     *  Клиента (АДМ)
     *
     * @property boolean $adm
     */
    private $adm = null;

    /**
     * Счет подключен к устройству
     *  самообслуживания (УС)
     *
     * @property boolean $selfEnCashment
     */
    private $selfEnCashment = null;

    /**
     * Gets as name
     *
     * Наименование счета
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Наименование счета
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as currencyCode
     *
     * Числовой код валюты счета
     *
     *  Если не пришло, то вычисляем по номеру счета
     *
     * @return string
     */
    public function getCurrencyCode()
    {
        return $this->currencyCode;
    }

    /**
     * Sets a new currencyCode
     *
     * Числовой код валюты счета
     *
     *  Если не пришло, то вычисляем по номеру счета
     *
     * @param string $currencyCode
     * @return static
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currencyCode = $currencyCode;
        return $this;
    }

    /**
     * Gets as currCode
     *
     * ISO-код валюты договора (3-х буквенный код валюты)
     *
     * @return string
     */
    public function getCurrCode()
    {
        return $this->currCode;
    }

    /**
     * Sets a new currCode
     *
     * ISO-код валюты договора (3-х буквенный код валюты)
     *
     * @param string $currCode
     * @return static
     */
    public function setCurrCode($currCode)
    {
        $this->currCode = $currCode;
        return $this;
    }

    /**
     * Gets as useOrgNameInDoc
     *
     * Использовать наименование организации в документах
     *  1 - использовать
     *  0 - не использовать
     *
     * @return boolean
     */
    public function getUseOrgNameInDoc()
    {
        return $this->useOrgNameInDoc;
    }

    /**
     * Sets a new useOrgNameInDoc
     *
     * Использовать наименование организации в документах
     *  1 - использовать
     *  0 - не использовать
     *
     * @param boolean $useOrgNameInDoc
     * @return static
     */
    public function setUseOrgNameInDoc($useOrgNameInDoc)
    {
        $this->useOrgNameInDoc = $useOrgNameInDoc;
        return $this;
    }

    /**
     * Gets as orgNameInDoc
     *
     * Наименование орагнизации в документах
     *
     * @return string
     */
    public function getOrgNameInDoc()
    {
        return $this->orgNameInDoc;
    }

    /**
     * Sets a new orgNameInDoc
     *
     * Наименование орагнизации в документах
     *
     * @param string $orgNameInDoc
     * @return static
     */
    public function setOrgNameInDoc($orgNameInDoc)
    {
        $this->orgNameInDoc = $orgNameInDoc;
        return $this;
    }

    /**
     * Gets as accountType
     *
     * Тип счёта: 01 – расчетный, 02 – бюджетный, 03 – транзитный, 04- специальный транзитный валютный счет, 05 - ссудный, 06 - счет по учету обеспечения.
     *
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * Sets a new accountType
     *
     * Тип счёта: 01 – расчетный, 02 – бюджетный, 03 – транзитный, 04- специальный транзитный валютный счет, 05 - ссудный, 06 - счет по учету обеспечения.
     *
     * @param string $accountType
     * @return static
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
        return $this;
    }

    /**
     * Gets as accountTypeP
     *
     * Тип счёта: 0 – активный, 1 – пассивный (по умолчанию)
     *
     * @return boolean
     */
    public function getAccountTypeP()
    {
        return $this->accountTypeP;
    }

    /**
     * Sets a new accountTypeP
     *
     * Тип счёта: 0 – активный, 1 – пассивный (по умолчанию)
     *
     * @param boolean $accountTypeP
     * @return static
     */
    public function setAccountTypeP($accountTypeP)
    {
        $this->accountTypeP = $accountTypeP;
        return $this;
    }

    /**
     * Gets as dBO
     *
     * Обслуживание в ДБО
     *  1 - обслуживается
     *  0 - не обслуживается
     *
     * @return boolean
     */
    public function getDBO()
    {
        return $this->dBO;
    }

    /**
     * Sets a new dBO
     *
     * Обслуживание в ДБО
     *  1 - обслуживается
     *  0 - не обслуживается
     *
     * @param boolean $dBO
     * @return static
     */
    public function setDBO($dBO)
    {
        $this->dBO = $dBO;
        return $this;
    }

    /**
     * Gets as business
     *
     * Признак бизнес-счёта
     *
     * @return string
     */
    public function getBusiness()
    {
        return $this->business;
    }

    /**
     * Sets a new business
     *
     * Признак бизнес-счёта
     *
     * @param string $business
     * @return static
     */
    public function setBusiness($business)
    {
        $this->business = $business;
        return $this;
    }

    /**
     * Gets as isBusinessNewType
     *
     * Признак бизнес-счёта "нового" типа
     *
     * @return string
     */
    public function getIsBusinessNewType()
    {
        return $this->isBusinessNewType;
    }

    /**
     * Sets a new isBusinessNewType
     *
     * Признак бизнес-счёта "нового" типа
     *
     * @param string $isBusinessNewType
     * @return static
     */
    public function setIsBusinessNewType($isBusinessNewType)
    {
        $this->isBusinessNewType = $isBusinessNewType;
        return $this;
    }

    /**
     * Gets as isNotDelay
     *
     * Признак возможности проведения неотложных платежей: 0 - не установлен, 1 - установлен
     *
     * @return boolean
     */
    public function getIsNotDelay()
    {
        return $this->isNotDelay;
    }

    /**
     * Sets a new isNotDelay
     *
     * Признак возможности проведения неотложных платежей: 0 - не установлен, 1 - установлен
     *
     * @param boolean $isNotDelay
     * @return static
     */
    public function setIsNotDelay($isNotDelay)
    {
        $this->isNotDelay = $isNotDelay;
        return $this;
    }

    /**
     * Gets as isUrgent
     *
     * Признак проведения срочных платежей
     *  Возможные значения: 0 - не установлен, 1 - установлен
     *
     * @return boolean
     */
    public function getIsUrgent()
    {
        return $this->isUrgent;
    }

    /**
     * Sets a new isUrgent
     *
     * Признак проведения срочных платежей
     *  Возможные значения: 0 - не установлен, 1 - установлен
     *
     * @param boolean $isUrgent
     * @return static
     */
    public function setIsUrgent($isUrgent)
    {
        $this->isUrgent = $isUrgent;
        return $this;
    }

    /**
     * Gets as isIntermed
     *
     * Признак возможности предоставления информации о платежах по счету третьим лицам: 0 - не установлен, 1 - установлен
     *
     * @return boolean
     */
    public function getIsIntermed()
    {
        return $this->isIntermed;
    }

    /**
     * Sets a new isIntermed
     *
     * Признак возможности предоставления информации о платежах по счету третьим лицам: 0 - не установлен, 1 - установлен
     *
     * @param boolean $isIntermed
     * @return static
     */
    public function setIsIntermed($isIntermed)
    {
        $this->isIntermed = $isIntermed;
        return $this;
    }

    /**
     * Gets as createDate
     *
     * Дата открытия счета
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->createDate;
    }

    /**
     * Sets a new createDate
     *
     * Дата открытия счета
     *
     * @param \DateTime $createDate
     * @return static
     */
    public function setCreateDate(\DateTime $createDate)
    {
        $this->createDate = $createDate;
        return $this;
    }

    /**
     * Gets as closeDate
     *
     * Дата закрытия счёта
     *
     * @return \DateTime
     */
    public function getCloseDate()
    {
        return $this->closeDate;
    }

    /**
     * Sets a new closeDate
     *
     * Дата закрытия счёта
     *
     * @param \DateTime $closeDate
     * @return static
     */
    public function setCloseDate(\DateTime $closeDate)
    {
        $this->closeDate = $closeDate;
        return $this;
    }

    /**
     * Gets as state
     *
     * Состояние счета:
     *  OPEN - открыт;
     *  BLOCKED - заблокирован;
     *  CLOSED - закрыт.
     *  По умолчанию - OPEN
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets a new state
     *
     * Состояние счета:
     *  OPEN - открыт;
     *  BLOCKED - заблокирован;
     *  CLOSED - закрыт.
     *  По умолчанию - OPEN
     *
     * @param string $state
     * @return static
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Gets as mode
     *
     * Режим:
     *  1 - стандартный;
     *  2 - разрешена подготовка только выписок по счету (запрещен прием документов);
     *  3 - разрешен только прием документов (запрещено формирование выписок).
     *
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * Sets a new mode
     *
     * Режим:
     *  1 - стандартный;
     *  2 - разрешена подготовка только выписок по счету (запрещен прием документов);
     *  3 - разрешен только прием документов (запрещено формирование выписок).
     *
     * @param string $mode
     * @return static
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * Gets as blockedFullInfo
     *
     * Блокировки средств на счетах
     *
     * @return \common\models\sbbolxml\response\BlockedFullInfoType
     */
    public function getBlockedFullInfo()
    {
        return $this->blockedFullInfo;
    }

    /**
     * Sets a new blockedFullInfo
     *
     * Блокировки средств на счетах
     *
     * @param \common\models\sbbolxml\response\BlockedFullInfoType $blockedFullInfo
     * @return static
     */
    public function setBlockedFullInfo(\common\models\sbbolxml\response\BlockedFullInfoType $blockedFullInfo)
    {
        $this->blockedFullInfo = $blockedFullInfo;
        return $this;
    }

    /**
     * Gets as cartotecaDocInfo
     *
     * Информация о документах, поставленных в Картотеку 1 и 2 к счету
     *
     * @return \common\models\sbbolxml\response\CartotecaDocType
     */
    public function getCartotecaDocInfo()
    {
        return $this->cartotecaDocInfo;
    }

    /**
     * Sets a new cartotecaDocInfo
     *
     * Информация о документах, поставленных в Картотеку 1 и 2 к счету
     *
     * @param \common\models\sbbolxml\response\CartotecaDocType $cartotecaDocInfo
     * @return static
     */
    public function setCartotecaDocInfo(\common\models\sbbolxml\response\CartotecaDocType $cartotecaDocInfo)
    {
        $this->cartotecaDocInfo = $cartotecaDocInfo;
        return $this;
    }

    /**
     * Gets as minBalance
     *
     * Минимальный поддерживаемый остаток на счете (неснижаемый остаток)
     *
     * @return float
     */
    public function getMinBalance()
    {
        return $this->minBalance;
    }

    /**
     * Sets a new minBalance
     *
     * Минимальный поддерживаемый остаток на счете (неснижаемый остаток)
     *
     * @param float $minBalance
     * @return static
     */
    public function setMinBalance($minBalance)
    {
        $this->minBalance = $minBalance;
        return $this;
    }

    /**
     * Gets as sumOvd
     *
     * Сумма общего лимита овердрафта в валюте счёта
     *
     * @return float
     */
    public function getSumOvd()
    {
        return $this->sumOvd;
    }

    /**
     * Sets a new sumOvd
     *
     * Сумма общего лимита овердрафта в валюте счёта
     *
     * @param float $sumOvd
     * @return static
     */
    public function setSumOvd($sumOvd)
    {
        $this->sumOvd = $sumOvd;
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
     * Gets as addInfoSBK
     *
     * @return \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\AddInfoSBKAType
     */
    public function getAddInfoSBK()
    {
        return $this->addInfoSBK;
    }

    /**
     * Sets a new addInfoSBK
     *
     * @param \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\AddInfoSBKAType $addInfoSBK
     * @return static
     */
    public function setAddInfoSBK(\common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\AddInfoSBKAType $addInfoSBK)
    {
        $this->addInfoSBK = $addInfoSBK;
        return $this;
    }

    /**
     * Gets as compensatingProducts
     *
     * @return \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\CompensatingProductsAType
     */
    public function getCompensatingProducts()
    {
        return $this->compensatingProducts;
    }

    /**
     * Sets a new compensatingProducts
     *
     * @param \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\CompensatingProductsAType $compensatingProducts
     * @return static
     */
    public function setCompensatingProducts(\common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\CompensatingProductsAType $compensatingProducts)
    {
        $this->compensatingProducts = $compensatingProducts;
        return $this;
    }

    /**
     * Adds as creditContract
     *
     * Если счет является ссудным, дополнительно может передаваться информация по кредитным договорам.
     *
     * @return static
     * @param \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\CreditContractsAType\CreditContractAType $creditContract
     */
    public function addToCreditContracts(\common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\CreditContractsAType\CreditContractAType $creditContract)
    {
        $this->creditContracts[] = $creditContract;
        return $this;
    }

    /**
     * isset creditContracts
     *
     * Если счет является ссудным, дополнительно может передаваться информация по кредитным договорам.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCreditContracts($index)
    {
        return isset($this->creditContracts[$index]);
    }

    /**
     * unset creditContracts
     *
     * Если счет является ссудным, дополнительно может передаваться информация по кредитным договорам.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCreditContracts($index)
    {
        unset($this->creditContracts[$index]);
    }

    /**
     * Gets as creditContracts
     *
     * Если счет является ссудным, дополнительно может передаваться информация по кредитным договорам.
     *
     * @return \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\CreditContractsAType\CreditContractAType[]
     */
    public function getCreditContracts()
    {
        return $this->creditContracts;
    }

    /**
     * Sets a new creditContracts
     *
     * Если счет является ссудным, дополнительно может передаваться информация по кредитным договорам.
     *
     * @param \common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType\CreditContractsAType\CreditContractAType[] $creditContracts
     * @return static
     */
    public function setCreditContracts(array $creditContracts)
    {
        $this->creditContracts = $creditContracts;
        return $this;
    }

    /**
     * Gets as contractOfTheStateDefenseOrder
     *
     * Номер договора государственного оборонного заказа (ГОЗ)
     *
     * @return string
     */
    public function getContractOfTheStateDefenseOrder()
    {
        return $this->contractOfTheStateDefenseOrder;
    }

    /**
     * Sets a new contractOfTheStateDefenseOrder
     *
     * Номер договора государственного оборонного заказа (ГОЗ)
     *
     * @param string $contractOfTheStateDefenseOrder
     * @return static
     */
    public function setContractOfTheStateDefenseOrder($contractOfTheStateDefenseOrder)
    {
        $this->contractOfTheStateDefenseOrder = $contractOfTheStateDefenseOrder;
        return $this;
    }

    /**
     * Gets as adm
     *
     * Счет подключен к устройству
     *  самообслуживания на территории
     *  Клиента (АДМ)
     *
     * @return boolean
     */
    public function getAdm()
    {
        return $this->adm;
    }

    /**
     * Sets a new adm
     *
     * Счет подключен к устройству
     *  самообслуживания на территории
     *  Клиента (АДМ)
     *
     * @param boolean $adm
     * @return static
     */
    public function setAdm($adm)
    {
        $this->adm = $adm;
        return $this;
    }

    /**
     * Gets as selfEnCashment
     *
     * Счет подключен к устройству
     *  самообслуживания (УС)
     *
     * @return boolean
     */
    public function getSelfEnCashment()
    {
        return $this->selfEnCashment;
    }

    /**
     * Sets a new selfEnCashment
     *
     * Счет подключен к устройству
     *  самообслуживания (УС)
     *
     * @param boolean $selfEnCashment
     * @return static
     */
    public function setSelfEnCashment($selfEnCashment)
    {
        $this->selfEnCashment = $selfEnCashment;
        return $this;
    }


}

