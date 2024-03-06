<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing BudgetDepartmentalInfoType
 *
 *
 * XSD Type: BudgetDepartmentalInfo
 */
class BudgetDepartmentalInfoType extends DepartmentalInfoBaseType
{

    /**
     * Код бюджетной классификации (104)
     *
     * @property string $cbc
     */
    private $cbc = null;

    /**
     * Gets as cbc
     *
     * Код бюджетной классификации (104)
     *
     * @return string
     */
    public function getCbc()
    {
        return $this->cbc;
    }

    /**
     * Sets a new cbc
     *
     * Код бюджетной классификации (104)
     *
     * @param string $cbc
     * @return static
     */
    public function setCbc($cbc)
    {
        $this->cbc = $cbc;
        return $this;
    }


}

