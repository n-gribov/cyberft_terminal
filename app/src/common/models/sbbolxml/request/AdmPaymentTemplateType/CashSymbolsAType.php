<?php

namespace common\models\sbbolxml\request\AdmPaymentTemplateType;

/**
 * Class representing CashSymbolsAType
 */
class CashSymbolsAType
{

    /**
     * @property \common\models\sbbolxml\request\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType[] $cashSymbol
     */
    private $cashSymbol = array(
        
    );

    /**
     * Adds as cashSymbol
     *
     * @return static
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType $cashSymbol
     */
    public function addToCashSymbol(\common\models\sbbolxml\request\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType $cashSymbol)
    {
        $this->cashSymbol[] = $cashSymbol;
        return $this;
    }

    /**
     * isset cashSymbol
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCashSymbol($index)
    {
        return isset($this->cashSymbol[$index]);
    }

    /**
     * unset cashSymbol
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCashSymbol($index)
    {
        unset($this->cashSymbol[$index]);
    }

    /**
     * Gets as cashSymbol
     *
     * @return \common\models\sbbolxml\request\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType[]
     */
    public function getCashSymbol()
    {
        return $this->cashSymbol;
    }

    /**
     * Sets a new cashSymbol
     *
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateType\CashSymbolsAType\CashSymbolAType[] $cashSymbol
     * @return static
     */
    public function setCashSymbol(array $cashSymbol)
    {
        $this->cashSymbol = $cashSymbol;
        return $this;
    }


}

