<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CorpCardExtContactInfoType
 *
 * Контактная информация держателя КК
 * XSD Type: CorpCardExtContactInfo
 */
class CorpCardExtContactInfoType
{

    /**
     * Домашний номер телефона
     *
     * @property \common\models\sbbolxml\request\PhoneNumWithCodeType $phoneHome
     */
    private $phoneHome = null;

    /**
     * Рабочий номер телефона
     *
     * @property \common\models\sbbolxml\request\PhoneNumWithCodeType $phoneWork
     */
    private $phoneWork = null;

    /**
     * Мобильный номер телефона
     *
     * @property \common\models\sbbolxml\request\PhoneNumWithCodeType $phoneMobile
     */
    private $phoneMobile = null;

    /**
     * Адрес электронной почты
     *
     * @property string $emailAddr
     */
    private $emailAddr = null;

    /**
     * Gets as phoneHome
     *
     * Домашний номер телефона
     *
     * @return \common\models\sbbolxml\request\PhoneNumWithCodeType
     */
    public function getPhoneHome()
    {
        return $this->phoneHome;
    }

    /**
     * Sets a new phoneHome
     *
     * Домашний номер телефона
     *
     * @param \common\models\sbbolxml\request\PhoneNumWithCodeType $phoneHome
     * @return static
     */
    public function setPhoneHome(\common\models\sbbolxml\request\PhoneNumWithCodeType $phoneHome)
    {
        $this->phoneHome = $phoneHome;
        return $this;
    }

    /**
     * Gets as phoneWork
     *
     * Рабочий номер телефона
     *
     * @return \common\models\sbbolxml\request\PhoneNumWithCodeType
     */
    public function getPhoneWork()
    {
        return $this->phoneWork;
    }

    /**
     * Sets a new phoneWork
     *
     * Рабочий номер телефона
     *
     * @param \common\models\sbbolxml\request\PhoneNumWithCodeType $phoneWork
     * @return static
     */
    public function setPhoneWork(\common\models\sbbolxml\request\PhoneNumWithCodeType $phoneWork)
    {
        $this->phoneWork = $phoneWork;
        return $this;
    }

    /**
     * Gets as phoneMobile
     *
     * Мобильный номер телефона
     *
     * @return \common\models\sbbolxml\request\PhoneNumWithCodeType
     */
    public function getPhoneMobile()
    {
        return $this->phoneMobile;
    }

    /**
     * Sets a new phoneMobile
     *
     * Мобильный номер телефона
     *
     * @param \common\models\sbbolxml\request\PhoneNumWithCodeType $phoneMobile
     * @return static
     */
    public function setPhoneMobile(\common\models\sbbolxml\request\PhoneNumWithCodeType $phoneMobile)
    {
        $this->phoneMobile = $phoneMobile;
        return $this;
    }

    /**
     * Gets as emailAddr
     *
     * Адрес электронной почты
     *
     * @return string
     */
    public function getEmailAddr()
    {
        return $this->emailAddr;
    }

    /**
     * Sets a new emailAddr
     *
     * Адрес электронной почты
     *
     * @param string $emailAddr
     * @return static
     */
    public function setEmailAddr($emailAddr)
    {
        $this->emailAddr = $emailAddr;
        return $this;
    }


}

