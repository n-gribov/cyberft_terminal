<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\BranchesAType\BranchAType;

/**
 * Class representing BranchDataAType
 */
class BranchDataAType
{

    /**
     * Полное фирменное наименование подразделения
     *  кредитной организации
     *
     * @property string $fullName
     */
    private $fullName = null;

    /**
     * Полное междкнародное наименование
     *  подразделения кредитной организации
     *
     * @property string $intFullName
     */
    private $intFullName = null;

    /**
     * Сокращенное междкнародное наименование
     *  подраздеоления кредитной организации
     *
     * @property string $intShortName
     */
    private $intShortName = null;

    /**
     * Gets as fullName
     *
     * Полное фирменное наименование подразделения
     *  кредитной организации
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Sets a new fullName
     *
     * Полное фирменное наименование подразделения
     *  кредитной организации
     *
     * @param string $fullName
     * @return static
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
        return $this;
    }

    /**
     * Gets as intFullName
     *
     * Полное междкнародное наименование
     *  подразделения кредитной организации
     *
     * @return string
     */
    public function getIntFullName()
    {
        return $this->intFullName;
    }

    /**
     * Sets a new intFullName
     *
     * Полное междкнародное наименование
     *  подразделения кредитной организации
     *
     * @param string $intFullName
     * @return static
     */
    public function setIntFullName($intFullName)
    {
        $this->intFullName = $intFullName;
        return $this;
    }

    /**
     * Gets as intShortName
     *
     * Сокращенное междкнародное наименование
     *  подраздеоления кредитной организации
     *
     * @return string
     */
    public function getIntShortName()
    {
        return $this->intShortName;
    }

    /**
     * Sets a new intShortName
     *
     * Сокращенное междкнародное наименование
     *  подраздеоления кредитной организации
     *
     * @param string $intShortName
     * @return static
     */
    public function setIntShortName($intShortName)
    {
        $this->intShortName = $intShortName;
        return $this;
    }


}

