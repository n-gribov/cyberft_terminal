<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing AdmPaymentTemplateType
 *
 * Шаблон внесения средств
 * XSD Type: AdmPaymentTemplate
 */
class AdmPaymentTemplateType
{

    /**
     * Идентификатор сущности в СББОЛ
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Информация об устройстве внесения средств
     *
     * @property \common\models\sbbolxml\response\AdmPaymentTemplateType\TemplateInfoAType $templateInfo
     */
    private $templateInfo = null;

    /**
     * @property \common\models\sbbolxml\response\AdmPaymentTemplateType\PayeeInfoAType $payeeInfo
     */
    private $payeeInfo = null;

    /**
     * Реквизиты бюджетополучателя
     *
     * @property \common\models\sbbolxml\response\AdmPaymentTemplateType\BudgetAType $budget
     */
    private $budget = null;

    /**
     * Кассовые символы
     *
     * @property \common\models\sbbolxml\response\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType[] $cashSymbols
     */
    private $cashSymbols = null;

    /**
     * Gets as docId
     *
     * Идентификатор сущности в СББОЛ
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор сущности в СББОЛ
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
        return $this;
    }

    /**
     * Gets as templateInfo
     *
     * Информация об устройстве внесения средств
     *
     * @return \common\models\sbbolxml\response\AdmPaymentTemplateType\TemplateInfoAType
     */
    public function getTemplateInfo()
    {
        return $this->templateInfo;
    }

    /**
     * Sets a new templateInfo
     *
     * Информация об устройстве внесения средств
     *
     * @param \common\models\sbbolxml\response\AdmPaymentTemplateType\TemplateInfoAType $templateInfo
     * @return static
     */
    public function setTemplateInfo(\common\models\sbbolxml\response\AdmPaymentTemplateType\TemplateInfoAType $templateInfo)
    {
        $this->templateInfo = $templateInfo;
        return $this;
    }

    /**
     * Gets as payeeInfo
     *
     * @return \common\models\sbbolxml\response\AdmPaymentTemplateType\PayeeInfoAType
     */
    public function getPayeeInfo()
    {
        return $this->payeeInfo;
    }

    /**
     * Sets a new payeeInfo
     *
     * @param \common\models\sbbolxml\response\AdmPaymentTemplateType\PayeeInfoAType $payeeInfo
     * @return static
     */
    public function setPayeeInfo(\common\models\sbbolxml\response\AdmPaymentTemplateType\PayeeInfoAType $payeeInfo)
    {
        $this->payeeInfo = $payeeInfo;
        return $this;
    }

    /**
     * Gets as budget
     *
     * Реквизиты бюджетополучателя
     *
     * @return \common\models\sbbolxml\response\AdmPaymentTemplateType\BudgetAType
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Sets a new budget
     *
     * Реквизиты бюджетополучателя
     *
     * @param \common\models\sbbolxml\response\AdmPaymentTemplateType\BudgetAType $budget
     * @return static
     */
    public function setBudget(\common\models\sbbolxml\response\AdmPaymentTemplateType\BudgetAType $budget)
    {
        $this->budget = $budget;
        return $this;
    }

    /**
     * Adds as cashSymbol
     *
     * Кассовые символы
     *
     * @return static
     * @param \common\models\sbbolxml\response\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType $cashSymbol
     */
    public function addToCashSymbols(\common\models\sbbolxml\response\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType $cashSymbol)
    {
        $this->cashSymbols[] = $cashSymbol;
        return $this;
    }

    /**
     * isset cashSymbols
     *
     * Кассовые символы
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCashSymbols($index)
    {
        return isset($this->cashSymbols[$index]);
    }

    /**
     * unset cashSymbols
     *
     * Кассовые символы
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCashSymbols($index)
    {
        unset($this->cashSymbols[$index]);
    }

    /**
     * Gets as cashSymbols
     *
     * Кассовые символы
     *
     * @return \common\models\sbbolxml\response\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType[]
     */
    public function getCashSymbols()
    {
        return $this->cashSymbols;
    }

    /**
     * Sets a new cashSymbols
     *
     * Кассовые символы
     *
     * @param \common\models\sbbolxml\response\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType[] $cashSymbols
     * @return static
     */
    public function setCashSymbols(array $cashSymbols)
    {
        $this->cashSymbols = $cashSymbols;
        return $this;
    }


}

