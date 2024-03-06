<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing ProfitReasonInfoType
 *
 * Сведения об основаниях, свидетельствующих о том, что Клиент действует к выгоде другого лица
 * XSD Type: ProfitReasonInfo
 */
class ProfitReasonInfoType
{

    /**
     * Наименование договора
     *
     * @property string $agreementName
     */
    private $agreementName = null;

    /**
     * Номер
     *
     * @property string $agreementNumber
     */
    private $agreementNumber = null;

    /**
     * Дата заключения
     *
     * @property \DateTime $conclusionDate
     */
    private $conclusionDate = null;

    /**
     * Gets as agreementName
     *
     * Наименование договора
     *
     * @return string
     */
    public function getAgreementName()
    {
        return $this->agreementName;
    }

    /**
     * Sets a new agreementName
     *
     * Наименование договора
     *
     * @param string $agreementName
     * @return static
     */
    public function setAgreementName($agreementName)
    {
        $this->agreementName = $agreementName;
        return $this;
    }

    /**
     * Gets as agreementNumber
     *
     * Номер
     *
     * @return string
     */
    public function getAgreementNumber()
    {
        return $this->agreementNumber;
    }

    /**
     * Sets a new agreementNumber
     *
     * Номер
     *
     * @param string $agreementNumber
     * @return static
     */
    public function setAgreementNumber($agreementNumber)
    {
        $this->agreementNumber = $agreementNumber;
        return $this;
    }

    /**
     * Gets as conclusionDate
     *
     * Дата заключения
     *
     * @return \DateTime
     */
    public function getConclusionDate()
    {
        return $this->conclusionDate;
    }

    /**
     * Sets a new conclusionDate
     *
     * Дата заключения
     *
     * @param \DateTime $conclusionDate
     * @return static
     */
    public function setConclusionDate(\DateTime $conclusionDate)
    {
        $this->conclusionDate = $conclusionDate;
        return $this;
    }


}

