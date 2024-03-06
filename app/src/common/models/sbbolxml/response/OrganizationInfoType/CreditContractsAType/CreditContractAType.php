<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType;

/**
 * Class representing CreditContractAType
 */
class CreditContractAType
{

    /**
     * Идентификатор кредитного договора
     *
     * @property string $credContrtId
     */
    private $credContrtId = null;

    /**
     * Идентиифкатор организации в СББОЛ
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Идентификатор подразделения банка
     *
     * @property string $branchId
     */
    private $branchId = null;

    /**
     * Номер кредитного договора
     *
     * @property string $contractNum
     */
    private $contractNum = null;

    /**
     * Наименование кредитного договора
     *
     * @property string $contractName
     */
    private $contractName = null;

    /**
     * Дата заключения договора
     *
     * @property \DateTime $contractDate
     */
    private $contractDate = null;

    /**
     * ID кредитующего подразделения
     *
     * @property string $creditDeptID
     */
    private $creditDeptID = null;

    /**
     * Наименование кредитующего подразделения
     *
     * @property string $creditDeptName
     */
    private $creditDeptName = null;

    /**
     * Срок пользования кредитными средствами (кол-во дней)
     *
     * @property integer $creditDuration
     */
    private $creditDuration = null;

    /**
     * Сумма кредита по договору на момент заключения договора
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $contractSum
     */
    private $contractSum = null;

    /**
     * Доступная сумма по кредиту
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $availableSum
     */
    private $availableSum = null;

    /**
     * Валюта кредитного договора
     *
     * @property string $contractCurr
     */
    private $contractCurr = null;

    /**
     * Процентная ставка по договору (актуальная)
     *
     * @property float $contractRate
     */
    private $contractRate = null;

    /**
     * Статус договора:
     *  0 - закрыт
     *  1 - открыт.
     *  По умолчанию 1.
     *
     * @property boolean $contractStatus
     */
    private $contractStatus = null;

    /**
     * Дата предоставления кредитных средств
     *
     * @property \DateTime $creditDate
     */
    private $creditDate = null;

    /**
     * @property \common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType\CreditContractAType\AvailableAccsAType[] $availableAccs
     */
    private $availableAccs = array(
        
    );

    /**
     * Дата окончания периода доступности лимитных средств
     *
     * @property \DateTime $limitDate
     */
    private $limitDate = null;

    /**
     * Код валюты выдачи
     *
     * @property string $creditCurr
     */
    private $creditCurr = null;

    /**
     * Дата окончания договора
     *
     * @property \DateTime $finishDate
     */
    private $finishDate = null;

    /**
     * Ссудная задолженность
     *
     * @property \common\models\sbbolxml\response\CurrAmountType $debtSum
     */
    private $debtSum = null;

    /**
     * Gets as credContrtId
     *
     * Идентификатор кредитного договора
     *
     * @return string
     */
    public function getCredContrtId()
    {
        return $this->credContrtId;
    }

    /**
     * Sets a new credContrtId
     *
     * Идентификатор кредитного договора
     *
     * @param string $credContrtId
     * @return static
     */
    public function setCredContrtId($credContrtId)
    {
        $this->credContrtId = $credContrtId;
        return $this;
    }

    /**
     * Gets as orgId
     *
     * Идентиифкатор организации в СББОЛ
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
     * Идентиифкатор организации в СББОЛ
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
     * Gets as branchId
     *
     * Идентификатор подразделения банка
     *
     * @return string
     */
    public function getBranchId()
    {
        return $this->branchId;
    }

    /**
     * Sets a new branchId
     *
     * Идентификатор подразделения банка
     *
     * @param string $branchId
     * @return static
     */
    public function setBranchId($branchId)
    {
        $this->branchId = $branchId;
        return $this;
    }

    /**
     * Gets as contractNum
     *
     * Номер кредитного договора
     *
     * @return string
     */
    public function getContractNum()
    {
        return $this->contractNum;
    }

    /**
     * Sets a new contractNum
     *
     * Номер кредитного договора
     *
     * @param string $contractNum
     * @return static
     */
    public function setContractNum($contractNum)
    {
        $this->contractNum = $contractNum;
        return $this;
    }

