<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing AccreditiveCurRaifType
 *
 *
 * XSD Type: AccreditiveCurRaif
 */
class AccreditiveCurRaifType
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
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocDataAType $docData
     */
    private $docData = null;

    /**
     * Основное
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType $main
     */
    private $main = null;

    /**
     * Приказодатель
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\ApplicantAType $applicant
     */
    private $applicant = null;

    /**
     * Бенефициар
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\BeneficiaryAType $beneficiary
     */
    private $beneficiary = null;

    /**
     * Банк бенефициара
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\BenefBankAType $benefBank
     */
    private $benefBank = null;

    /**
     * Сумма аккредитива
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType $accrSum
     */
    private $accrSum = null;

    /**
     * Прочие сведения
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\OtherAType $other
     */
    private $other = null;

    /**
     * Отгрузка
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\ShippingAType $shipping
     */
    private $shipping = null;

    /**
     * Товар
     *
     * @property string $goods
     */
    private $goods = null;

    /**
     * Условия
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\TermsAType $terms
     */
    private $terms = null;

    /**
     * Документы
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocumentsAType $documents
     */
    private $documents = null;

    /**
     * Дополнительные условия.
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AddTermsAType $addTerms
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
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocDataAType
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
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocDataAType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocDataAType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as main
     *
     * Основное
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Sets a new main
     *
     * Основное
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType $main
     * @return static
     */
    public function setMain(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\MainAType $main)
    {
        $this->main = $main;
        return $this;
    }

    /**
     * Gets as applicant
     *
     * Приказодатель
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\ApplicantAType
     */
    public function getApplicant()
    {
        return $this->applicant;
    }

    /**
     * Sets a new applicant
     *
     * Приказодатель
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\ApplicantAType $applicant
     * @return static
     */
    public function setApplicant(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\ApplicantAType $applicant)
    {
        $this->applicant = $applicant;
        return $this;
    }

    /**
     * Gets as beneficiary
     *
     * Бенефициар
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\BeneficiaryAType
     */
    public function getBeneficiary()
    {
        return $this->beneficiary;
    }

    /**
     * Sets a new beneficiary
     *
     * Бенефициар
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\BeneficiaryAType $beneficiary
     * @return static
     */
    public function setBeneficiary(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\BeneficiaryAType $beneficiary)
    {
        $this->beneficiary = $beneficiary;
        return $this;
    }

    /**
     * Gets as benefBank
     *
     * Банк бенефициара
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\BenefBankAType
     */
    public function getBenefBank()
    {
        return $this->benefBank;
    }

    /**
     * Sets a new benefBank
     *
     * Банк бенефициара
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\BenefBankAType $benefBank
     * @return static
     */
    public function setBenefBank(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\BenefBankAType $benefBank)
    {
        $this->benefBank = $benefBank;
        return $this;
    }

    /**
     * Gets as accrSum
     *
     * Сумма аккредитива
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType
     */
    public function getAccrSum()
    {
        return $this->accrSum;
    }

    /**
     * Sets a new accrSum
     *
     * Сумма аккредитива
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType $accrSum
     * @return static
     */
    public function setAccrSum(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\AccrSumAType $accrSum)
    {
        $this->accrSum = $accrSum;
        return $this;
    }

    /**
     * Gets as other
     *
     * Прочие сведения
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\OtherAType
     */
    public function getOther()
    {
        return $this->other;
    }

    /**
     * Sets a new other
     *
     * Прочие сведения
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\OtherAType $other
     * @return static
     */
    public function setOther(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\OtherAType $other)
    {
        $this->other = $other;
        return $this;
    }

    /**
     * Gets as shipping
     *
     * Отгрузка
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\ShippingAType
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
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\ShippingAType $shipping
     * @return static
     */
    public function setShipping(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\ShippingAType $shipping)
    {
        $this->shipping = $shipping;
        return $this;
    }

    /**
     * Gets as goods
     *
     * Товар
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
     * Товар
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
     * Gets as terms
     *
     * Условия
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\TermsAType
     */
    public function getTerms()
    {
        return $this->terms;
    }

    /**
     * Sets a new terms
     *
     * Условия
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\TermsAType $terms
     * @return static
     */
    public function setTerms(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\TermsAType $terms)
    {
        $this->terms = $terms;
        return $this;
    }

    /**
     * Gets as documents
     *
     * Документы
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocumentsAType
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
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocumentsAType $documents
     * @return static
     */
    public function setDocuments(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\DocumentsAType $documents)
    {
        $this->documents = $documents;
        return $this;
    }

    /**
     * Gets as addTerms
     *
     * Дополнительные условия.
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AddTermsAType
     */
    public function getAddTerms()
    {
        return $this->addTerms;
    }

    /**
     * Sets a new addTerms
     *
     * Дополнительные условия.
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\AddTermsAType $addTerms
     * @return static
     */
    public function setAddTerms(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\AddTermsAType $addTerms)
    {
        $this->addTerms = $addTerms;
        return $this;
    }


}

