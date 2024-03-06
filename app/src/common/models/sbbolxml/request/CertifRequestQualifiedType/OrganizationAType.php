<?php

namespace common\models\sbbolxml\request\CertifRequestQualifiedType;

/**
 * Class representing OrganizationAType
 */
class OrganizationAType
{

    /**
     * Краткое наименование организации.
     *
     * @property string $shortName
     */
    private $shortName = null;

    /**
     * Полное наименование организации
     *
     * @property string $fullName
     */
    private $fullName = null;

    /**
     * ОГРН (ОГРНИП) ЮЛ/ИП. Сотрудником ИП поле не заполняется.
     *
     * @property string $oGRN
     */
    private $oGRN = null;

    /**
     * ИНН ЮЛ/ИП/сотрудника ИП. Например: «772807592836».
     *
     * @property string $iNN
     */
    private $iNN = null;

    /**
     * ОКАТО
     *
     * @property string $oKATO
     */
    private $oKATO = null;

    /**
     * БИК
     *
     * @property string $bIC
     */
    private $bIC = null;

    /**
     * Адрес электронной почты
     *
     * @property string $email
     */
    private $email = null;

    /**
     * Свидетельство о регистрации
     *
     * @property string $regCert
     */
    private $regCert = null;

    /**
     * Почтовый индекс из юридического адреса организации
     *
     * @property string $mailIndex
     */
    private $mailIndex = null;

    /**
     * 2-х символьный код страны местонахождения ЮЛ/ИП/сотрудника ИП
     *  (согласно
     *  ГОСТ 7.67-2003 ISO 3166-1:1997).
     *  Например, для «Российской федерации» - “RU”.
     *
     * @property string $country
     */
    private $country = null;

    /**
     * Наименование страны из справочника стран (из поля NAME)
     *
     * @property string $countryName
     */
    private $countryName = null;

    /**
     * Код региона из справочника КЛАДР
     *
     * @property string $codeKladr
     */
    private $codeKladr = null;

    /**
     * Регион (область) местонахождения ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «Воронежская область».
     *
     * @property string $region
     */
    private $region = null;

    /**
     * Город (населенный пункт) местонахождения ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «г. Воронеж».
     *
     * @property string $locality
     */
    private $locality = null;

    /**
     * Название улицы, номер дома местонахождения ЮЛ/ИП/сотрудника
     *  ИП. Например:
     *  «ул. Января, д. 8, к. 5, пом.
     *  42».
     *
     * @property string $street
     */
    private $street = null;

    /**
     * Флаг «Прошу зарегистрировать меня в ЕСИА»
     *
     *  0 – регистрация не требуется,
     *
     *  1 – требуется с оповещением на email,
     *
     *  2 – требуется с оповещением на телефон
     *
     * @property integer $regSignHolderAgree
     */
    private $regSignHolderAgree = null;

    /**
     * Gets as shortName
     *
     * Краткое наименование организации.
     *
     * @return string
     */
    public function getShortName()
    {
        return $this->shortName;
    }

    /**
     * Sets a new shortName
     *
     * Краткое наименование организации.
     *
     * @param string $shortName
     * @return static
     */
    public function setShortName($shortName)
    {
        $this->shortName = $shortName;
        return $this;
    }

    /**
     * Gets as fullName
     *
     * Полное наименование организации
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
     * Полное наименование организации
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
     * Gets as oGRN
     *
     * ОГРН (ОГРНИП) ЮЛ/ИП. Сотрудником ИП поле не заполняется.
     *
     * @return string
     */
    public function getOGRN()
    {
        return $this->oGRN;
    }

    /**
     * Sets a new oGRN
     *
     * ОГРН (ОГРНИП) ЮЛ/ИП. Сотрудником ИП поле не заполняется.
     *
     * @param string $oGRN
     * @return static
     */
    public function setOGRN($oGRN)
    {
        $this->oGRN = $oGRN;
        return $this;
    }

    /**
     * Gets as iNN
     *
     * ИНН ЮЛ/ИП/сотрудника ИП. Например: «772807592836».
     *
     * @return string
     */
    public function getINN()
    {
        return $this->iNN;
    }

    /**
     * Sets a new iNN
     *
     * ИНН ЮЛ/ИП/сотрудника ИП. Например: «772807592836».
     *
     * @param string $iNN
     * @return static
     */
    public function setINN($iNN)
    {
        $this->iNN = $iNN;
        return $this;
    }

    /**
     * Gets as oKATO
     *
     * ОКАТО
     *
     * @return string
     */
    public function getOKATO()
    {
        return $this->oKATO;
    }

    /**
     * Sets a new oKATO
     *
     * ОКАТО
     *
     * @param string $oKATO
     * @return static
     */
    public function setOKATO($oKATO)
    {
        $this->oKATO = $oKATO;
        return $this;
    }

    /**
     * Gets as bIC
     *
     * БИК
     *
     * @return string
     */
    public function getBIC()
    {
        return $this->bIC;
    }

