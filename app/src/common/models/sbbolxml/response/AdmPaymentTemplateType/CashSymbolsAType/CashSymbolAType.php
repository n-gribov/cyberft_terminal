<?php

namespace common\models\sbbolxml\response\AdmPaymentTemplateType\CashSymbolsAType;

/**
 * Class representing CashSymbolAType
 */
class CashSymbolAType
{

    /**
     * Заполняется кодом кассового символа. Значение справочника UpgRplDictionary/ADMCashSymbolsDict/@cashSymbols
     *
     * @property string $cashSymbolId
     */
    private $cashSymbolId = null;

    /**
     * Gets as cashSymbolId
     *
     * Заполняется кодом кассового символа. Значение справочника UpgRplDictionary/ADMCashSymbolsDict/@cashSymbols
     *
     * @return string
     */
    public function getCashSymbolId()
    {
        return $this->cashSymbolId;
    }

    /**
     * Sets a new cashSymbolId
     *
     * Заполняется кодом кассового символа. Значение справочника UpgRplDictionary/ADMCashSymbolsDict/@cashSymbols
     *
     * @param string $cashSymbolId
     * @return static
     */
    public function setCashSymbolId($cashSymbolId)
    {
        $this->cashSymbolId = $cashSymbolId;
        return $this;
    }


}

