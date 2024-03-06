<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing AdmPaymentTemplateType
 *
 * Работа со справочником Шаблоны внесения средств
 * XSD Type: AdmPaymentTemplate
 */
class AdmPaymentTemplateType
{

    /**
     * Идентификатор сущности в УС.
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Идентификатор сущности в
     *  СББОЛ. При отсутствии
     *  идентификатора - СББОЛ
     *  будет создана новая запись.
     *  При наличии
     *  идентификатора - в СББОЛ
     *  будет произведено
     *  изменение существующей
     *  записи с данным id
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Сгенерировать SMS-код для подтверждения записи?
     *
     * @property string $genSMSSign
     */
    private $genSMSSign = null;

    /**
     * Информация об устройстве внесения средств
     *
     * @property \common\models\sbbolxml\request\AdmPaymentTemplateType\TemplateInfoAType $templateInfo
     */
    private $templateInfo = null;

    /**
     * @property \common\models\sbbolxml\request\AdmPaymentTemplateType\PayeeInfoAType $payeeInfo
     */
    private $payeeInfo = null;

    /**
     * Реквизиты бюджетополучателя
     *
     * @property \common\models\sbbolxml\request\AdmPaymentTemplateType\BudgetAType $budget
     */
    private $budget = null;

    /**
     * Кассовые символы
     *
     * @property \common\models\sbbolxml\request\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType[] $cashSymbols
     */
    private $cashSymbols = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор сущности в УС.
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
     * Идентификатор сущности в УС.
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
     * Gets as docId
     *
     * Идентификатор сущности в
     *  СББОЛ. При отсутствии
     *  идентификатора - СББОЛ
     *  будет создана новая запись.
     *  При наличии
     *  идентификатора - в СББОЛ
     *  будет произведено
     *  изменение существующей
     *  записи с данным id
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
     * Идентификатор сущности в
     *  СББОЛ. При отсутствии
     *  идентификатора - СББОЛ
     *  будет создана новая запись.
     *  При наличии
     *  идентификатора - в СББОЛ
     *  будет произведено
     *  изменение существующей
     *  записи с данным id
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
     * Gets as genSMSSign
     *
     * Сгенерировать SMS-код для подтверждения записи?
     *
     * @return string
     */
    public function getGenSMSSign()
    {
        return $this->genSMSSign;
    }

    /**
     * Sets a new genSMSSign
     *
     * Сгенерировать SMS-код для подтверждения записи?
     *
     * @param string $genSMSSign
     * @return static
     */
    public function setGenSMSSign($genSMSSign)
    {
        $this->genSMSSign = $genSMSSign;
        return $this;
    }

    /**
     * Gets as templateInfo
     *
     * Информация об устройстве внесения средств
     *
     * @return \common\models\sbbolxml\request\AdmPaymentTemplateType\TemplateInfoAType
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
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateType\TemplateInfoAType $templateInfo
     * @return static
     */
    public function setTemplateInfo(\common\models\sbbolxml\request\AdmPaymentTemplateType\TemplateInfoAType $templateInfo)
    {
        $this->templateInfo = $templateInfo;
        return $this;
    }

    /**
     * Gets as payeeInfo
     *
     * @return \common\models\sbbolxml\request\AdmPaymentTemplateType\PayeeInfoAType
     */
    public function getPayeeInfo()
    {
        return $this->payeeInfo;
    }

    /**
     * Sets a new payeeInfo
     *
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateType\PayeeInfoAType $payeeInfo
     * @return static
     */
    public function setPayeeInfo(\common\models\sbbolxml\request\AdmPaymentTemplateType\PayeeInfoAType $payeeInfo)
    {
        $this->payeeInfo = $payeeInfo;
        return $this;
    }

    /**
     * Gets as budget
     *
     * Реквизиты бюджетополучателя
     *
     * @return \common\models\sbbolxml\request\AdmPaymentTemplateType\BudgetAType
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
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateType\BudgetAType $budget
     * @return static
     */
    public function setBudget(\common\models\sbbolxml\request\AdmPaymentTemplateType\BudgetAType $budget)
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
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType $cashSymbol
     */
    public function addToCashSymbols(\common\models\sbbolxml\request\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType $cashSymbol)
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
     * @return \common\models\sbbolxml\request\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType[]
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
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType[] $cashSymbols
     * @return static
     */
    public function setCashSymbols(array $cashSymbols)
    {
        $this->cashSymbols = $cashSymbols;
        return $this;
    }


}

