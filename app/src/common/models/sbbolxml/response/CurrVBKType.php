<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CurrVBKType
 *
 * Валюта суммы контракта, кредитного договора
 * XSD Type: CurrVBK
 */
class CurrVBKType extends DPCurrType
{

    /**
     * ISO-код валюты контракта, кредитного договора
     *
     * @property string $isoCode
     */
    private $isoCode = null;

    /**
     * Gets as isoCode
     *
     * ISO-код валюты контракта, кредитного договора
     *
     * @return string
     */
    public function getIsoCode()
    {
        return $this->isoCode;
    }

    /**
     * Sets a new isoCode
     *
     * ISO-код валюты контракта, кредитного договора
     *
     * @param string $isoCode
     * @return static
     */
    public function setIsoCode($isoCode)
    {
        $this->isoCode = $isoCode;
        return $this;
    }


}

