<?php

namespace common\models\raiffeisenxml\request;

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
     * Наименование типа комиссии
     *
     * @property string $chargesPartyName
     */
    private $chargesPartyName = null;

    /**
     * Счет взимания комиссии за перевод
     *
     * @property \common\models\raiffeisenxml\request\AccNumBicType $accCommis
     */
    private $accCommis = null;

    /**
     * Счет взимания комиссии за конверсию
     *
     * @property \common\models\raiffeisenxml\request\AccNumBicType $accConv
     */
    private $accConv = null;

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
     * Gets as chargesPartyName
     *
     * Наименование типа комиссии
     *
     * @return string
     */
    public function getChargesPartyName()
    {
        return $this->chargesPartyName;
    }

    /**
     * Sets a new chargesPartyName
     *
     * Наименование типа комиссии
     *
     * @param string $chargesPartyName
     * @return static
     */
    public function setChargesPartyName($chargesPartyName)
    {
        $this->chargesPartyName = $chargesPartyName;
        return $this;
    }

    /**
     * Gets as accCommis
     *
     * Счет взимания комиссии за перевод
     *
     * @return \common\models\raiffeisenxml\request\AccNumBicType
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
     * @param \common\models\raiffeisenxml\request\AccNumBicType $accCommis
     * @return static
     */
    public function setAccCommis(\common\models\raiffeisenxml\request\AccNumBicType $accCommis)
    {
        $this->accCommis = $accCommis;
        return $this;
    }

    /**
     * Gets as accConv
     *
     * Счет взимания комиссии за конверсию
     *
     * @return \common\models\raiffeisenxml\request\AccNumBicType
     */
    public function getAccConv()
    {
        return $this->accConv;
    }

    /**
     * Sets a new accConv
     *
     * Счет взимания комиссии за конверсию
     *
     * @param \common\models\raiffeisenxml\request\AccNumBicType $accConv
     * @return static
     */
    public function setAccConv(\common\models\raiffeisenxml\request\AccNumBicType $accConv)
    {
        $this->accConv = $accConv;
        return $this;
    }


}

