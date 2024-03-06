<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing PercentType
 *
 * Процентные платежи
 * XSD Type: Percent
 */
class PercentType
{

    /**
     * Тип процентных платежей: 1 - Фикс. размер процентной ставки (% годовых), 2 - Код ставки ЛИБОР, 3 - Другой метод определения процентной ставки
     *
     * @property string $percentType
     */
    private $percentType = null;

    /**
     * Фиксированный размер процентной ставки (% годовых)
     *
     * @property float $fixPercent
     */
    private $fixPercent = null;

    /**
     * Код ставки LIBOR например, Л06
     *
     * @property string $lIBOR
     */
    private $lIBOR = null;

    /**
     * Другие методы определения процентной ставки
     *
     * @property string $otherMethod
     */
    private $otherMethod = null;

    /**
     * Размер процентной надбавки (% годовых)
     *
     * @property float $incPercent
     */
    private $incPercent = null;

    /**
     * Gets as percentType
     *
     * Тип процентных платежей: 1 - Фикс. размер процентной ставки (% годовых), 2 - Код ставки ЛИБОР, 3 - Другой метод определения процентной ставки
     *
     * @return string
     */
    public function getPercentType()
    {
        return $this->percentType;
    }

    /**
     * Sets a new percentType
     *
     * Тип процентных платежей: 1 - Фикс. размер процентной ставки (% годовых), 2 - Код ставки ЛИБОР, 3 - Другой метод определения процентной ставки
     *
     * @param string $percentType
     * @return static
     */
    public function setPercentType($percentType)
    {
        $this->percentType = $percentType;
        return $this;
    }

    /**
     * Gets as fixPercent
     *
     * Фиксированный размер процентной ставки (% годовых)
     *
     * @return float
     */
    public function getFixPercent()
    {
        return $this->fixPercent;
    }

    /**
     * Sets a new fixPercent
     *
     * Фиксированный размер процентной ставки (% годовых)
     *
     * @param float $fixPercent
     * @return static
     */
    public function setFixPercent($fixPercent)
    {
        $this->fixPercent = $fixPercent;
        return $this;
    }

    /**
     * Gets as lIBOR
     *
     * Код ставки LIBOR например, Л06
     *
     * @return string
     */
    public function getLIBOR()
    {
        return $this->lIBOR;
    }

    /**
     * Sets a new lIBOR
     *
     * Код ставки LIBOR например, Л06
     *
     * @param string $lIBOR
     * @return static
     */
    public function setLIBOR($lIBOR)
    {
        $this->lIBOR = $lIBOR;
        return $this;
    }

    /**
     * Gets as otherMethod
     *
     * Другие методы определения процентной ставки
     *
     * @return string
     */
    public function getOtherMethod()
    {
        return $this->otherMethod;
    }

    /**
     * Sets a new otherMethod
     *
     * Другие методы определения процентной ставки
     *
     * @param string $otherMethod
     * @return static
     */
    public function setOtherMethod($otherMethod)
    {
        $this->otherMethod = $otherMethod;
        return $this;
    }

    /**
     * Gets as incPercent
     *
     * Размер процентной надбавки (% годовых)
     *
     * @return float
     */
    public function getIncPercent()
    {
        return $this->incPercent;
    }

    /**
     * Sets a new incPercent
     *
     * Размер процентной надбавки (% годовых)
     *
     * @param float $incPercent
     * @return static
     */
    public function setIncPercent($incPercent)
    {
        $this->incPercent = $incPercent;
        return $this;
    }


}

