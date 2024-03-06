<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing BeneficiarInfoISCType
 *
 * Реквизиты нерезидента
 * XSD Type: BeneficiarInfoISC
 */
class BeneficiarInfoISCType extends BeneficiarInfoType
{

    /**
     * Идентификатор иностранного контрагента
     *
     * @property string $beneficiarId
     */
    private $beneficiarId = null;

    /**
     * Gets as beneficiarId
     *
     * Идентификатор иностранного контрагента
     *
     * @return string
     */
    public function getBeneficiarId()
    {
        return $this->beneficiarId;
    }

    /**
     * Sets a new beneficiarId
     *
     * Идентификатор иностранного контрагента
     *
     * @param string $beneficiarId
     * @return static
     */
    public function setBeneficiarId($beneficiarId)
    {
        $this->beneficiarId = $beneficiarId;
        return $this;
    }


}

