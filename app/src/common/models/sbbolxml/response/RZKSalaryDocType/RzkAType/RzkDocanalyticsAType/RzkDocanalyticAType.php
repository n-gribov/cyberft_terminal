<?php

namespace common\models\sbbolxml\response\RZKSalaryDocType\RzkAType\RzkDocanalyticsAType;

/**
 * Class representing RzkDocanalyticAType
 */
class RzkDocanalyticAType
{

    /**
     * Идентификатор записи многострочной аналитики
     *
     * @property string $fieldSetId
     */
    private $fieldSetId = null;

    /**
     * Номер строки Многострочной аналитики
     *
     * @property string $tableRow
     */
    private $tableRow = null;

    /**
     * Идентификатор документа (Платежного поручение), к которому относится строка многострочной аналитики
     *
     * @property string $docContentId
     */
    private $docContentId = null;

    /**
     * Сумма
     *
     * @property float $amount
     */
    private $amount = null;

    /**
     * Идентификатор структуры
     *
     * @property integer $budgetId
     */
    private $budgetId = null;

    /**
     * Идентификатор сметы доходов и расходов ЦО
     *
     * @property integer $classifier1Id
     */
    private $classifier1Id = null;

    /**
     * Код сметы доходов и расходов ЦО
     *
     * @property string $class1Code
     */
    private $class1Code = null;

    /**
     * Наименование сметы доходов и расходов ЦО
     *
     * @property string $class1Description
     */
    private $class1Description = null;

    /**
     * Название вида финансирования
     *
     * @property string $financeTypeDescription
     */
    private $financeTypeDescription = null;

    /**
     * Идентификатор вида финансирования в РЦК (СБК)
     *
     * @property integer $financeTypeId
     */
    private $financeTypeId = null;

    /**
     * Классификатор сметы
     *
     * @property integer $kesrCode
     */
    private $kesrCode = null;

    /**
     * Наименование сметы статей доходов и расходов
     *
     * @property string $kesrDescription
     */
    private $kesrDescription = null;

    /**
     * Наименование группы организаций
     *
     * @property string $orgGroupCaption
     */
    private $orgGroupCaption = null;

    /**
     * Идентификатор группы организаций/
     *
     * @property integer $orgGroupId
     */
    private $orgGroupId = null;

    /**
     * Дата договора
     *
     * @property \DateTime $pactDate
     */
    private $pactDate = null;

    /**
     * Идентификатор договора в РЦК (СБК)
     *
     * @property integer $pactId
     */
    private $pactId = null;

    /**
     * Номер договора
     *
     * @property string $pactNum
     */
    private $pactNum = null;

    /**
     * Наименование договора
     *
     * @property string $pactSubject
     */
    private $pactSubject = null;

    /**
     * Код вида расчетов
     *
     * @property string $payCodeCode
     */
    private $payCodeCode = null;

    /**
     * Название вида расчёта
     *
     * @property string $payCodeDescription
     */
    private $payCodeDescription = null;

    /**
     * Идентификатор вида расчёта в РЦК (СБК)
     *
     * @property integer $payCodeId
     */
    private $payCodeId = null;

    /**
     * Код проекта
     *
     * @property string $projectStdCode
     */
    private $projectStdCode = null;

    /**
     * Наименование проекта
     *
     * @property string $projectStdDescription
     */
    private $projectStdDescription = null;

    /**
     * Идентификатор проекта в РЦК (СБК)
     *
     * @property integer $projectStdId
     */
    private $projectStdId = null;

    /**
     * Дата документа основания платежа
     *
     * @property \DateTime $reasonDate
     */
    private $reasonDate = null;

    /**
     * Номер документа основания платежа
     *
     * @property string $reasonNum
     */
    private $reasonNum = null;

    /**
     * Вид документа основания платежа
     *
     * @property string $reasonType
     */
    private $reasonType = null;

    /**
     * Gets as fieldSetId
     *
     * Идентификатор записи многострочной аналитики
     *
     * @return string
     */
    public function getFieldSetId()
    {
        return $this->fieldSetId;
    }

