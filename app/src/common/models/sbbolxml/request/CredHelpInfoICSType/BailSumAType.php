<?php

namespace common\models\sbbolxml\request\CredHelpInfoICSType;

/**
 * Class representing BailSumAType
 */
class BailSumAType
{

    /**
     * Сумма. Формат XX.YY с двумя знаками после запятой
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Gets as docSum
     *
     * Сумма. Формат XX.YY с двумя знаками после запятой
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
     * Сумма. Формат XX.YY с двумя знаками после запятой
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

