<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BusinessRelationsInformationType
 *
 * Сведения о целях установления и предполагаемом характере деловых отношений с Банком
 * XSD Type: BusinessRelationsInformation
 */
class BusinessRelationsInformationType
{

    /**
     * Планируемая длительность отношений
     *
     * @property \common\models\sbbolxml\request\BusinessRelationsDurationType $businessRelationsDuration
     */
    private $businessRelationsDuration = null;

    /**
     * Наличие действующих отношений с Банком
     *
     * @property boolean $activeBankRelations
     */
    private $activeBankRelations = null;

    /**
     * Расчетно-кассовое обслуживание
     *
     * @property boolean $paymentAndCashServices
     */
    private $paymentAndCashServices = null;

    /**
     * Кредитование
     *
     * @property boolean $crediting
     */
    private $crediting = null;

    /**
     * Документарные операции (аккредитивы и гарантии, инкассо)
     *
     * @property boolean $documentaryOperations
     */
    private $documentaryOperations = null;

    /**
     * Депозитарные услуги
     *
     * @property boolean $depositoryServices
     */
    private $depositoryServices = null;

    /**
     * Дистанционное банковское обслуживание
     *
     * @property boolean $remoteBanking
     */
    private $remoteBanking = null;

    /**
     * Размещение свободных денежных средств (депозиты,
     *  облигации, депозитные сертификаты и др.)
     *
     * @property boolean $deposits
     */
    private $deposits = null;

    /**
     * Иное (флаг)
     *
     * @property boolean $otherBusinessRelationsInformation
     */
    private $otherBusinessRelationsInformation = null;

    /**
     * Иное (текст)
     *
     * @property string $otherBusinessRelationsInformationText
     */
    private $otherBusinessRelationsInformationText = null;

    /**
     * Gets as businessRelationsDuration
     *
     * Планируемая длительность отношений
     *
     * @return \common\models\sbbolxml\request\BusinessRelationsDurationType
     */
    public function getBusinessRelationsDuration()
    {
        return $this->businessRelationsDuration;
    }

    /**
     * Sets a new businessRelationsDuration
     *
     * Планируемая длительность отношений
     *
     * @param \common\models\sbbolxml\request\BusinessRelationsDurationType $businessRelationsDuration
     * @return static
     */
    public function setBusinessRelationsDuration(\common\models\sbbolxml\request\BusinessRelationsDurationType $businessRelationsDuration)
    {
        $this->businessRelationsDuration = $businessRelationsDuration;
        return $this;
    }

    /**
     * Gets as activeBankRelations
     *
     * Наличие действующих отношений с Банком
     *
     * @return boolean
     */
    public function getActiveBankRelations()
    {
        return $this->activeBankRelations;
    }

    /**
     * Sets a new activeBankRelations
     *
     * Наличие действующих отношений с Банком
     *
     * @param boolean $activeBankRelations
     * @return static
     */
    public function setActiveBankRelations($activeBankRelations)
    {
        $this->activeBankRelations = $activeBankRelations;
        return $this;
    }

    /**
     * Gets as paymentAndCashServices
     *
     * Расчетно-кассовое обслуживание
     *
     * @return boolean
     */
    public function getPaymentAndCashServices()
    {
        return $this->paymentAndCashServices;
    }

    /**
     * Sets a new paymentAndCashServices
     *
     * Расчетно-кассовое обслуживание
     *
     * @param boolean $paymentAndCashServices
     * @return static
     */
    public function setPaymentAndCashServices($paymentAndCashServices)
    {
        $this->paymentAndCashServices = $paymentAndCashServices;
        return $this;
    }

    /**
     * Gets as crediting
     *
     * Кредитование
     *
     * @return boolean
     */
    public function getCrediting()
    {
        return $this->crediting;
    }

    /**
     * Sets a new crediting
     *
     * Кредитование
     *
     * @param boolean $crediting
     * @return static
     */
    public function setCrediting($crediting)
    {
        $this->crediting = $crediting;
        return $this;
    }

    /**
     * Gets as documentaryOperations
     *
     * Документарные операции (аккредитивы и гарантии, инкассо)
     *
     * @return boolean
     */
    public function getDocumentaryOperations()
    {
        return $this->documentaryOperations;
    }

    /**
     * Sets a new documentaryOperations
     *
     * Документарные операции (аккредитивы и гарантии, инкассо)
     *
     * @param boolean $documentaryOperations
     * @return static
     */
    public function setDocumentaryOperations($documentaryOperations)
    {
        $this->documentaryOperations = $documentaryOperations;
        return $this;
    }

    /**
     * Gets as depositoryServices
     *
     * Депозитарные услуги
     *
     * @return boolean
     */
    public function getDepositoryServices()
    {
        return $this->depositoryServices;
    }

    /**
     * Sets a new depositoryServices
     *
     * Депозитарные услуги
     *
     * @param boolean $depositoryServices
     * @return static
     */
    public function setDepositoryServices($depositoryServices)
    {
        $this->depositoryServices = $depositoryServices;
        return $this;
    }

    /**
     * Gets as remoteBanking
     *
     * Дистанционное банковское обслуживание
     *
     * @return boolean
     */
    public function getRemoteBanking()
    {
        return $this->remoteBanking;
    }

    /**
     * Sets a new remoteBanking
     *
     * Дистанционное банковское обслуживание
     *
     * @param boolean $remoteBanking
     * @return static
     */
    public function setRemoteBanking($remoteBanking)
    {
        $this->remoteBanking = $remoteBanking;
        return $this;
    }

    /**
     * Gets as deposits
     *
     * Размещение свободных денежных средств (депозиты,
     *  облигации, депозитные сертификаты и др.)
     *
     * @return boolean
     */
    public function getDeposits()
    {
        return $this->deposits;
    }

    /**
     * Sets a new deposits
     *
     * Размещение свободных денежных средств (депозиты,
     *  облигации, депозитные сертификаты и др.)
     *
     * @param boolean $deposits
     * @return static
     */
    public function setDeposits($deposits)
    {
        $this->deposits = $deposits;
        return $this;
    }

    /**
     * Gets as otherBusinessRelationsInformation
     *
     * Иное (флаг)
     *
     * @return boolean
     */
    public function getOtherBusinessRelationsInformation()
    {
        return $this->otherBusinessRelationsInformation;
    }

    /**
     * Sets a new otherBusinessRelationsInformation
     *
     * Иное (флаг)
     *
     * @param boolean $otherBusinessRelationsInformation
     * @return static
     */
    public function setOtherBusinessRelationsInformation($otherBusinessRelationsInformation)
    {
        $this->otherBusinessRelationsInformation = $otherBusinessRelationsInformation;
        return $this;
    }

    /**
     * Gets as otherBusinessRelationsInformationText
     *
     * Иное (текст)
     *
     * @return string
     */
    public function getOtherBusinessRelationsInformationText()
    {
        return $this->otherBusinessRelationsInformationText;
    }

    /**
     * Sets a new otherBusinessRelationsInformationText
     *
     * Иное (текст)
     *
     * @param string $otherBusinessRelationsInformationText
     * @return static
     */
    public function setOtherBusinessRelationsInformationText($otherBusinessRelationsInformationText)
    {
        $this->otherBusinessRelationsInformationText = $otherBusinessRelationsInformationText;
        return $this;
    }


}