    /**
     * Sets a new fieldSetId
     *
     * Идентификатор записи многострочной аналитики
     *
     * @param string $fieldSetId
     * @return static
     */
    public function setFieldSetId($fieldSetId)
    {
        $this->fieldSetId = $fieldSetId;
        return $this;
    }

    /**
     * Gets as tableRow
     *
     * Номер строки Многострочной аналитики
     *
     * @return string
     */
    public function getTableRow()
    {
        return $this->tableRow;
    }

    /**
     * Sets a new tableRow
     *
     * Номер строки Многострочной аналитики
     *
     * @param string $tableRow
     * @return static
     */
    public function setTableRow($tableRow)
    {
        $this->tableRow = $tableRow;
        return $this;
    }

    /**
     * Gets as docContentId
     *
     * Идентификатор документа (Платежного поручение), к которому относится строка многострочной аналитики
     *
     * @return string
     */
    public function getDocContentId()
    {
        return $this->docContentId;
    }

    /**
     * Sets a new docContentId
     *
     * Идентификатор документа (Платежного поручение), к которому относится строка многострочной аналитики
     *
     * @param string $docContentId
     * @return static
     */
    public function setDocContentId($docContentId)
    {
        $this->docContentId = $docContentId;
        return $this;
    }

    /**
     * Gets as amount
     *
     * Сумма
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Sets a new amount
     *
     * Сумма
     *
     * @param float $amount
     * @return static
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * Gets as budgetId
     *
     * Идентификатор структуры
     *
     * @return integer
     */
    public function getBudgetId()
    {
        return $this->budgetId;
    }

    /**
     * Sets a new budgetId
     *
     * Идентификатор структуры
     *
     * @param integer $budgetId
     * @return static
     */
    public function setBudgetId($budgetId)
    {
        $this->budgetId = $budgetId;
        return $this;
    }

    /**
     * Gets as classifier1Id
     *
     * Идентификатор сметы доходов и расходов ЦО
     *
     * @return integer
     */
    public function getClassifier1Id()
    {
        return $this->classifier1Id;
    }

    /**
     * Sets a new classifier1Id
     *
     * Идентификатор сметы доходов и расходов ЦО
     *
     * @param integer $classifier1Id
     * @return static
     */
    public function setClassifier1Id($classifier1Id)
    {
        $this->classifier1Id = $classifier1Id;
        return $this;
    }

    /**
     * Gets as class1Code
     *
     * Код сметы доходов и расходов ЦО
     *
     * @return string
     */
    public function getClass1Code()
    {
        return $this->class1Code;
    }

    /**
     * Sets a new class1Code
     *
     * Код сметы доходов и расходов ЦО
     *
     * @param string $class1Code
     * @return static
     */
    public function setClass1Code($class1Code)
    {
        $this->class1Code = $class1Code;
        return $this;
    }

    /**
     * Gets as class1Description
     *
     * Наименование сметы доходов и расходов ЦО
     *
     * @return string
     */
    public function getClass1Description()
    {
        return $this->class1Description;
    }

    /**
     * Sets a new class1Description
     *
     * Наименование сметы доходов и расходов ЦО
     *
     * @param string $class1Description
     * @return static
     */
    public function setClass1Description($class1Description)
    {
        $this->class1Description = $class1Description;
        return $this;
    }

    /**
     * Gets as financeTypeDescription
     *
     * Название вида финансирования
     *
     * @return string
     */
    public function getFinanceTypeDescription()
    {
        return $this->financeTypeDescription;
    }

    /**
     * Sets a new financeTypeDescription
     *
     * Название вида финансирования
     *
     * @param string $financeTypeDescription
     * @return static
     */
    public function setFinanceTypeDescription($financeTypeDescription)
    {
        $this->financeTypeDescription = $financeTypeDescription;
        return $this;
    }

    /**
     * Gets as financeTypeId
     *
     * Идентификатор вида финансирования в РЦК (СБК)
     *
     * @return integer
     */
    public function getFinanceTypeId()
    {
        return $this->financeTypeId;
    }

