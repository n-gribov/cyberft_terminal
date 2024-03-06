<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing Charge71ARaifType
 *
 *
 * XSD Type: Charge_71ARaif
 */
class Charge71ARaifType
{

    /**
     * Тип комиссии за перевод: BEN, SHA или OUR
     *
     * @property string $chargesParty
     */
    private $chargesParty = null;

    /**
     * Счет взимания комиссии за перевод
     *
     * @property \common\models\raiffeisenxml\request\AccountNoNameType $accCommis
     */
    private $accCommis = null;

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

    /**
     * Gets as accCommis
     *
     * Счет взимания комиссии за перевод
     *
     * @return \common\models\raiffeisenxml\request\AccountNoNameType
     */
    public function getAccCommis()
    {
        return $this->accCommis;
    }

    /**
     * Sets a new accCommis
     *
     * Счет взимания комиссии за перевод
     *
     * @param \common\models\raiffeisenxml\request\AccountNoNameType $accCommis
     * @return static
     */
    public function setAccCommis(\common\models\raiffeisenxml\request\AccountNoNameType $accCommis)
    {
        $this->accCommis = $accCommis;
        return $this;
    }


}

