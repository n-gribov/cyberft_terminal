<?php

namespace common\models\sbbolxml\response\CartotecaDocType;

/**
 * Class representing PermitionOperDocsAType
 */
class PermitionOperDocsAType
{

    /**
     * Количество документов
     *
     * @property string $quantity
     */
    private $quantity = null;

    /**
     * На сумму.
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
     * На сумму.
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
     * На сумму.
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

