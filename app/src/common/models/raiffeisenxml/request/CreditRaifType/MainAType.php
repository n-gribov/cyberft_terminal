<?php

namespace common\models\raiffeisenxml\request\CreditRaifType;

/**
 * Class representing MainAType
 */
class MainAType
{

    /**
     * Наименование документа. Возможные значения: "Заявление на кредит", "Заявление на предоставление Кредита", "Заявка на Предоставление Кредита".
     *
     * @property string $docName
     */
    private $docName = null;

    /**
     * Тип кредита. Возможные значения: "Кредит в рамках возобновляемой кредитной линии", "Кредит в рамках невозобновляемой кредитной линии", "Кредит с единовременной выдачей".
     *
     * @property string $creditType
     */
    private $creditType = null;

    /**
     * Реквизиты соглашения
     *
     * @property \common\models\raiffeisenxml\request\CreditRaifType\MainAType\AgreementAType $agreement
     */
    private $agreement = null;

    /**
     * Сумма кредита
     *
     * @property \common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditSumAType $creditSum
     */
    private $creditSum = null;

    /**
     * В соответствии с условиями Соглашения путем кредитования нашего счета.
     *
     * @property \common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditAccAType $creditAcc
     */
    private $creditAcc = null;

    /**
     * Дата предоставления кредита
     *
     * @property \DateTime $creditDate
     */
    private $creditDate = null;

    /**
     * Срок кредита.
     *
     * @property \common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditTermAType $creditTerm
     */
    private $creditTerm = null;

    /**
     * Gets as docName
     *
     * Наименование документа. Возможные значения: "Заявление на кредит", "Заявление на предоставление Кредита", "Заявка на Предоставление Кредита".
     *
     * @return string
     */
    public function getDocName()
    {
        return $this->docName;
    }

    /**
     * Sets a new docName
     *
     * Наименование документа. Возможные значения: "Заявление на кредит", "Заявление на предоставление Кредита", "Заявка на Предоставление Кредита".
     *
     * @param string $docName
     * @return static
     */
    public function setDocName($docName)
    {
        $this->docName = $docName;
        return $this;
    }

    /**
     * Gets as creditType
     *
     * Тип кредита. Возможные значения: "Кредит в рамках возобновляемой кредитной линии", "Кредит в рамках невозобновляемой кредитной линии", "Кредит с единовременной выдачей".
     *
     * @return string
     */
    public function getCreditType()
    {
        return $this->creditType;
    }

    /**
     * Sets a new creditType
     *
     * Тип кредита. Возможные значения: "Кредит в рамках возобновляемой кредитной линии", "Кредит в рамках невозобновляемой кредитной линии", "Кредит с единовременной выдачей".
     *
     * @param string $creditType
     * @return static
     */
    public function setCreditType($creditType)
    {
        $this->creditType = $creditType;
        return $this;
    }

    /**
     * Gets as agreement
     *
     * Реквизиты соглашения
     *
     * @return \common\models\raiffeisenxml\request\CreditRaifType\MainAType\AgreementAType
     */
    public function getAgreement()
    {
        return $this->agreement;
    }

    /**
     * Sets a new agreement
     *
     * Реквизиты соглашения
     *
     * @param \common\models\raiffeisenxml\request\CreditRaifType\MainAType\AgreementAType $agreement
     * @return static
     */
    public function setAgreement(\common\models\raiffeisenxml\request\CreditRaifType\MainAType\AgreementAType $agreement)
    {
        $this->agreement = $agreement;
        return $this;
    }

    /**
     * Gets as creditSum
     *
     * Сумма кредита
     *
     * @return \common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditSumAType
     */
    public function getCreditSum()
    {
        return $this->creditSum;
    }

    /**
     * Sets a new creditSum
     *
     * Сумма кредита
     *
     * @param \common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditSumAType $creditSum
     * @return static
     */
    public function setCreditSum(\common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditSumAType $creditSum)
    {
        $this->creditSum = $creditSum;
        return $this;
    }

    /**
     * Gets as creditAcc
     *
     * В соответствии с условиями Соглашения путем кредитования нашего счета.
     *
     * @return \common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditAccAType
     */
    public function getCreditAcc()
    {
        return $this->creditAcc;
    }

    /**
     * Sets a new creditAcc
     *
     * В соответствии с условиями Соглашения путем кредитования нашего счета.
     *
     * @param \common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditAccAType $creditAcc
     * @return static
     */
    public function setCreditAcc(\common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditAccAType $creditAcc)
    {
        $this->creditAcc = $creditAcc;
        return $this;
    }

    /**
     * Gets as creditDate
     *
     * Дата предоставления кредита
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
     * Дата предоставления кредита
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
     * Gets as creditTerm
     *
     * Срок кредита.
     *
     * @return \common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditTermAType
     */
    public function getCreditTerm()
    {
        return $this->creditTerm;
    }

    /**
     * Sets a new creditTerm
     *
     * Срок кредита.
     *
     * @param \common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditTermAType $creditTerm
     * @return static
     */
    public function setCreditTerm(\common\models\raiffeisenxml\request\CreditRaifType\MainAType\CreditTermAType $creditTerm)
    {
        $this->creditTerm = $creditTerm;
        return $this;
    }


}

