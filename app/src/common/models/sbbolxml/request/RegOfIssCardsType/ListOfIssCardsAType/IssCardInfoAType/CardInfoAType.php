<?php

namespace common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType;

/**
 * Class representing CardInfoAType
 */
class CardInfoAType
{

    /**
     * Предопределенный список значений.
     *  Значение Тип карты
     *  01 - Visa Classic
     *  02 - Visa Gold
     *  03 - MasterCard Standard
     *  04 - Gold MasterCard
     *  05 - Сбербанк-Maestro
     *  06 - Visa Electron
     *  07 - Visa Platinum
     *  08 - Platinum MasterCard
     *  09 - Visa Infinite
     *  12 - Visa Classic "Аэрофлот"
     *  13 - Visa Classic "Молодежная"
     *  14 - MasterCard Standard "МТС"
     *  15 - MasterCard Standard "Молодежная"
     *  16 - Visa Gold "Аэрофлот"
     *  17 - Gold MasterCard "МТС"
     *
     * @property string $cardType
     */
    private $cardType = null;

    /**
     * Название типа карты из справочника типов карт
     *
     * @property string $cardTypeName
     */
    private $cardTypeName = null;

    /**
     * Номер участника в бонусной программе
     *
     * @property string $bonusNum
     */
    private $bonusNum = null;

    /**
     * @property string $bonusId
     */
    private $bonusId = null;

    /**
     * Прошу ОАО "Сбербанк России" открыть на мое имя,
     *  счет карты:
     *  - Рубли,
     *  - Доллар,
     *  - Евро
     *  Передавать код валюты
     *
     * @property string $cardCurrName
     */
    private $cardCurrName = null;

    /**
     * Gets as cardType
     *
     * Предопределенный список значений.
     *  Значение Тип карты
     *  01 - Visa Classic
     *  02 - Visa Gold
     *  03 - MasterCard Standard
     *  04 - Gold MasterCard
     *  05 - Сбербанк-Maestro
     *  06 - Visa Electron
     *  07 - Visa Platinum
     *  08 - Platinum MasterCard
     *  09 - Visa Infinite
     *  12 - Visa Classic "Аэрофлот"
     *  13 - Visa Classic "Молодежная"
     *  14 - MasterCard Standard "МТС"
     *  15 - MasterCard Standard "Молодежная"
     *  16 - Visa Gold "Аэрофлот"
     *  17 - Gold MasterCard "МТС"
     *
     * @return string
     */
    public function getCardType()
    {
        return $this->cardType;
    }

    /**
     * Sets a new cardType
     *
     * Предопределенный список значений.
     *  Значение Тип карты
     *  01 - Visa Classic
     *  02 - Visa Gold
     *  03 - MasterCard Standard
     *  04 - Gold MasterCard
     *  05 - Сбербанк-Maestro
     *  06 - Visa Electron
     *  07 - Visa Platinum
     *  08 - Platinum MasterCard
     *  09 - Visa Infinite
     *  12 - Visa Classic "Аэрофлот"
     *  13 - Visa Classic "Молодежная"
     *  14 - MasterCard Standard "МТС"
     *  15 - MasterCard Standard "Молодежная"
     *  16 - Visa Gold "Аэрофлот"
     *  17 - Gold MasterCard "МТС"
     *
     * @param string $cardType
     * @return static
     */
    public function setCardType($cardType)
    {
        $this->cardType = $cardType;
        return $this;
    }

    /**
     * Gets as cardTypeName
     *
     * Название типа карты из справочника типов карт
     *
     * @return string
     */
    public function getCardTypeName()
    {
        return $this->cardTypeName;
    }

    /**
     * Sets a new cardTypeName
     *
     * Название типа карты из справочника типов карт
     *
     * @param string $cardTypeName
     * @return static
     */
    public function setCardTypeName($cardTypeName)
    {
        $this->cardTypeName = $cardTypeName;
        return $this;
    }

    /**
     * Gets as bonusNum
     *
     * Номер участника в бонусной программе
     *
     * @return string
     */
    public function getBonusNum()
    {
        return $this->bonusNum;
    }

    /**
     * Sets a new bonusNum
     *
     * Номер участника в бонусной программе
     *
     * @param string $bonusNum
     * @return static
     */
    public function setBonusNum($bonusNum)
    {
        $this->bonusNum = $bonusNum;
        return $this;
    }

    /**
     * Gets as bonusId
     *
     * @return string
     */
    public function getBonusId()
    {
        return $this->bonusId;
    }

    /**
     * Sets a new bonusId
     *
     * @param string $bonusId
     * @return static
     */
    public function setBonusId($bonusId)
    {
        $this->bonusId = $bonusId;
        return $this;
    }

    /**
     * Gets as cardCurrName
     *
     * Прошу ОАО "Сбербанк России" открыть на мое имя,
     *  счет карты:
     *  - Рубли,
     *  - Доллар,
     *  - Евро
     *  Передавать код валюты
     *
     * @return string
     */
    public function getCardCurrName()
    {
        return $this->cardCurrName;
    }

    /**
     * Sets a new cardCurrName
     *
     * Прошу ОАО "Сбербанк России" открыть на мое имя,
     *  счет карты:
     *  - Рубли,
     *  - Доллар,
     *  - Евро
     *  Передавать код валюты
     *
     * @param string $cardCurrName
     * @return static
     */
    public function setCardCurrName($cardCurrName)
    {
        $this->cardCurrName = $cardCurrName;
        return $this;
    }


}