    /**
     * Sets a new bIC
     *
     * БИК
     *
     * @param string $bIC
     * @return static
     */
    public function setBIC($bIC)
    {
        $this->bIC = $bIC;
        return $this;
    }

    /**
     * Gets as email
     *
     * Адрес электронной почты
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets a new email
     *
     * Адрес электронной почты
     *
     * @param string $email
     * @return static
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Gets as regCert
     *
     * Свидетельство о регистрации
     *
     * @return string
     */
    public function getRegCert()
    {
        return $this->regCert;
    }

    /**
     * Sets a new regCert
     *
     * Свидетельство о регистрации
     *
     * @param string $regCert
     * @return static
     */
    public function setRegCert($regCert)
    {
        $this->regCert = $regCert;
        return $this;
    }

    /**
     * Gets as mailIndex
     *
     * Почтовый индекс из юридического адреса организации
     *
     * @return string
     */
    public function getMailIndex()
    {
        return $this->mailIndex;
    }

    /**
     * Sets a new mailIndex
     *
     * Почтовый индекс из юридического адреса организации
     *
     * @param string $mailIndex
     * @return static
     */
    public function setMailIndex($mailIndex)
    {
        $this->mailIndex = $mailIndex;
        return $this;
    }

    /**
     * Gets as country
     *
     * 2-х символьный код страны местонахождения ЮЛ/ИП/сотрудника ИП
     *  (согласно
     *  ГОСТ 7.67-2003 ISO 3166-1:1997).
     *  Например, для «Российской федерации» - “RU”.
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Sets a new country
     *
     * 2-х символьный код страны местонахождения ЮЛ/ИП/сотрудника ИП
     *  (согласно
     *  ГОСТ 7.67-2003 ISO 3166-1:1997).
     *  Например, для «Российской федерации» - “RU”.
     *
     * @param string $country
     * @return static
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Gets as countryName
     *
     * Наименование страны из справочника стран (из поля NAME)
     *
     * @return string
     */
    public function getCountryName()
    {
        return $this->countryName;
    }

    /**
     * Sets a new countryName
     *
     * Наименование страны из справочника стран (из поля NAME)
     *
     * @param string $countryName
     * @return static
     */
    public function setCountryName($countryName)
    {
        $this->countryName = $countryName;
        return $this;
    }

    /**
     * Gets as codeKladr
     *
     * Код региона из справочника КЛАДР
     *
     * @return string
     */
    public function getCodeKladr()
    {
        return $this->codeKladr;
    }

    /**
     * Sets a new codeKladr
     *
     * Код региона из справочника КЛАДР
     *
     * @param string $codeKladr
     * @return static
     */
    public function setCodeKladr($codeKladr)
    {
        $this->codeKladr = $codeKladr;
        return $this;
    }

    /**
     * Gets as region
     *
     * Регион (область) местонахождения ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «Воронежская область».
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Sets a new region
     *
     * Регион (область) местонахождения ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «Воронежская область».
     *
     * @param string $region
     * @return static
     */
    public function setRegion($region)
    {
        $this->region = $region;
        return $this;
    }

    /**
     * Gets as locality
     *
     * Город (населенный пункт) местонахождения ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «г. Воронеж».
     *
     * @return string
     */
    public function getLocality()
    {
        return $this->locality;
    }

    /**
     * Sets a new locality
     *
     * Город (населенный пункт) местонахождения ЮЛ/ИП/сотрудника ИП.
     *  Например:
     *  «г. Воронеж».
     *
     * @param string $locality
     * @return static
     */
    public function setLocality($locality)
    {
        $this->locality = $locality;
        return $this;
    }

    /**
     * Gets as street
     *
     * Название улицы, номер дома местонахождения ЮЛ/ИП/сотрудника
     *  ИП. Например:
     *  «ул. Января, д. 8, к. 5, пом.
     *  42».
     *
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Sets a new street
     *
     * Название улицы, номер дома местонахождения ЮЛ/ИП/сотрудника
     *  ИП. Например:
     *  «ул. Января, д. 8, к. 5, пом.
     *  42».
     *
     * @param string $street
     * @return static
     */
    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    /**
     * Gets as regSignHolderAgree
     *
     * Флаг «Прошу зарегистрировать меня в ЕСИА»
     *
     *  0 – регистрация не требуется,
     *
     *  1 – требуется с оповещением на email,
     *
     *  2 – требуется с оповещением на телефон
     *
     * @return integer
     */
    public function getRegSignHolderAgree()
    {
        return $this->regSignHolderAgree;
    }

    /**
     * Sets a new regSignHolderAgree
     *
     * Флаг «Прошу зарегистрировать меня в ЕСИА»
     *
     *  0 – регистрация не требуется,
     *
     *  1 – требуется с оповещением на email,
     *
     *  2 – требуется с оповещением на телефон
     *
     * @param integer $regSignHolderAgree
     * @return static
     */
    public function setRegSignHolderAgree($regSignHolderAgree)
    {
        $this->regSignHolderAgree = $regSignHolderAgree;
        return $this;
    }


}

