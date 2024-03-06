<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CashOrderRecipientInfoType
 *
 * Данные получателя заяки на получение наличных средств
 * XSD Type: CashOrderRecipientInfo
 */
class CashOrderRecipientInfoType
{

    /**
     * Фамилия физического лица
     *
     * @property string $lastName
     */
    private $lastName = null;

    /**
     * Имя физического лица
     *
     * @property string $firstName
     */
    private $firstName = null;

    /**
     * Отчество физического лица
     *
     * @property string $patronimic
     */
    private $patronimic = null;

    /**
     * Дата рождения
     *
     * @property \DateTime $dateOfBirth
     */
    private $dateOfBirth = null;

    /**
     * Реквизиты документа, удостоверяющего личность
     *
     * @property \common\models\sbbolxml\request\CashOrderRecipientDocInfoType $docInfo
     */
    private $docInfo = null;

    /**
     * Gets as lastName
     *
     * Фамилия физического лица
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Sets a new lastName
     *
     * Фамилия физического лица
     *
     * @param string $lastName
     * @return static
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Gets as firstName
     *
     * Имя физического лица
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Sets a new firstName
     *
     * Имя физического лица
     *
     * @param string $firstName
     * @return static
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Gets as patronimic
     *
     * Отчество физического лица
     *
     * @return string
     */
    public function getPatronimic()
    {
        return $this->patronimic;
    }

    /**
     * Sets a new patronimic
     *
     * Отчество физического лица
     *
     * @param string $patronimic
     * @return static
     */
    public function setPatronimic($patronimic)
    {
        $this->patronimic = $patronimic;
        return $this;
    }

    /**
     * Gets as dateOfBirth
     *
     * Дата рождения
     *
     * @return \DateTime
     */
    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    /**
     * Sets a new dateOfBirth
     *
     * Дата рождения
     *
     * @param \DateTime $dateOfBirth
     * @return static
     */
    public function setDateOfBirth(\DateTime $dateOfBirth)
    {
        $this->dateOfBirth = $dateOfBirth;
        return $this;
    }

    /**
     * Gets as docInfo
     *
     * Реквизиты документа, удостоверяющего личность
     *
     * @return \common\models\sbbolxml\request\CashOrderRecipientDocInfoType
     */
    public function getDocInfo()
    {
        return $this->docInfo;
    }

    /**
     * Sets a new docInfo
     *
     * Реквизиты документа, удостоверяющего личность
     *
     * @param \common\models\sbbolxml\request\CashOrderRecipientDocInfoType $docInfo
     * @return static
     */
    public function setDocInfo(\common\models\sbbolxml\request\CashOrderRecipientDocInfoType $docInfo)
    {
        $this->docInfo = $docInfo;
        return $this;
    }


}