    /**
     * Sets a new financeTypeId
     *
     * Идентификатор вида финансирования в РЦК (СБК)
     *
     * @param integer $financeTypeId
     * @return static
     */
    public function setFinanceTypeId($financeTypeId)
    {
        $this->financeTypeId = $financeTypeId;
        return $this;
    }

    /**
     * Gets as kesrCode
     *
     * Классификатор сметы
     *
     * @return integer
     */
    public function getKesrCode()
    {
        return $this->kesrCode;
    }

    /**
     * Sets a new kesrCode
     *
     * Классификатор сметы
     *
     * @param integer $kesrCode
     * @return static
     */
    public function setKesrCode($kesrCode)
    {
        $this->kesrCode = $kesrCode;
        return $this;
    }

    /**
     * Gets as kesrDescription
     *
     * Наименование сметы статей доходов и расходов
     *
     * @return string
     */
    public function getKesrDescription()
    {
        return $this->kesrDescription;
    }

    /**
     * Sets a new kesrDescription
     *
     * Наименование сметы статей доходов и расходов
     *
     * @param string $kesrDescription
     * @return static
     */
    public function setKesrDescription($kesrDescription)
    {
        $this->kesrDescription = $kesrDescription;
        return $this;
    }

    /**
     * Gets as orgGroupCaption
     *
     * Наименование группы организаций
     *
     * @return string
     */
    public function getOrgGroupCaption()
    {
        return $this->orgGroupCaption;
    }

    /**
     * Sets a new orgGroupCaption
     *
     * Наименование группы организаций
     *
     * @param string $orgGroupCaption
     * @return static
     */
    public function setOrgGroupCaption($orgGroupCaption)
    {
        $this->orgGroupCaption = $orgGroupCaption;
        return $this;
    }

    /**
     * Gets as orgGroupId
     *
     * Идентификатор группы организаций/
     *
     * @return integer
     */
    public function getOrgGroupId()
    {
        return $this->orgGroupId;
    }

    /**
     * Sets a new orgGroupId
     *
     * Идентификатор группы организаций/
     *
     * @param integer $orgGroupId
     * @return static
     */
    public function setOrgGroupId($orgGroupId)
    {
        $this->orgGroupId = $orgGroupId;
        return $this;
    }

    /**
     * Gets as pactDate
     *
     * Дата договора
     *
     * @return \DateTime
     */
    public function getPactDate()
    {
        return $this->pactDate;
    }

    /**
     * Sets a new pactDate
     *
     * Дата договора
     *
     * @param \DateTime $pactDate
     * @return static
     */
    public function setPactDate(\DateTime $pactDate)
    {
        $this->pactDate = $pactDate;
        return $this;
    }

    /**
     * Gets as pactId
     *
     * Идентификатор договора в РЦК (СБК)
     *
     * @return integer
     */
    public function getPactId()
    {
        return $this->pactId;
    }

    /**
     * Sets a new pactId
     *
     * Идентификатор договора в РЦК (СБК)
     *
     * @param integer $pactId
     * @return static
     */
    public function setPactId($pactId)
    {
        $this->pactId = $pactId;
        return $this;
    }

    /**
     * Gets as pactNum
     *
     * Номер договора
     *
     * @return string
     */
    public function getPactNum()
    {
        return $this->pactNum;
    }

    /**
     * Sets a new pactNum
     *
     * Номер договора
     *
     * @param string $pactNum
     * @return static
     */
    public function setPactNum($pactNum)
    {
        $this->pactNum = $pactNum;
        return $this;
    }

    /**
     * Gets as pactSubject
     *
     * Наименование договора
     *
     * @return string
     */
    public function getPactSubject()
    {
        return $this->pactSubject;
    }

    /**
     * Sets a new pactSubject
     *
     * Наименование договора
     *
     * @param string $pactSubject
     * @return static
     */
    public function setPactSubject($pactSubject)
    {
        $this->pactSubject = $pactSubject;
        return $this;
    }

    /**
     * Gets as payCodeCode
     *
     * Код вида расчетов
     *
     * @return string
     */
    public function getPayCodeCode()
    {
        return $this->payCodeCode;
    }

