<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CorpCardHolderPersonInfoType
 *
 * Персональные Данные физического лица
 * XSD Type: CorpCardHolderPersonInfo
 */
class CorpCardHolderPersonInfoType
{

    /**
     * Номер в списке
     *
     * @property integer $lineNumber
     */
    private $lineNumber = null;

    /**
     * Идентификатор держателя КК в СББ
     *
     * @property string $extPersonId
     */
    private $extPersonId = null;

    /**
     * Фамилия физического лица
     *
     * @property string $sName
     */
    private $sName = null;

    /**
     * Имя физического лица
     *
     * @property string $name
     */
    private $name = null;

    /**
     * Отчество физического лица
     *
     * @property string $middleName
     */
    private $middleName = null;

    /**
     * Пол физического лица: 0 - мужской, 1 - женский
     *
     * @property boolean $sex
     */
    private $sex = null;

    /**
     * Дата рождения
     *
     * @property \DateTime $birthDt
     */
    private $birthDt = null;

    /**
     * ИНН физического лица
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * Обращение
     *
     * @property string $accost
     */
    private $accost = null;

    /**
     * Гражданство
     *
     * @property \common\models\sbbolxml\request\CitizenshipType $citizenship
     */
    private $citizenship = null;

    /**
     * 1 - резидент, 0 - нерезидент.
     *
     * @property boolean $resident
     */
    private $resident = null;

    /**
     * Кодовое слово
     *
     * @property string $keyword
     */
    private $keyword = null;

    /**
     * Эмбоссированная фамилия
     *
     * @property string $embossingSName
     */
    private $embossingSName = null;

    /**
     * Эмбоссированное имя
     *
     * @property string $embossingName
     */
    private $embossingName = null;

    /**
     * Адреса
     *
     * @property \common\models\sbbolxml\request\AddressType[] $postAddresses
     */
    private $postAddresses = array(
        
    );

    /**
     * Контактная информация держателя КК
     *
     * @property \common\models\sbbolxml\request\CorpCardExtContactInfoType $contactInfo
     */
    private $contactInfo = null;

    /**
     * Информация по документам держателя КК (ДУЛ)
     *
     * @property \common\models\sbbolxml\request\CorpCardExtDocInfoType $docInfo
     */
    private $docInfo = null;

    /**
     * Gets as lineNumber
     *
     * Номер в списке
     *
     * @return integer
     */
    public function getLineNumber()
    {
        return $this->lineNumber;
    }

    /**
     * Sets a new lineNumber
     *
     * Номер в списке
     *
     * @param integer $lineNumber
     * @return static
     */
    public function setLineNumber($lineNumber)
    {
        $this->lineNumber = $lineNumber;
        return $this;
    }

    /**
     * Gets as extPersonId
     *
     * Идентификатор держателя КК в СББ
     *
     * @return string
     */
    public function getExtPersonId()
    {
        return $this->extPersonId;
    }

    /**
     * Sets a new extPersonId
     *
     * Идентификатор держателя КК в СББ
     *
     * @param string $extPersonId
     * @return static
     */
    public function setExtPersonId($extPersonId)
    {
        $this->extPersonId = $extPersonId;
        return $this;
    }

    /**
     * Gets as sName
     *
     * Фамилия физического лица
     *
     * @return string
     */
    public function getSName()
    {
        return $this->sName;
    }

    /**
     * Sets a new sName
     *
     * Фамилия физического лица
     *
     * @param string $sName
     * @return static
     */
    public function setSName($sName)
    {
        $this->sName = $sName;
        return $this;
    }

    /**
     * Gets as name
     *
     * Имя физического лица
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Имя физического лица
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as middleName
     *
     * Отчество физического лица
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middleName;
    }

    /**
     * Sets a new middleName
     *
     * Отчество физического лица
     *
     * @param string $middleName
     * @return static
     */
    public function setMiddleName($middleName)
    {
        $this->middleName = $middleName;
        return $this;
    }

    /**
     * Gets as sex
     *
     * Пол физического лица: 0 - мужской, 1 - женский
     *
     * @return boolean
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Sets a new sex
     *
     * Пол физического лица: 0 - мужской, 1 - женский
     *
     * @param boolean $sex
     * @return static
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * Gets as birthDt
     *
     * Дата рождения
     *
     * @return \DateTime
     */
    public function getBirthDt()
    {
        return $this->birthDt;
    }

    /**
     * Sets a new birthDt
     *
     * Дата рождения
     *
     * @param \DateTime $birthDt
     * @return static
     */
    public function setBirthDt(\DateTime $birthDt)
    {
        $this->birthDt = $birthDt;
        return $this;
    }

    /**
     * Gets as inn
     *
     * ИНН физического лица
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Sets a new inn
     *
     * ИНН физического лица
     *
     * @param string $inn
     * @return static
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * Gets as accost
     *
     * Обращение
     *
     * @return string
     */
    public function getAccost()
    {
        return $this->accost;
    }

