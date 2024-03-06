<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CreditOrderType
 *
 *
 * XSD Type: CreditOrder
 */
class CreditOrderType extends DocBaseType
{

    /**
     * Общие реквизиты документа
     *
     * @property \common\models\sbbolxml\request\CreditOrderType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Полное наименование организации, берется из СББОЛа
     *
     * @property string $orgName
     */
    private $orgName = null;

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
     * Сумма кредита (запрашиваемая сумма)
     *
     * @property \common\models\sbbolxml\request\CurrAmountLetterType $creditSum
     */
    private $creditSum = null;

    /**
     * Номер расчетного счета для перечисления кредитных средств
     *
     * @property string $settlementAcc
     */
    private $settlementAcc = null;

    /**
     * Номер ссудного счета, привязанного к кредитному договору
     *
     * @property string $creditAcc
     */
    private $creditAcc = null;

    /**
     * Срок пользования кредитными средствами, кол-во дней
     *
     * @property integer $creditDuration
     */
    private $creditDuration = null;

    /**
     * Должность уполномоченного на распоряжение кредитными средствами лица организации
     *  (заемщика)
     *
     * @property string $executorPos
     */
    private $executorPos = null;

    /**
     * ФИО уполномоченного на распоряжение кредитными средствами лица организации
     *  (заемщика
     *
     * @property string $executorName
     */
    private $executorName = null;

    /**
     * ФИО главного бухгалтера
     *
     * @property string $accounterName
     */
    private $accounterName = null;

    /**
     * Дата предоставления кредитных средств
     *
     * @property \DateTime $creditDate
     */
    private $creditDate = null;

    /**
     * ID кредитующего подразделения (Передается из АБС в составе поле Кредитного договора)
     *
     * @property string $creditDeptID
     */
    private $creditDeptID = null;

    /**
     * Дата окончания договора
     *
     * @property \DateTime $contractEndDate
     */
    private $contractEndDate = null;

    /**
     * Дата окончания периода доступности лимитных средств
     *
     * @property \DateTime $sumAvailabilityEnd
     */
    private $sumAvailabilityEnd = null;

    /**
     * Доступная сумма
     *
     * @property \common\models\sbbolxml\request\CurrAmountLetterType $sumRest
     */
    private $sumRest = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты документа
     *
     * @return \common\models\sbbolxml\request\CreditOrderType\DocDataAType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа
     *
     * @param \common\models\sbbolxml\request\CreditOrderType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\CreditOrderType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as orgName
     *
     * Полное наименование организации, берется из СББОЛа
     *
     * @return string
     */
    public function getOrgName()
    {
        return $this->orgName;
    }

