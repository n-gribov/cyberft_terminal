<?php

namespace common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType;

/**
 * Class representing ResCredInfoAType
 */
class ResCredInfoAType
{

    /**
     * Номер по порядку
     *
     * @property string $num
     */
    private $num = null;

    /**
     * Наименование
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Код страны банка нерезедента
     *
     * @property string $country
     */
    private $country = null;

    /**
     * Сумма
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\ResCredInfoAType\SumAType $sum
     */
    private $sum = null;

    /**
     * Доля в общей сумме
     *
     * @property float $share
     */
    private $share = null;

    /**
     * Gets as num
     *
     * Номер по порядку
     *
     * @return string
     */
    public function getNum()
    {
        return $this->num;
    }

    /**
     * Sets a new num
     *
     * Номер по порядку
     *
     * @param string $num
     * @return static
     */
    public function setNum($num)
    {
        $this->num = $num;
        return $this;
    }

    /**
     * Gets as name
     *
     * Наименование
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Наименование
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as country
     *
     * Код страны банка нерезедента
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * Код страны банка нерезедента
     *
     * @param string $country
     * @return static
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\ResCredInfoAType\SumAType
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\ResCredInfoAType\SumAType $sum
     * @return static
     */
    public function setSum(\common\models\raiffeisenxml\request\ChanDPCredRaifType\SpecDataAType\ResCredInfoAType\SumAType $sum)
    {
        $this->sum = $sum;
        return $this;
    }

    /**
     * Gets as share
     *
     * Доля в общей сумме
     *
     * @return float
     */
    public function getShare()
    {
        return $this->share;
    }

    /**
     * Sets a new share
     *
     * Доля в общей сумме
     *
     * @param float $share
     * @return static
     */
    public function setShare($share)
    {
        $this->share = $share;
        return $this;
    }


}

