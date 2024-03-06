<?php

namespace common\models\sbbolxml\response\OrgInfoType\AuthPersonsAType\AuthPersonAType\AuthSignsAType\AuthSignAType\AccountsAType\AccountAType;

/**
 * Class representing AmmountAType
 */
class AmmountAType
{

    /**
     * Сумма
     *
     * @property float $docSum
     */
    private $docSum = null;

    /**
     * Без ограничений
     *  по сумме.
     *  1- признак
     *  установлен;
     *  0- признак НЕ
     *  установлен.
     *
     * @property boolean $noLimit
     */
    private $noLimit = null;

    /**
     * Gets as docSum
     *
     * Сумма
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
     * Сумма
     *
     * @param float $docSum
     * @return static
     */
    public function setDocSum($docSum)
    {
        $this->docSum = $docSum;
        return $this;
    }

    /**
     * Gets as noLimit
     *
     * Без ограничений
     *  по сумме.
     *  1- признак
     *  установлен;
     *  0- признак НЕ
     *  установлен.
     *
     * @return boolean
     */
    public function getNoLimit()
    {
        return $this->noLimit;
    }

    /**
     * Sets a new noLimit
     *
     * Без ограничений
     *  по сумме.
     *  1- признак
     *  установлен;
     *  0- признак НЕ
     *  установлен.
     *
     * @param boolean $noLimit
     * @return static
     */
    public function setNoLimit($noLimit)
    {
        $this->noLimit = $noLimit;
        return $this;
    }


}

