<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\SalaryContractsAType\SalaryContractAType\SalTypesAType;

/**
 * Class representing SalTypeAType
 */
class SalTypeAType
{

    /**
     * Идентификатор вида зачисления
     *
     * @property string $id
     */
    private $id = null;

    /**
     * Код вида зачисления
     *
     * @property string $code
     */
    private $code = null;

    /**
     * Описание вида зачисления
     *
     * @property string $description
     */
    private $description = null;

    /**
     * Gets as id
     *
     * Идентификатор вида зачисления
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets a new id
     *
     * Идентификатор вида зачисления
     *
     * @param string $id
     * @return static
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets as code
     *
     * Код вида зачисления
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Код вида зачисления
     *
     * @param string $code
     * @return static
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as description
     *
     * Описание вида зачисления
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * Описание вида зачисления
     *
     * @param string $description
     * @return static
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }


}

