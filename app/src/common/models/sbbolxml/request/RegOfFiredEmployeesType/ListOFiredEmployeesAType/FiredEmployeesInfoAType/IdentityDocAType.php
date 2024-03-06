<?php

namespace common\models\sbbolxml\request\RegOfFiredEmployeesType\ListOFiredEmployeesAType\FiredEmployeesInfoAType;

/**
 * Class representing IdentityDocAType
 */
class IdentityDocAType
{

    /**
     * код вида документа по классификатору
     *  ФЕДЕРАЛЬНОЙ НАЛОГОВОЙ СЛУЖБЫ ПРИКАЗ от 13 октября 2006 г. N
     *  САЭ-3-04/706 "ОБ УТВЕРЖДЕНИИ ФОРМЫ СВЕДЕНИЙ О ДОХОДАХ
     *  ФИЗИЧЕСКИХ ЛИЦ"
     *
     * @property string $docTypeCode
     */
    private $docTypeCode = null;

    /**
     * Серия
     *
     * @property string $series
     */
    private $series = null;

    /**
     * Номер
     *
     * @property string $number
     */
    private $number = null;

    /**
     * Дата выдачи
     *
     * @property \DateTime $date
     */
    private $date = null;

    /**
     * Кем выдан
     *
     * @property string $branch
     */
    private $branch = null;

    /**
     * Код органа, выдавшего документ
     *
     * @property string $branchCode
     */
    private $branchCode = null;

    /**
     * Gets as docTypeCode
     *
     * код вида документа по классификатору
     *  ФЕДЕРАЛЬНОЙ НАЛОГОВОЙ СЛУЖБЫ ПРИКАЗ от 13 октября 2006 г. N
     *  САЭ-3-04/706 "ОБ УТВЕРЖДЕНИИ ФОРМЫ СВЕДЕНИЙ О ДОХОДАХ
     *  ФИЗИЧЕСКИХ ЛИЦ"
     *
     * @return string
     */
    public function getDocTypeCode()
    {
        return $this->docTypeCode;
    }

    /**
     * Sets a new docTypeCode
     *
     * код вида документа по классификатору
     *  ФЕДЕРАЛЬНОЙ НАЛОГОВОЙ СЛУЖБЫ ПРИКАЗ от 13 октября 2006 г. N
     *  САЭ-3-04/706 "ОБ УТВЕРЖДЕНИИ ФОРМЫ СВЕДЕНИЙ О ДОХОДАХ
     *  ФИЗИЧЕСКИХ ЛИЦ"
     *
     * @param string $docTypeCode
     * @return static
     */
    public function setDocTypeCode($docTypeCode)
    {
        $this->docTypeCode = $docTypeCode;
        return $this;
    }

    /**
     * Gets as series
     *
     * Серия
     *
     * @return string
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Sets a new series
     *
     * Серия
     *
     * @param string $series
     * @return static
     */
    public function setSeries($series)
    {
        $this->series = $series;
        return $this;
    }

    /**
     * Gets as number
     *
     * Номер
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Sets a new number
     *
     * Номер
     *
     * @param string $number
     * @return static
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }

    /**
     * Gets as date
     *
     * Дата выдачи
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Sets a new date
     *
     * Дата выдачи
     *
     * @param \DateTime $date
     * @return static
     */
    public function setDate(\DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Gets as branch
     *
     * Кем выдан
     *
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * Sets a new branch
     *
     * Кем выдан
     *
     * @param string $branch
     * @return static
     */
    public function setBranch($branch)
    {
        $this->branch = $branch;
        return $this;
    }

    /**
     * Gets as branchCode
     *
     * Код органа, выдавшего документ
     *
     * @return string
     */
    public function getBranchCode()
    {
        return $this->branchCode;
    }

    /**
     * Sets a new branchCode
     *
     * Код органа, выдавшего документ
     *
     * @param string $branchCode
     * @return static
     */
    public function setBranchCode($branchCode)
    {
        $this->branchCode = $branchCode;
        return $this;
    }


}