    /**
     * Sets a new accost
     *
     * Обращение
     *
     * @param string $accost
     * @return static
     */
    public function setAccost($accost)
    {
        $this->accost = $accost;
        return $this;
    }

    /**
     * Gets as citizenship
     *
     * Гражданство
     *
     * @return \common\models\sbbolxml\request\CitizenshipType
     */
    public function getCitizenship()
    {
        return $this->citizenship;
    }

    /**
     * Sets a new citizenship
     *
     * Гражданство
     *
     * @param \common\models\sbbolxml\request\CitizenshipType $citizenship
     * @return static
     */
    public function setCitizenship(\common\models\sbbolxml\request\CitizenshipType $citizenship)
    {
        $this->citizenship = $citizenship;
        return $this;
    }

    /**
     * Gets as resident
     *
     * 1 - резидент, 0 - нерезидент.
     *
     * @return boolean
     */
    public function getResident()
    {
        return $this->resident;
    }

    /**
     * Sets a new resident
     *
     * 1 - резидент, 0 - нерезидент.
     *
     * @param boolean $resident
     * @return static
     */
    public function setResident($resident)
    {
        $this->resident = $resident;
        return $this;
    }

    /**
     * Gets as keyword
     *
     * Кодовое слово
     *
     * @return string
     */
    public function getKeyword()
    {
        return $this->keyword;
    }

    /**
     * Sets a new keyword
     *
     * Кодовое слово
     *
     * @param string $keyword
     * @return static
     */
    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    /**
     * Gets as embossingSName
     *
     * Эмбоссированная фамилия
     *
     * @return string
     */
    public function getEmbossingSName()
    {
        return $this->embossingSName;
    }

    /**
     * Sets a new embossingSName
     *
     * Эмбоссированная фамилия
     *
     * @param string $embossingSName
     * @return static
     */
    public function setEmbossingSName($embossingSName)
    {
        $this->embossingSName = $embossingSName;
        return $this;
    }

    /**
     * Gets as embossingName
     *
     * Эмбоссированное имя
     *
     * @return string
     */
    public function getEmbossingName()
    {
        return $this->embossingName;
    }

    /**
     * Sets a new embossingName
     *
     * Эмбоссированное имя
     *
     * @param string $embossingName
     * @return static
     */
    public function setEmbossingName($embossingName)
    {
        $this->embossingName = $embossingName;
        return $this;
    }

    /**
     * Adds as postAddresses
     *
     * Адреса
     *
     * @return static
     * @param \common\models\sbbolxml\request\AddressType $postAddresses
     */
    public function addToPostAddresses(\common\models\sbbolxml\request\AddressType $postAddresses)
    {
        $this->postAddresses[] = $postAddresses;
        return $this;
    }

    /**
     * isset postAddresses
     *
     * Адреса
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPostAddresses($index)
    {
        return isset($this->postAddresses[$index]);
    }

    /**
     * unset postAddresses
     *
     * Адреса
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPostAddresses($index)
    {
        unset($this->postAddresses[$index]);
    }

    /**
     * Gets as postAddresses
     *
     * Адреса
     *
     * @return \common\models\sbbolxml\request\AddressType[]
     */
    public function getPostAddresses()
    {
        return $this->postAddresses;
    }

    /**
     * Sets a new postAddresses
     *
     * Адреса
     *
     * @param \common\models\sbbolxml\request\AddressType[] $postAddresses
     * @return static
     */
    public function setPostAddresses(array $postAddresses)
    {
        $this->postAddresses = $postAddresses;
        return $this;
    }

    /**
     * Gets as contactInfo
     *
     * Контактная информация держателя КК
     *
     * @return \common\models\sbbolxml\request\CorpCardExtContactInfoType
     */
    public function getContactInfo()
    {
        return $this->contactInfo;
    }

    /**
     * Sets a new contactInfo
     *
     * Контактная информация держателя КК
     *
     * @param \common\models\sbbolxml\request\CorpCardExtContactInfoType $contactInfo
     * @return static
     */
    public function setContactInfo(\common\models\sbbolxml\request\CorpCardExtContactInfoType $contactInfo)
    {
        $this->contactInfo = $contactInfo;
        return $this;
    }

    /**
     * Gets as docInfo
     *
     * Информация по документам держателя КК (ДУЛ)
     *
     * @return \common\models\sbbolxml\request\CorpCardExtDocInfoType
     */
    public function getDocInfo()
    {
        return $this->docInfo;
    }

    /**
     * Sets a new docInfo
     *
     * Информация по документам держателя КК (ДУЛ)
     *
     * @param \common\models\sbbolxml\request\CorpCardExtDocInfoType $docInfo
     * @return static
     */
    public function setDocInfo(\common\models\sbbolxml\request\CorpCardExtDocInfoType $docInfo)
    {
        $this->docInfo = $docInfo;
        return $this;
    }


}

