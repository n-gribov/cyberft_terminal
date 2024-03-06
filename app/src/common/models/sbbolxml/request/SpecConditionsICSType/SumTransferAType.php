<?php

namespace common\models\sbbolxml\request\SpecConditionsICSType;

/**
 * Class representing SumTransferAType
 */
class SumTransferAType
{

    /**
     * Сумма в единицах валюты. Формат XX.YY с двумя знаками после запятой
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Gets as docSum
     *
     * Сумма в единицах валюты. Формат XX.YY с двумя знаками после запятой
     *
     * @return float
     */
    public function getDocSum()
    {
        return $this->docSum;
    }

    /**
     * Sets a new docSum
     *
     * Сумма в единицах валюты. Формат XX.YY с двумя знаками после запятой
     *
     * @param float $docSum
     * @return static
     */
    public function setDocSum($docSum)
    {
        $this->docSum = $docSum;
        return $this;
    }


}

