<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing TypesOfContractsType
 *
 * Виды договоров (контрактов), расчеты по которым планируется осуществлять через Банк (нужное отметить)
 * XSD Type: TypesOfContracts
 */
class TypesOfContractsType
{

    /**
     * Хозяйственный договор (купли-продажи, на оплату/оказание услуг и т.п.)
     *
     * @property boolean $householdContract
     */
    private $householdContract = null;

    /**
     * Договор купли-продажи ценных бумаг
     *
     * @property boolean $securitySalesContract
     */
    private $securitySalesContract = null;

    /**
     * Договор займа
     *
     * @property boolean $loanAgreement
     */
    private $loanAgreement = null;

    /**
     * Договор аренды
     *
     * @property boolean $hiringContract
     */
    private $hiringContract = null;

    /**
     * Внешнеэкономический договор
     *
     * @property boolean $foreignTradeAgreement
     */
    private $foreignTradeAgreement = null;

    /**
     * Договор на оказание банковские услуги (эквайринг, инкассация, кредитование, зарплатные проект и т.п.).
     *
     * @property boolean $bankServiceAgreement
     */
    private $bankServiceAgreement = null;

    /**
     * Иное (флаг)
     *
     * @property boolean $otherContract
     */
    private $otherContract = null;

    /**
     * Иное (текст)
     *
     * @property string $otherContractText
     */
    private $otherContractText = null;

    /**
     * Gets as householdContract
     *
     * Хозяйственный договор (купли-продажи, на оплату/оказание услуг и т.п.)
     *
     * @return boolean
     */
    public function getHouseholdContract()
    {
        return $this->householdContract;
    }

    /**
     * Sets a new householdContract
     *
     * Хозяйственный договор (купли-продажи, на оплату/оказание услуг и т.п.)
     *
     * @param boolean $householdContract
     * @return static
     */
    public function setHouseholdContract($householdContract)
    {
        $this->householdContract = $householdContract;
        return $this;
    }

    /**
     * Gets as securitySalesContract
     *
     * Договор купли-продажи ценных бумаг
     *
     * @return boolean
     */
    public function getSecuritySalesContract()
    {
        return $this->securitySalesContract;
    }

    /**
     * Sets a new securitySalesContract
     *
     * Договор купли-продажи ценных бумаг
     *
     * @param boolean $securitySalesContract
     * @return static
     */
    public function setSecuritySalesContract($securitySalesContract)
    {
        $this->securitySalesContract = $securitySalesContract;
        return $this;
    }

    /**
     * Gets as loanAgreement
     *
     * Договор займа
     *
     * @return boolean
     */
    public function getLoanAgreement()
    {
        return $this->loanAgreement;
    }

    /**
     * Sets a new loanAgreement
     *
     * Договор займа
     *
     * @param boolean $loanAgreement
     * @return static
     */
    public function setLoanAgreement($loanAgreement)
    {
        $this->loanAgreement = $loanAgreement;
        return $this;
    }

    /**
     * Gets as hiringContract
     *
     * Договор аренды
     *
     * @return boolean
     */
    public function getHiringContract()
    {
        return $this->hiringContract;
    }

    /**
     * Sets a new hiringContract
     *
     * Договор аренды
     *
     * @param boolean $hiringContract
     * @return static
     */
    public function setHiringContract($hiringContract)
    {
        $this->hiringContract = $hiringContract;
        return $this;
    }

    /**
     * Gets as foreignTradeAgreement
     *
     * Внешнеэкономический договор
     *
     * @return boolean
     */
    public function getForeignTradeAgreement()
    {
        return $this->foreignTradeAgreement;
    }

    /**
     * Sets a new foreignTradeAgreement
     *
     * Внешнеэкономический договор
     *
     * @param boolean $foreignTradeAgreement
     * @return static
     */
    public function setForeignTradeAgreement($foreignTradeAgreement)
    {
        $this->foreignTradeAgreement = $foreignTradeAgreement;
        return $this;
    }

    /**
     * Gets as bankServiceAgreement
     *
     * Договор на оказание банковские услуги (эквайринг, инкассация, кредитование, зарплатные проект и т.п.).
     *
     * @return boolean
     */
    public function getBankServiceAgreement()
    {
        return $this->bankServiceAgreement;
    }

    /**
     * Sets a new bankServiceAgreement
     *
     * Договор на оказание банковские услуги (эквайринг, инкассация, кредитование, зарплатные проект и т.п.).
     *
     * @param boolean $bankServiceAgreement
     * @return static
     */
    public function setBankServiceAgreement($bankServiceAgreement)
    {
        $this->bankServiceAgreement = $bankServiceAgreement;
        return $this;
    }

    /**
     * Gets as otherContract
     *
     * Иное (флаг)
     *
     * @return boolean
     */
    public function getOtherContract()
    {
        return $this->otherContract;
    }

    /**
     * Sets a new otherContract
     *
     * Иное (флаг)
     *
     * @param boolean $otherContract
     * @return static
     */
    public function setOtherContract($otherContract)
    {
        $this->otherContract = $otherContract;
        return $this;
    }

    /**
     * Gets as otherContractText
     *
     * Иное (текст)
     *
     * @return string
     */
    public function getOtherContractText()
    {
        return $this->otherContractText;
    }

    /**
     * Sets a new otherContractText
     *
     * Иное (текст)
     *
     * @param string $otherContractText
     * @return static
     */
    public function setOtherContractText($otherContractText)
    {
        $this->otherContractText = $otherContractText;
        return $this;
    }


}

