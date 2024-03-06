<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ICSDataType
 *
 * Общие сведения о контракте/кредитном договоре
 * XSD Type: ICSData
 */
class ICSDataType
{

    /**
     * Признак наличия номера контракта/кредитного договора: 1-документ имеет номер, 0-документ без номера
     *
     * @property boolean $checkNum
     */
    private $checkNum = null;

    /**
     * Признак наличия суммы контракта/кредитного договора: 1-есть сумма, 0-нет суммы
     *
     * @property boolean $checkSum
     */
    private $checkSum = null;

    /**
     * Номер контракта/кредитного договора
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Дата контракта/кредитного договора
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Тип ВБК: OnContract - По Контракту, OnCredit - По кредитному договору
     *
     * @property string $iCSType
     */
    private $iCSType = null;

    /**
     * Режим создания ВБК (импорт/экспорт товаров и/или услуг, уступка)
     *  VbkConRegistration, VbkConInformation, VbkConLicenseLost, VbkConAnotherBank, VbkConAssignment,
     *  VbkCredRegistration, VbkCredLicenseLost, VbkCredAnotherBank, VbkCredAssignment
     *
     * @property string $icsCreationMode
     */
    private $icsCreationMode = null;

    /**
     * Код вида контракта, заполняемый для экспортных контрактов при представлении
     *  сведений по контракту без контракта (Режим создания ВБК - VbkConInformation)
     *
     * @property string $iCSContractTypeCode
     */
    private $iCSContractTypeCode = null;

    /**
     * Подтип документа "Ведомость банковского контроля".
     *  Возможные значения:
     *  SEND_DOCUMENTS - Досыл документов по контракту (кредитному договору)
     *  REGISTRATION - Постановка контракта (кредитного договора) на учет
     *  STATEMENT - ВБК по контракту (кредитному договору)
     *
     * @property string $iCSContractSubTypeCode
     */
    private $iCSContractSubTypeCode = null;

    /**
     * Валюта суммы контракта/кредитного договора
     *
     * @property \common\models\sbbolxml\request\CurrencyType $curr
     */
    private $curr = null;

    /**
     * Сумма контракта/кредитного договора
     *
     * @property \common\models\sbbolxml\request\ICSDataType\SumAType $sum
     */
    private $sum = null;

    /**
     * Дата завершения исполнения обязательств
     *
     * @property \DateTime $endDate
     */
    private $endDate = null;

    /**
     * Иные платежи, предусмотренные кредитным договором (за исключением платежей по
     *  возврату основного долга)
     *
     * @property string $otherPayments
     */
    private $otherPayments = null;

    /**
     * Уникальный номер контракта/кредитного договора, перешедшего резиденту по уступке
     *
     * @property string $assignedConBankNum
     */
    private $assignedConBankNum = null;

    /**
     * Дата постановки на учет
     *
     * @property \DateTime $registrationDate
     */
    private $registrationDate = null;

    /**
     * Дата снятия с учета
     *
     * @property \DateTime $closeDate
     */
    private $closeDate = null;

    /**
     * Регистрационный номер банка.
     *  Заполняется вручную в формате NNNN/YYNN,
     *  где N – цифра от 0 до 9, YY – цифры от 0 до 9 или буквы "GU" (или две цифры, или GU)
     *
     * @property string $regBankNum
     */
    private $regBankNum = null;

    /**
     * Особые условия
     *
     * @property \common\models\sbbolxml\request\SpecConditionsICSType $specConditions
     */
    private $specConditions = null;

    /**
     * Gets as checkNum
     *
     * Признак наличия номера контракта/кредитного договора: 1-документ имеет номер, 0-документ без номера
     *
     * @return boolean
     */
    public function getCheckNum()
    {
        return $this->checkNum;
    }

    /**
     * Sets a new checkNum
     *
     * Признак наличия номера контракта/кредитного договора: 1-документ имеет номер, 0-документ без номера
     *
     * @param boolean $checkNum
     * @return static
     */
    public function setCheckNum($checkNum)
    {
        $this->checkNum = $checkNum;
        return $this;
    }

    /**
     * Gets as checkSum
     *
     * Признак наличия суммы контракта/кредитного договора: 1-есть сумма, 0-нет суммы
     *
     * @return boolean
     */
    public function getCheckSum()
    {
        return $this->checkSum;
    }