    /**
     * Sets a new payCodeCode
     *
     * Код вида расчетов
     *
     * @param string $payCodeCode
     * @return static
     */
    public function setPayCodeCode($payCodeCode)
    {
        $this->payCodeCode = $payCodeCode;
        return $this;
    }

    /**
     * Gets as payCodeDescription
     *
     * Название вида расчёта
     *
     * @return string
     */
    public function getPayCodeDescription()
    {
        return $this->payCodeDescription;
    }

    /**
     * Sets a new payCodeDescription
     *
     * Название вида расчёта
     *
     * @param string $payCodeDescription
     * @return static
     */
    public function setPayCodeDescription($payCodeDescription)
    {
        $this->payCodeDescription = $payCodeDescription;
        return $this;
    }

    /**
     * Gets as payCodeId
     *
     * Идентификатор вида расчёта в РЦК (СБК)
     *
     * @return integer
     */
    public function getPayCodeId()
    {
        return $this->payCodeId;
    }

    /**
     * Sets a new payCodeId
     *
     * Идентификатор вида расчёта в РЦК (СБК)
     *
     * @param integer $payCodeId
     * @return static
     */
    public function setPayCodeId($payCodeId)
    {
        $this->payCodeId = $payCodeId;
        return $this;
    }

    /**
     * Gets as projectStdCode
     *
     * Код проекта
     *
     * @return string
     */
    public function getProjectStdCode()
    {
        return $this->projectStdCode;
    }

    /**
     * Sets a new projectStdCode
     *
     * Код проекта
     *
     * @param string $projectStdCode
     * @return static
     */
    public function setProjectStdCode($projectStdCode)
    {
        $this->projectStdCode = $projectStdCode;
        return $this;
    }

    /**
     * Gets as projectStdDescription
     *
     * Наименование проекта
     *
     * @return string
     */
    public function getProjectStdDescription()
    {
        return $this->projectStdDescription;
    }

    /**
     * Sets a new projectStdDescription
     *
     * Наименование проекта
     *
     * @param string $projectStdDescription
     * @return static
     */
    public function setProjectStdDescription($projectStdDescription)
    {
        $this->projectStdDescription = $projectStdDescription;
        return $this;
    }

    /**
     * Gets as projectStdId
     *
     * Идентификатор проекта в РЦК (СБК)
     *
     * @return integer
     */
    public function getProjectStdId()
    {
        return $this->projectStdId;
    }

    /**
     * Sets a new projectStdId
     *
     * Идентификатор проекта в РЦК (СБК)
     *
     * @param integer $projectStdId
     * @return static
     */
    public function setProjectStdId($projectStdId)
    {
        $this->projectStdId = $projectStdId;
        return $this;
    }

    /**
     * Gets as reasonDate
     *
     * Дата документа основания платежа
     *
     * @return \DateTime
     */
    public function getReasonDate()
    {
        return $this->reasonDate;
    }

    /**
     * Sets a new reasonDate
     *
     * Дата документа основания платежа
     *
     * @param \DateTime $reasonDate
     * @return static
     */
    public function setReasonDate(\DateTime $reasonDate)
    {
        $this->reasonDate = $reasonDate;
        return $this;
    }

    /**
     * Gets as reasonNum
     *
     * Номер документа основания платежа
     *
     * @return string
     */
    public function getReasonNum()
    {
        return $this->reasonNum;
    }

    /**
     * Sets a new reasonNum
     *
     * Номер документа основания платежа
     *
     * @param string $reasonNum
     * @return static
     */
    public function setReasonNum($reasonNum)
    {
        $this->reasonNum = $reasonNum;
        return $this;
    }

    /**
     * Gets as reasonType
     *
     * Вид документа основания платежа
     *
     * @return string
     */
    public function getReasonType()
    {
        return $this->reasonType;
    }

    /**
     * Sets a new reasonType
     *
     * Вид документа основания платежа
     *
     * @param string $reasonType
     * @return static
     */
    public function setReasonType($reasonType)
    {
        $this->reasonType = $reasonType;
        return $this;
    }


}

