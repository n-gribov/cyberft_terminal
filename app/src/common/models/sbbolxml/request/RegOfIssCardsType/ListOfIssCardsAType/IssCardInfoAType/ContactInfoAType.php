<?php

namespace common\models\sbbolxml\request\RegOfIssCardsType\ListOfIssCardsAType\IssCardInfoAType;

/**
 * Class representing ContactInfoAType
 */
class ContactInfoAType
{

    /**
     * @property string $homePhone
     */
    private $homePhone = null;

    /**
     * @property string $officePhone
     */
    private $officePhone = null;

    /**
     * @property \common\models\sbbolxml\request\MobPhoneType $mobPhone
     */
    private $mobPhone = null;

    /**
     * @property string $email
     */
    private $email = null;

    /**
     * Gets as homePhone
     *
     * @return string
     */
    public function getHomePhone()
    {
        return $this->homePhone;
    }

    /**
     * Sets a new homePhone
     *
     * @param string $homePhone
     * @return static
     */
    public function setHomePhone($homePhone)
    {
        $this->homePhone = $homePhone;
        return $this;
    }

    /**
     * Gets as officePhone
     *
     * @return string
     */
    public function getOfficePhone()
    {
        return $this->officePhone;
    }

    /**
     * Sets a new officePhone
     *
     * @param string $officePhone
     * @return static
     */
    public function setOfficePhone($officePhone)
    {
        $this->officePhone = $officePhone;
        return $this;
    }

    /**
     * Gets as mobPhone
     *
     * @return \common\models\sbbolxml\request\MobPhoneType
     */
    public function getMobPhone()
    {
        return $this->mobPhone;
    }

    /**
     * Sets a new mobPhone
     *
     * @param \common\models\sbbolxml\request\MobPhoneType $mobPhone
     * @return static
     */
    public function setMobPhone(\common\models\sbbolxml\request\MobPhoneType $mobPhone)
    {
        $this->mobPhone = $mobPhone;
        return $this;
    }

    /**
     * Gets as email
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
     * @param string $email
     * @return static
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }


}

