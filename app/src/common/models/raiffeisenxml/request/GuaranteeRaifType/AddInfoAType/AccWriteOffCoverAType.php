<?php

namespace common\models\raiffeisenxml\request\GuaranteeRaifType\AddInfoAType;

/**
 * Class representing AccWriteOffCoverAType
 */
class AccWriteOffCoverAType
{

    /**
     * Номер счета и БИК
     *
     * @property \common\models\raiffeisenxml\request\AccNumBicType $acc
     */
    private $acc = null;

    /**
     * Наименование банка
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Gets as acc
     *
     * Номер счета и БИК
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
     * Номер счета и БИК
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
     * Наименование банка
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
     * Наименование банка
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
