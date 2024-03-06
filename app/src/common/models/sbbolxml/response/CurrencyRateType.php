<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CurrencyRateType
 *
 * Запись о базовой валюте для валютной пары для кросс –курса/ запись о валюте, курс которой установлен (например USD в паре USD/RUB)
 * XSD Type: CurrencyRate
 */
class CurrencyRateType
{

    /**
     * Код базовой валюты. Буквенно-цифровой код валюты согласно Общероссийскому классификатору валют (Доллары США -840 , Евро -978 и т.д.)Поле «Цифровой код валюты» для конкретного курса
     *
     * @property string $currBaseCode
     */
    private $currBaseCode = null;

    /**
     * Масштаб валюты.
     *
     * @property string $currUnits
     */
    private $currUnits = null;

    /**
     * Дата выставления курса
     *
     * @property \DateTime $courseDateTime
     */
    private $courseDateTime = null;

    /**
     * Поле «Курс ЦБ РФ» для конкретного курса
     *
     * @property string $valueRate
     */
    private $valueRate = null;

    /**
     * Поле «Курс покупки» для конкретного курса
     *
     * @property string $rateBuyRate
     */
    private $rateBuyRate = null;

    /**
     * Поле «Курс продажи» для конкретного курса
     *
     * @property string $rateSellRate
     */
    private $rateSellRate = null;

    /**
     * Gets as currBaseCode
     *
     * Код базовой валюты. Буквенно-цифровой код валюты согласно Общероссийскому классификатору валют (Доллары США -840 , Евро -978 и т.д.)Поле «Цифровой код валюты» для конкретного курса
     *
     * @return string
     */
    public function getCurrBaseCode()
    {
        return $this->currBaseCode;
    }

    /**
     * Sets a new currBaseCode
     *
     * Код базовой валюты. Буквенно-цифровой код валюты согласно Общероссийскому классификатору валют (Доллары США -840 , Евро -978 и т.д.)Поле «Цифровой код валюты» для конкретного курса
     *
     * @param string $currBaseCode
     * @return static
     */
    public function setCurrBaseCode($currBaseCode)
    {
        $this->currBaseCode = $currBaseCode;
        return $this;
    }

    /**
     * Gets as currUnits
     *
     * Масштаб валюты.
     *
     * @return string
     */
    public function getCurrUnits()
    {
        return $this->currUnits;
    }

    /**
     * Sets a new currUnits
     *
     * Масштаб валюты.
     *
     * @param string $currUnits
     * @return static
     */
    public function setCurrUnits($currUnits)
    {
        $this->currUnits = $currUnits;
        return $this;
    }

    /**
     * Gets as courseDateTime
     *
     * Дата выставления курса
     *
     * @return \DateTime
     */
    public function getCourseDateTime()
    {
        return $this->courseDateTime;
    }

    /**
     * Sets a new courseDateTime
     *
     * Дата выставления курса
     *
     * @param \DateTime $courseDateTime
     * @return static
     */
    public function setCourseDateTime(\DateTime $courseDateTime)
    {
        $this->courseDateTime = $courseDateTime;
        return $this;
    }

    /**
     * Gets as valueRate
     *
     * Поле «Курс ЦБ РФ» для конкретного курса
     *
     * @return string
     */
    public function getValueRate()
    {
        return $this->valueRate;
    }

    /**
     * Sets a new valueRate
     *
     * Поле «Курс ЦБ РФ» для конкретного курса
     *
     * @param string $valueRate
     * @return static
     */
    public function setValueRate($valueRate)
    {
        $this->valueRate = $valueRate;
        return $this;
    }

    /**
     * Gets as rateBuyRate
     *
     * Поле «Курс покупки» для конкретного курса
     *
     * @return string
     */
    public function getRateBuyRate()
    {
        return $this->rateBuyRate;
    }

    /**
     * Sets a new rateBuyRate
     *
     * Поле «Курс покупки» для конкретного курса
     *
     * @param string $rateBuyRate
     * @return static
     */
    public function setRateBuyRate($rateBuyRate)
    {
        $this->rateBuyRate = $rateBuyRate;
        return $this;
    }

    /**
     * Gets as rateSellRate
     *
     * Поле «Курс продажи» для конкретного курса
     *
     * @return string
     */
    public function getRateSellRate()
    {
        return $this->rateSellRate;
    }

    /**
     * Sets a new rateSellRate
     *
     * Поле «Курс продажи» для конкретного курса
     *
     * @param string $rateSellRate
     * @return static
     */
    public function setRateSellRate($rateSellRate)
    {
        $this->rateSellRate = $rateSellRate;
        return $this;
    }


}

