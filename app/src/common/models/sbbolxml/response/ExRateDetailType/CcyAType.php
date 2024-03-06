<?php

namespace common\models\sbbolxml\response\ExRateDetailType;

/**
 * Class representing CcyAType
 */
class CcyAType
{

    /**
     * Буквенно-цифровой код валюты согласно Общероссийскому классификатору валют (Доллары США -840 , Евро -978 и т.д.)
     *
     * @property string $currName
     */
    private $currName = null;

    /**
     * Цифровой код валюты ISO
     *
     * @property string $currIsoCode
     */
    private $currIsoCode = null;

    /**
     * Цифровой код валюты
     *
     * @property string $currCode
     */
    private $currCode = null;

    /**
     * Дата и время, на которые устанавливается курс, оставлена для совместимости
     *
     * @property \DateTime $beginDate
     */
    private $beginDate = null;

    /**
     * Gets as currName
     *
     * Буквенно-цифровой код валюты согласно Общероссийскому классификатору валют (Доллары США -840 , Евро -978 и т.д.)
     *
     * @return string
     */
    public function getCurrName()
    {
        return $this->currName;
    }

    /**
     * Sets a new currName
     *
     * Буквенно-цифровой код валюты согласно Общероссийскому классификатору валют (Доллары США -840 , Евро -978 и т.д.)
     *
     * @param string $currName
     * @return static
     */
    public function setCurrName($currName)
    {
        $this->currName = $currName;
        return $this;
    }

    /**
     * Gets as currIsoCode
     *
     * Цифровой код валюты ISO
     *
     * @return string
     */
    public function getCurrIsoCode()
    {
        return $this->currIsoCode;
    }

    /**
     * Sets a new currIsoCode
     *
     * Цифровой код валюты ISO
     *
     * @param string $currIsoCode
     * @return static
     */
    public function setCurrIsoCode($currIsoCode)
    {
        $this->currIsoCode = $currIsoCode;
        return $this;
    }

    /**
     * Gets as currCode
     *
     * Цифровой код валюты
     *
     * @return string
     */
    public function getCurrCode()
    {
        return $this->currCode;
    }

    /**
     * Sets a new currCode
     *
     * Цифровой код валюты
     *
     * @param string $currCode
     * @return static
     */
    public function setCurrCode($currCode)
    {
        $this->currCode = $currCode;
        return $this;
    }

    /**
     * Gets as beginDate
     *
     * Дата и время, на которые устанавливается курс, оставлена для совместимости
     *
     * @return \DateTime
     */
    public function getBeginDate()
    {
        return $this->beginDate;
    }

    /**
     * Sets a new beginDate
     *
     * Дата и время, на которые устанавливается курс, оставлена для совместимости
     *
     * @param \DateTime $beginDate
     * @return static
     */
    public function setBeginDate(\DateTime $beginDate)
    {
        $this->beginDate = $beginDate;
        return $this;
    }


}