    /**
     * Gets as contractName
     *
     * Наименование кредитного договора
     *
     * @return string
     */
    public function getContractName()
    {
        return $this->contractName;
    }

    /**
     * Sets a new contractName
     *
     * Наименование кредитного договора
     *
     * @param string $contractName
     * @return static
     */
    public function setContractName($contractName)
    {
        $this->contractName = $contractName;
        return $this;
    }

    /**
     * Gets as contractDate
     *
     * Дата заключения договора
     *
     * @return \DateTime
     */
    public function getContractDate()
    {
        return $this->contractDate;
    }

    /**
     * Sets a new contractDate
     *
     * Дата заключения договора
     *
     * @param \DateTime $contractDate
     * @return static
     */
    public function setContractDate(\DateTime $contractDate)
    {
        $this->contractDate = $contractDate;
        return $this;
    }

    /**
     * Gets as creditDeptID
     *
     * ID кредитующего подразделения
     *
     * @return string
     */
    public function getCreditDeptID()
    {
        return $this->creditDeptID;
    }

    /**
     * Sets a new creditDeptID
     *
     * ID кредитующего подразделения
     *
     * @param string $creditDeptID
     * @return static
     */
    public function setCreditDeptID($creditDeptID)
    {
        $this->creditDeptID = $creditDeptID;
        return $this;
    }

    /**
     * Gets as creditDeptName
     *
     * Наименование кредитующего подразделения
     *
     * @return string
     */
    public function getCreditDeptName()
    {
        return $this->creditDeptName;
    }

    /**
     * Sets a new creditDeptName
     *
     * Наименование кредитующего подразделения
     *
     * @param string $creditDeptName
     * @return static
     */
    public function setCreditDeptName($creditDeptName)
    {
        $this->creditDeptName = $creditDeptName;
        return $this;
    }

    /**
     * Gets as creditDuration
     *
     * Срок пользования кредитными средствами (кол-во дней)
     *
     * @return integer
     */
    public function getCreditDuration()
    {
        return $this->creditDuration;
    }

    /**
     * Sets a new creditDuration
     *
     * Срок пользования кредитными средствами (кол-во дней)
     *
     * @param integer $creditDuration
     * @return static
     */
    public function setCreditDuration($creditDuration)
    {
        $this->creditDuration = $creditDuration;
        return $this;
    }

    /**
     * Gets as contractSum
     *
     * Сумма кредита по договору на момент заключения договора
     *
     * @return \common\models\sbbolxml\response\CurrAmountType
     */
    public function getContractSum()
    {
        return $this->contractSum;
    }

    /**
     * Sets a new contractSum
     *
     * Сумма кредита по договору на момент заключения договора
     *
     * @param \common\models\sbbolxml\response\CurrAmountType $contractSum
     * @return static
     */
    public function setContractSum(\common\models\sbbolxml\response\CurrAmountType $contractSum)
    {
        $this->contractSum = $contractSum;
        return $this;
    }

    /**
     * Gets as availableSum
     *
     * Доступная сумма по кредиту
     *
     * @return \common\models\sbbolxml\response\CurrAmountType
     */
    public function getAvailableSum()
    {
        return $this->availableSum;
    }

    /**
     * Sets a new availableSum
     *
     * Доступная сумма по кредиту
     *
     * @param \common\models\sbbolxml\response\CurrAmountType $availableSum
     * @return static
     */
    public function setAvailableSum(\common\models\sbbolxml\response\CurrAmountType $availableSum)
    {
        $this->availableSum = $availableSum;
        return $this;
    }

    /**
     * Gets as contractCurr
     *
     * Валюта кредитного договора
     *
     * @return string
     */
    public function getContractCurr()
    {
        return $this->contractCurr;
    }

    /**
     * Sets a new contractCurr
     *
     * Валюта кредитного договора
     *
     * @param string $contractCurr
     * @return static
     */
    public function setContractCurr($contractCurr)
    {
        $this->contractCurr = $contractCurr;
        return $this;
    }

    /**
     * Gets as contractRate
     *
     * Процентная ставка по договору (актуальная)
     *
     * @return float
     */
    public function getContractRate()
    {
        return $this->contractRate;
    }

