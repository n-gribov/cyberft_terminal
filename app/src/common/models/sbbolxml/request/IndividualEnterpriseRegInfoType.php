<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing IndividualEnterpriseRegInfoType
 *
 * Сведения о регистрации в качестве индивидуального предпринимателя
 * XSD Type: IndividualEnterpriseRegInfo
 */
class IndividualEnterpriseRegInfoType
{

    /**
     * 12.1 Регистрационный номер (ОГРНИП)
     *
     * @property string $regNumORGRNIP
     */
    private $regNumORGRNIP = null;

    /**
     * 12.2 Дата государственной регистрации
     *
     * @property \DateTime $stateRegDate
     */
    private $stateRegDate = null;

    /**
     * 12.3 Наименование регистрирующего органа
     *
     * @property string $regAuthorityName
     */
    private $regAuthorityName = null;

    /**
     * 12.4 Место регистрации
     *
     * @property string $regPlace
     */
    private $regPlace = null;

    /**
     * Gets as regNumORGRNIP
     *
     * 12.1 Регистрационный номер (ОГРНИП)
     *
     * @return string
     */
    public function getRegNumORGRNIP()
    {
        return $this->regNumORGRNIP;
    }

    /**
     * Sets a new regNumORGRNIP
     *
     * 12.1 Регистрационный номер (ОГРНИП)
     *
     * @param string $regNumORGRNIP
     * @return static
     */
    public function setRegNumORGRNIP($regNumORGRNIP)
    {
        $this->regNumORGRNIP = $regNumORGRNIP;
        return $this;
    }

    /**
     * Gets as stateRegDate
     *
     * 12.2 Дата государственной регистрации
     *
     * @return \DateTime
     */
    public function getStateRegDate()
    {
        return $this->stateRegDate;
    }

    /**
     * Sets a new stateRegDate
     *
     * 12.2 Дата государственной регистрации
     *
     * @param \DateTime $stateRegDate
     * @return static
     */
    public function setStateRegDate(\DateTime $stateRegDate)
    {
        $this->stateRegDate = $stateRegDate;
        return $this;
    }

    /**
     * Gets as regAuthorityName
     *
     * 12.3 Наименование регистрирующего органа
     *
     * @return string
     */
    public function getRegAuthorityName()
    {
        return $this->regAuthorityName;
    }

    /**
     * Sets a new regAuthorityName
     *
     * 12.3 Наименование регистрирующего органа
     *
     * @param string $regAuthorityName
     * @return static
     */
    public function setRegAuthorityName($regAuthorityName)
    {
        $this->regAuthorityName = $regAuthorityName;
        return $this;
    }

    /**
     * Gets as regPlace
     *
     * 12.4 Место регистрации
     *
     * @return string
     */
    public function getRegPlace()
    {
        return $this->regPlace;
    }

    /**
     * Sets a new regPlace
     *
     * 12.4 Место регистрации
     *
     * @param string $regPlace
     * @return static
     */
    public function setRegPlace($regPlace)
    {
        $this->regPlace = $regPlace;
        return $this;
    }


}

