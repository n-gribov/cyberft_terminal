<?php

namespace common\models\raiffeisenxml\request\CreditRaifType\MainAType;

/**
 * Class representing CreditAccAType
 */
class CreditAccAType
{

    /**
     * Номер счета и БИК.
     *
     * @property \common\models\raiffeisenxml\request\AccNumBicType $acc
     */
    private $acc = null;

    /**
     * Наименование Банка
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Gets as acc
     *
     * Номер счета и БИК.
     *
     * @return \common\models\raiffeisenxml\request\AccNumBicType
     */
    public function getAcc()
    {
        return $this->acc;
    }

    /**
     * Sets a new acc
     *
     * Номер счета и БИК.
     *
     * @param \common\models\raiffeisenxml\request\AccNumBicType $acc
     * @return static
     */
    public function setAcc(\common\models\raiffeisenxml\request\AccNumBicType $acc)
    {
        $this->acc = $acc;
        return $this;
    }

    /**
     * Gets as bankName
     *
     * Наименование Банка
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * Наименование Банка
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
        return $this;
    }


}

