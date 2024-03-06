<?php

namespace common\models\raiffeisenxml\response\CartotecaDocType;

/**
 * Class representing NoMoneyAType
 */
class NoMoneyAType
{

    /**
     * Количество документов
     *
     * @property string $quantity
     */
    private $quantity = null;

    /**
     * Сумма в валюте Рубли РФ
     *
     * @property float $sum
     */
    private $sum = null;

    /**
     * Gets as quantity
     *
     * Количество документов
     *
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Sets a new quantity
     *
     * Количество документов
     *
     * @param string $quantity
     * @return static
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * Gets as sum
     *
     * Сумма в валюте Рубли РФ
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Sets a new sum
     *
     * Сумма в валюте Рубли РФ
     *
     * @param float $sum
     * @return static
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
        return $this;
    }


}