    /**
     * Sets a new checkSum
     *
     * Признак наличия суммы контракта/кредитного договора: 1-есть сумма, 0-нет суммы
     *
     * @param boolean $checkSum
     * @return static
     */
    public function setCheckSum($checkSum)
    {
        $this->checkSum = $checkSum;
        return $this;
    }

    /**
     * Gets as num
     *
     * Номер контракта/кредитного договора
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Sets a new num
     *
     * Номер контракта/кредитного договора
     *
     * @param string $num
     * @return static
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата контракта/кредитного договора
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата контракта/кредитного договора
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as iCSType
     *
     * Тип ВБК: OnContract - По Контракту, OnCredit - По кредитному договору
     *
     * @return string
     */
    public function getICSType()
    {
        return $this->iCSType;
    }

    /**
     * Sets a new iCSType
     *
     * Тип ВБК: OnContract - По Контракту, OnCredit - По кредитному договору
     *
     * @param string $iCSType
     * @return static
     */
    public function setICSType($iCSType)
    {
        $this->iCSType = $iCSType;
        return $this;
    }

    /**
     * Gets as icsCreationMode
     *
     * Режим создания ВБК (импорт/экспорт товаров и/или услуг, уступка)
     *  VbkConRegistration, VbkConInformation, VbkConLicenseLost, VbkConAnotherBank, VbkConAssignment,
     *  VbkCredRegistration, VbkCredLicenseLost, VbkCredAnotherBank, VbkCredAssignment
     *
     * @return string
     */
    public function getIcsCreationMode()
    {
        return $this->icsCreationMode;
    }

    /**
     * Sets a new icsCreationMode
     *
     * Режим создания ВБК (импорт/экспорт товаров и/или услуг, уступка)
     *  VbkConRegistration, VbkConInformation, VbkConLicenseLost, VbkConAnotherBank, VbkConAssignment,
     *  VbkCredRegistration, VbkCredLicenseLost, VbkCredAnotherBank, VbkCredAssignment
     *
     * @param string $icsCreationMode
     * @return static
     */
    public function setIcsCreationMode($icsCreationMode)
    {
        $this->icsCreationMode = $icsCreationMode;
        return $this;
    }

    /**
     * Gets as iCSContractTypeCode
     *
     * Код вида контракта, заполняемый для экспортных контрактов при представлении
     *  сведений по контракту без контракта (Режим создания ВБК - VbkConInformation)
     *
     * @return string
     */
    public function getICSContractTypeCode()
    {
        return $this->iCSContractTypeCode;
    }

    /**
     * Sets a new iCSContractTypeCode
     *
     * Код вида контракта, заполняемый для экспортных контрактов при представлении
     *  сведений по контракту без контракта (Режим создания ВБК - VbkConInformation)
     *
     * @param string $iCSContractTypeCode
     * @return static
     */
    public function setICSContractTypeCode($iCSContractTypeCode)
    {
        $this->iCSContractTypeCode = $iCSContractTypeCode;
        return $this;
    }

    /**
     * Gets as iCSContractSubTypeCode
     *
     * Подтип документа "Ведомость банковского контроля".
     *  Возможные значения:
     *  SEND_DOCUMENTS - Досыл документов по контракту (кредитному договору)
     *  REGISTRATION - Постановка контракта (кредитного договора) на учет
     *  STATEMENT - ВБК по контракту (кредитному договору)
     *
     * @return string
     */
    public function getICSContractSubTypeCode()
    {
        return $this->iCSContractSubTypeCode;
    }

    /**
     * Sets a new iCSContractSubTypeCode
     *
     * Подтип документа "Ведомость банковского контроля".
     *  Возможные значения:
     *  SEND_DOCUMENTS - Досыл документов по контракту (кредитному договору)
     *  REGISTRATION - Постановка контракта (кредитного договора) на учет
     *  STATEMENT - ВБК по контракту (кредитному договору)
     *
     * @param string $iCSContractSubTypeCode
     * @return static
     */
    public function setICSContractSubTypeCode($iCSContractSubTypeCode)
    {
        $this->iCSContractSubTypeCode = $iCSContractSubTypeCode;
        return $this;
    }

