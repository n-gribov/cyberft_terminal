<?php

namespace common\models\raiffeisenxml\response\CartotecaDocType;

/**
 * Class representing Cartoteca2AType
 */
class Cartoteca2AType
{

    /**
     * Количество документов
     *
     * @property string $quantity
     */
    private $quantity = null;

    /**
     * На сумму. Указывается сумма расчетных документов в виде целого количества сотых долей денежной величины
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
     * На сумму. Указывается сумма расчетных документов в виде целого количества сотых долей денежной величины
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
     * На сумму. Указывается сумма расчетных документов в виде целого количества сотых долей денежной величины
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

