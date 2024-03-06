<?php

namespace common\models\sbbolxml\response\OrgInfoType\OrgDataAType;

/**
 * Class representing OtherOrgDataAType
 */
class OtherOrgDataAType
{

    /**
     * Признак приостановки обслуживания организации по системе
     *  ДБО
     *  Возможные значения: 0-признак не установлен, 1 – признак установлен
     *
     * @property string $locked
     */
    private $locked = null;

    /**
     * @property \common\models\sbbolxml\response\OrgKppType[] $orgKpp
     */
    private $orgKpp = array(
        
    );

    /**
     * Международное наименование организации
     *
     * @property string $internationalName
     */
    private $internationalName = null;

    /**
     * Код удостоверяющего центра. Идентификатор организации в
     *  удостоверяющем центре.
     *
     * @property string $certAuthId
     */
    private $certAuthId = null;

    /**
     * Номер последнего порядкового номера сертификата в
     *  удостоверяющем центре
     *
     * @property string $lastCertifNum
     */
    private $lastCertifNum = null;

    /**
     * Содержит информацию о контактных лицах организации
     *
     * @property \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType $contacts
     */
    private $contacts = null;

    /**
     * Gets as locked
     *
     * Признак приостановки обслуживания организации по системе
     *  ДБО
     *  Возможные значения: 0-признак не установлен, 1 – признак установлен
     *
     * @return string
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Sets a new locked
     *
     * Признак приостановки обслуживания организации по системе
     *  ДБО
     *  Возможные значения: 0-признак не установлен, 1 – признак установлен
     *
     * @param string $locked
     * @return static
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * Adds as orgKpp
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgKppType $orgKpp
     */
    public function addToOrgKpp(\common\models\sbbolxml\response\OrgKppType $orgKpp)
    {
        $this->orgKpp[] = $orgKpp;
        return $this;
    }

    /**
     * isset orgKpp
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOrgKpp($index)
    {
        return isset($this->orgKpp[$index]);
    }

    /**
     * unset orgKpp
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOrgKpp($index)
    {
        unset($this->orgKpp[$index]);
    }

    /**
     * Gets as orgKpp
     *
     * @return \common\models\sbbolxml\response\OrgKppType[]
     */
    public function getOrgKpp()
    {
        return $this->orgKpp;
    }

    /**
     * Sets a new orgKpp
     *
     * @param \common\models\sbbolxml\response\OrgKppType[] $orgKpp
     * @return static
     */
    public function setOrgKpp(array $orgKpp)
    {
        $this->orgKpp = $orgKpp;
        return $this;
    }

    /**
     * Gets as internationalName
     *
     * Международное наименование организации
     *
     * @return string
     */
    public function getInternationalName()
    {
        return $this->internationalName;
    }

    /**
     * Sets a new internationalName
     *
     * Международное наименование организации
     *
     * @param string $internationalName
     * @return static
     */
    public function setInternationalName($internationalName)
    {
        $this->internationalName = $internationalName;
        return $this;
    }

    /**
     * Gets as certAuthId
     *
     * Код удостоверяющего центра. Идентификатор организации в
     *  удостоверяющем центре.
     *
     * @return string
     */
    public function getCertAuthId()
    {
        return $this->certAuthId;
    }

    /**
     * Sets a new certAuthId
     *
     * Код удостоверяющего центра. Идентификатор организации в
     *  удостоверяющем центре.
     *
     * @param string $certAuthId
     * @return static
     */
    public function setCertAuthId($certAuthId)
    {
        $this->certAuthId = $certAuthId;
        return $this;
    }

    /**
     * Gets as lastCertifNum
     *
     * Номер последнего порядкового номера сертификата в
     *  удостоверяющем центре
     *
     * @return string
     */
    public function getLastCertifNum()
    {
        return $this->lastCertifNum;
    }

    /**
     * Sets a new lastCertifNum
     *
     * Номер последнего порядкового номера сертификата в
     *  удостоверяющем центре
     *
     * @param string $lastCertifNum
     * @return static
     */
    public function setLastCertifNum($lastCertifNum)
    {
        $this->lastCertifNum = $lastCertifNum;
        return $this;
    }

    /**
     * Gets as contacts
     *
     * Содержит информацию о контактных лицах организации
     *
     * @return \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Sets a new contacts
     *
     * Содержит информацию о контактных лицах организации
     *
     * @param \common\models\sbbolxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType $contacts
     * @return static
     */
    public function setContacts(\common\models\sbbolxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType $contacts)
    {
        $this->contacts = $contacts;
        return $this;
    }


}