    /**
     * Gets as curr
     *
     * Валюта суммы контракта/кредитного договора
     *
     * @return \common\models\sbbolxml\request\CurrencyType
     */
    public function getCurr()
    {
        return $this->curr;
    }

    /**
     * Sets a new curr
     *
     * Валюта суммы контракта/кредитного договора
     *
     * @param \common\models\sbbolxml\request\CurrencyType $curr
     * @return static
     */
    public function setCurr(\common\models\sbbolxml\request\CurrencyType $curr)
    {
        $this->curr = $curr;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма контракта/кредитного договора
     *
     * @return \common\models\sbbolxml\request\ICSDataType\SumAType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма контракта/кредитного договора
     *
     * @param \common\models\sbbolxml\request\ICSDataType\SumAType $sum
     * @return static
     */
    public function setSum(\common\models\sbbolxml\request\ICSDataType\SumAType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as endDate
     *
     * Дата завершения исполнения обязательств
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
     * Дата завершения исполнения обязательств
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
     * Gets as otherPayments
     *
     * Иные платежи, предусмотренные кредитным договором (за исключением платежей по
     *  возврату основного долга)
     *
     * @return string
     */
    public function getOtherPayments()
    {
        return $this->otherPayments;
    }

    /**
     * Sets a new otherPayments
     *
     * Иные платежи, предусмотренные кредитным договором (за исключением платежей по
     *  возврату основного долга)
     *
     * @param string $otherPayments
     * @return static
     */
    public function setOtherPayments($otherPayments)
    {
        $this->otherPayments = $otherPayments;
        return $this;
    }

    /**
     * Gets as assignedConBankNum
     *
     * Уникальный номер контракта/кредитного договора, перешедшего резиденту по уступке
     *
     * @return string
     */
    public function getAssignedConBankNum()
    {
        return $this->assignedConBankNum;
    }

    /**
     * Sets a new assignedConBankNum
     *
     * Уникальный номер контракта/кредитного договора, перешедшего резиденту по уступке
     *
     * @param string $assignedConBankNum
     * @return static
     */
    public function setAssignedConBankNum($assignedConBankNum)
    {
        $this->assignedConBankNum = $assignedConBankNum;
        return $this;
    }

    /**
     * Gets as registrationDate
     *
     * Дата постановки на учет
     *
     * @return \DateTime
     */
    public function getRegistrationDate()
    {
        return $this->registrationDate;
    }

    /**
     * Sets a new registrationDate
     *
     * Дата постановки на учет
     *
     * @param \DateTime $registrationDate
     * @return static
     */
    public function setRegistrationDate(\DateTime $registrationDate)
    {
        $this->registrationDate = $registrationDate;
        return $this;
    }

    /**
     * Gets as closeDate
     *
     * Дата снятия с учета
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
     * Дата снятия с учета
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
     * Gets as regBankNum
     *
     * Регистрационный номер банка.
     *  Заполняется вручную в формате NNNN/YYNN,
     *  где N – цифра от 0 до 9, YY – цифры от 0 до 9 или буквы "GU" (или две цифры, или GU)
     *
     * @return string
     */
    public function getRegBankNum()
    {
        return $this->regBankNum;
    }

    /**
     * Sets a new regBankNum
     *
     * Регистрационный номер банка.
     *  Заполняется вручную в формате NNNN/YYNN,
     *  где N – цифра от 0 до 9, YY – цифры от 0 до 9 или буквы "GU" (или две цифры, или GU)
     *
     * @param string $regBankNum
     * @return static
     */
    public function setRegBankNum($regBankNum)
    {
        $this->regBankNum = $regBankNum;
        return $this;
    }

    /**
     * Gets as specConditions
     *
     * Особые условия
     *
     * @return \common\models\sbbolxml\request\SpecConditionsICSType
     */
    public function getSpecConditions()
    {
        return $this->specConditions;
    }

    /**
     * Sets a new specConditions
     *
     * Особые условия
     *
     * @param \common\models\sbbolxml\request\SpecConditionsICSType $specConditions
     * @return static
     */
    public function setSpecConditions(\common\models\sbbolxml\request\SpecConditionsICSType $specConditions)
    {
        $this->specConditions = $specConditions;
        return $this;
    }


}