    /**
     * Sets a new orgName
     *
     * Полное наименование организации, берется из СББОЛа
     *
     * @param string $orgName
     * @return static
     */
    public function setOrgName($orgName)
    {
        $this->orgName = $orgName;
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
     * Gets as creditSum
     *
     * Сумма кредита (запрашиваемая сумма)
     *
     * @return \common\models\sbbolxml\request\CurrAmountLetterType
     */
    public function getCreditSum()
    {
        return $this->creditSum;
    }

    /**
     * Sets a new creditSum
     *
     * Сумма кредита (запрашиваемая сумма)
     *
     * @param \common\models\sbbolxml\request\CurrAmountLetterType $creditSum
     * @return static
     */
    public function setCreditSum(\common\models\sbbolxml\request\CurrAmountLetterType $creditSum)
    {
        $this->creditSum = $creditSum;
        return $this;
    }

    /**
     * Gets as settlementAcc
     *
     * Номер расчетного счета для перечисления кредитных средств
     *
     * @return string
     */
    public function getSettlementAcc()
    {
        return $this->settlementAcc;
    }

    /**
     * Sets a new settlementAcc
     *
     * Номер расчетного счета для перечисления кредитных средств
     *
     * @param string $settlementAcc
     * @return static
     */
    public function setSettlementAcc($settlementAcc)
    {
        $this->settlementAcc = $settlementAcc;
        return $this;
    }

    /**
     * Gets as creditAcc
     *
     * Номер ссудного счета, привязанного к кредитному договору
     *
     * @return string
     */
    public function getCreditAcc()
    {
        return $this->creditAcc;
    }

    /**
     * Sets a new creditAcc
     *
     * Номер ссудного счета, привязанного к кредитному договору
     *
     * @param string $creditAcc
     * @return static
     */
    public function setCreditAcc($creditAcc)
    {
        $this->creditAcc = $creditAcc;
        return $this;
    }

    /**
     * Gets as creditDuration
     *
     * Срок пользования кредитными средствами, кол-во дней
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
     * Срок пользования кредитными средствами, кол-во дней
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
     * Gets as executorPos
     *
     * Должность уполномоченного на распоряжение кредитными средствами лица организации
     *  (заемщика)
     *
     * @return string
     */
    public function getExecutorPos()
    {
        return $this->executorPos;
    }

    /**
     * Sets a new executorPos
     *
     * Должность уполномоченного на распоряжение кредитными средствами лица организации
     *  (заемщика)
     *
     * @param string $executorPos
     * @return static
     */
    public function setExecutorPos($executorPos)
    {
        $this->executorPos = $executorPos;
        return $this;
    }

    /**
     * Gets as executorName
     *
     * ФИО уполномоченного на распоряжение кредитными средствами лица организации
     *  (заемщика
     *
     * @return string
     */
    public function getExecutorName()
    {
        return $this->executorName;
    }

    /**
     * Sets a new executorName
     *
     * ФИО уполномоченного на распоряжение кредитными средствами лица организации
     *  (заемщика
     *
     * @param string $executorName
     * @return static
     */
    public function setExecutorName($executorName)
    {
        $this->executorName = $executorName;
        return $this;
    }

    /**
     * Gets as accounterName
     *
     * ФИО главного бухгалтера
     *
     * @return string
     */
    public function getAccounterName()
    {
        return $this->accounterName;
    }

    /**
     * Sets a new accounterName
     *
     * ФИО главного бухгалтера
     *
     * @param string $accounterName
     * @return static
     */
    public function setAccounterName($accounterName)
    {
        $this->accounterName = $accounterName;
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
     * Gets as creditDeptID
     *
     * ID кредитующего подразделения (Передается из АБС в составе поле Кредитного договора)
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
     * ID кредитующего подразделения (Передается из АБС в составе поле Кредитного договора)
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
     * Gets as contractEndDate
     *
     * Дата окончания договора
     *
     * @return \DateTime
     */
    public function getContractEndDate()
    {
        return $this->contractEndDate;
    }

    /**
     * Sets a new contractEndDate
     *
     * Дата окончания договора
     *
     * @param \DateTime $contractEndDate
     * @return static
     */
    public function setContractEndDate(\DateTime $contractEndDate)
    {
        $this->contractEndDate = $contractEndDate;
        return $this;
    }

    /**
     * Gets as sumAvailabilityEnd
     *
     * Дата окончания периода доступности лимитных средств
     *
     * @return \DateTime
     */
    public function getSumAvailabilityEnd()
    {
        return $this->sumAvailabilityEnd;
    }

    /**
     * Sets a new sumAvailabilityEnd
     *
     * Дата окончания периода доступности лимитных средств
     *
     * @param \DateTime $sumAvailabilityEnd
     * @return static
     */
    public function setSumAvailabilityEnd(\DateTime $sumAvailabilityEnd)
    {
        $this->sumAvailabilityEnd = $sumAvailabilityEnd;
        return $this;
    }

    /**
     * Gets as sumRest
     *
     * Доступная сумма
     *
     * @return \common\models\sbbolxml\request\CurrAmountLetterType
     */
    public function getSumRest()
    {
        return $this->sumRest;
    }

    /**
     * Sets a new sumRest
     *
     * Доступная сумма
     *
     * @param \common\models\sbbolxml\request\CurrAmountLetterType $sumRest
     * @return static
     */
    public function setSumRest(\common\models\sbbolxml\request\CurrAmountLetterType $sumRest)
    {
        $this->sumRest = $sumRest;
        return $this;
    }


}

