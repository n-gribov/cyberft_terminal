<?php

namespace common\models\raiffeisenxml\request\AccreditiveCurRaifType;

/**
 * Class representing TermsAType
 */
class TermsAType
{

    /**
     * Условия поставки товара. Возможные значения: "Incoterms 2000", "Incoterms 2010".
     *
     * @property string $deliveryTerms
     */
    private $deliveryTerms = null;

    /**
     * Условия поставки товара
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType\TermsAType\DeliveryAType[] $delivery
     */
    private $delivery = [
        
    ];

    /**
     * Расходы вне территории России за счет. Возможные значения: "Приказодателя / Applicant", "Бенефициара / Beneficiary".
     *
     * @property string $costs
     */
    private $costs = null;

    /**
     * Расходы по подтверждению за счет. Возможные значения: "Приказодателя / Applicant", "Бенефициара / Beneficiary".
     *
     * @property string $confirmationCosts
     */
    private $confirmationCosts = null;

    /**
     * Gets as deliveryTerms
     *
     * Условия поставки товара. Возможные значения: "Incoterms 2000", "Incoterms 2010".
     *
     * @return string
     */
    public function getDeliveryTerms()
    {
        return $this->deliveryTerms;
    }

    /**
     * Sets a new deliveryTerms
     *
     * Условия поставки товара. Возможные значения: "Incoterms 2000", "Incoterms 2010".
     *
     * @param string $deliveryTerms
     * @return static
     */
    public function setDeliveryTerms($deliveryTerms)
    {
        $this->deliveryTerms = $deliveryTerms;
        return $this;
    }

    /**
     * Adds as delivery
     *
     * Условия поставки товара
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\TermsAType\DeliveryAType $delivery
     */
    public function addToDelivery(\common\models\raiffeisenxml\request\AccreditiveCurRaifType\TermsAType\DeliveryAType $delivery)
    {
        $this->delivery[] = $delivery;
        return $this;
    }

    /**
     * isset delivery
     *
     * Условия поставки товара
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDelivery($index)
    {
        return isset($this->delivery[$index]);
    }

    /**
     * unset delivery
     *
     * Условия поставки товара
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDelivery($index)
    {
        unset($this->delivery[$index]);
    }

    /**
     * Gets as delivery
     *
     * Условия поставки товара
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType\TermsAType\DeliveryAType[]
     */
    public function getDelivery()
    {
        return $this->delivery;
    }

    /**
     * Sets a new delivery
     *
     * Условия поставки товара
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType\TermsAType\DeliveryAType[] $delivery
     * @return static
     */
    public function setDelivery(array $delivery)
    {
        $this->delivery = $delivery;
        return $this;
    }

    /**
     * Gets as costs
     *
     * Расходы вне территории России за счет. Возможные значения: "Приказодателя / Applicant", "Бенефициара / Beneficiary".
     *
     * @return string
     */
    public function getCosts()
    {
        return $this->costs;
    }

    /**
     * Sets a new costs
     *
     * Расходы вне территории России за счет. Возможные значения: "Приказодателя / Applicant", "Бенефициара / Beneficiary".
     *
     * @param string $costs
     * @return static
     */
    public function setCosts($costs)
    {
        $this->costs = $costs;
        return $this;
    }

    /**
     * Gets as confirmationCosts
     *
     * Расходы по подтверждению за счет. Возможные значения: "Приказодателя / Applicant", "Бенефициара / Beneficiary".
     *
     * @return string
     */
    public function getConfirmationCosts()
    {
        return $this->confirmationCosts;
    }

    /**
     * Sets a new confirmationCosts
     *
     * Расходы по подтверждению за счет. Возможные значения: "Приказодателя / Applicant", "Бенефициара / Beneficiary".
     *
     * @param string $confirmationCosts
     * @return static
     */
    public function setConfirmationCosts($confirmationCosts)
    {
        $this->confirmationCosts = $confirmationCosts;
        return $this;
    }


}