    /**
     * Sets a new contractRate
     *
     * Процентная ставка по договору (актуальная)
     *
     * @param float $contractRate
     * @return static
     */
    public function setContractRate($contractRate)
    {
        $this->contractRate = $contractRate;
        return $this;
    }

    /**
     * Gets as contractStatus
     *
     * Статус договора:
     *  0 - закрыт
     *  1 - открыт.
     *  По умолчанию 1.
     *
     * @return boolean
     */
    public function getContractStatus()
    {
        return $this->contractStatus;
    }

    /**
     * Sets a new contractStatus
     *
     * Статус договора:
     *  0 - закрыт
     *  1 - открыт.
     *  По умолчанию 1.
     *
     * @param boolean $contractStatus
     * @return static
     */
    public function setContractStatus($contractStatus)
    {
        $this->contractStatus = $contractStatus;
        return $this;
    }

    /**
     * Gets as creditDate
     *
     * Дата предоставления кредитных средств
     *
     * @return \DateTime
     */
    public function getCreditDate()
    {
        return $this->creditDate;
    }

    /**
     * Sets a new creditDate
     *
     * Дата предоставления кредитных средств
     *
     * @param \DateTime $creditDate
     * @return static
     */
    public function setCreditDate(\DateTime $creditDate)
    {
        $this->creditDate = $creditDate;
        return $this;
    }

    /**
     * Adds as availableAccs
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType\CreditContractAType\AvailableAccsAType $availableAccs
     */
    public function addToAvailableAccs(\common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType\CreditContractAType\AvailableAccsAType $availableAccs)
    {
        $this->availableAccs[] = $availableAccs;
        return $this;
    }

    /**
     * isset availableAccs
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAvailableAccs($index)
    {
        return isset($this->availableAccs[$index]);
    }

    /**
     * unset availableAccs
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAvailableAccs($index)
    {
        unset($this->availableAccs[$index]);
    }

    /**
     * Gets as availableAccs
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType\CreditContractAType\AvailableAccsAType[]
     */
    public function getAvailableAccs()
    {
        return $this->availableAccs;
    }

    /**
     * Sets a new availableAccs
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\CreditContractsAType\CreditContractAType\AvailableAccsAType[] $availableAccs
     * @return static
     */
    public function setAvailableAccs(array $availableAccs)
    {
        $this->availableAccs = $availableAccs;
        return $this;
    }

    /**
     * Gets as limitDate
     *
     * Дата окончания периода доступности лимитных средств
     *
     * @return \DateTime
     */
    public function getLimitDate()
    {
        return $this->limitDate;
    }

    /**
     * Sets a new limitDate
     *
     * Дата окончания периода доступности лимитных средств
     *
     * @param \DateTime $limitDate
     * @return static
     */
    public function setLimitDate(\DateTime $limitDate)
    {
        $this->limitDate = $limitDate;
        return $this;
    }

    /**
     * Gets as creditCurr
     *
     * Код валюты выдачи
     *
     * @return string
     */
    public function getCreditCurr()
    {
        return $this->creditCurr;
    }

    /**
     * Sets a new creditCurr
     *
     * Код валюты выдачи
     *
     * @param string $creditCurr
     * @return static
     */
    public function setCreditCurr($creditCurr)
    {
        $this->creditCurr = $creditCurr;
        return $this;
    }

    /**
     * Gets as finishDate
     *
     * Дата окончания договора
     *
     * @return \DateTime
     */
    public function getFinishDate()
    {
        return $this->finishDate;
    }

    /**
     * Sets a new finishDate
     *
     * Дата окончания договора
     *
     * @param \DateTime $finishDate
     * @return static
     */
    public function setFinishDate(\DateTime $finishDate)
    {
        $this->finishDate = $finishDate;
        return $this;
    }

    /**
     * Gets as debtSum
     *
     * Ссудная задолженность
     *
     * @return \common\models\sbbolxml\response\CurrAmountType
     */
    public function getDebtSum()
    {
        return $this->debtSum;
    }

    /**
     * Sets a new debtSum
     *
     * Ссудная задолженность
     *
     * @param \common\models\sbbolxml\response\CurrAmountType $debtSum
     * @return static
     */
    public function setDebtSum(\common\models\sbbolxml\response\CurrAmountType $debtSum)
    {
        $this->debtSum = $debtSum;
        return $this;
    }


}

