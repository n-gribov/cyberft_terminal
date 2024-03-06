<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing Charge71AType
 *
 *
 * XSD Type: Charge_71A
 */
class Charge71AType
{

    /**
     * Тип комиссии за перевод: BEN, SHA или OUR
     *
     * @property string $chargesParty
     */
    private $chargesParty = null;

    /**
     * Gets as chargesParty
     *
     * Тип комиссии за перевод: BEN, SHA или OUR
     *
     * @return string
     */
    public function getChargesParty()
    {
        return $this->chargesParty;
    }

    /**
     * Sets a new chargesParty
     *
     * Тип комиссии за перевод: BEN, SHA или OUR
     *
     * @param string $chargesParty
     * @return static
     */
    public function setChargesParty($chargesParty)
    {
        $this->chargesParty = $chargesParty;
        return $this;
    }


}

