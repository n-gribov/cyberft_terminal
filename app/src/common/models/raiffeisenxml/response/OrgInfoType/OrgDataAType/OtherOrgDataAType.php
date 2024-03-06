<?php

namespace common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType;

/**
 * Class representing OtherOrgDataAType
 */
class OtherOrgDataAType
{

    /**
     * Признак приостановки обслуживания организации по системе ДБО
     *
     *  Возможные значения: 0-признак не установлен, 1 – признак установлен
     *
     * @property string $locked
     */
    private $locked = null;

    /**
     * @property \common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType $contacts
     */
    private $contacts = null;

    /**
     * @property \common\models\raiffeisenxml\response\OrgKppType[] $orgKpps
     */
    private $orgKpps = null;

    /**
     * Международное наименование организации
     *
     * @property string $internationalName
     */
    private $internationalName = null;

    /**
     * Полное или сокр. наименование ВК (справки)
     *
     * @property string $curControlCertificatesName
     */
    private $curControlCertificatesName = null;

    /**
     * Полное или сокр. наименование ВК (ПС)
     *
     * @property string $curControlPassportsName
     */
    private $curControlPassportsName = null;

    /**
     * Код удостоверяющего центра. Идентификатор организации в удостоверяющем центре.
     *
     * @property string $certAuthId
     */
    private $certAuthId = null;

    /**
     * Номер последнего порядкового номера сертификата в удостоверяющем центре
     *
     * @property string $lastCertifNum
     */
    private $lastCertifNum = null;

    /**
     * Gets as locked
     *
     * Признак приостановки обслуживания организации по системе ДБО
     *
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
     * Признак приостановки обслуживания организации по системе ДБО
     *
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
     * Gets as contacts
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * Sets a new contacts
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType $contacts
     * @return static
     */
    public function setContacts(\common\models\raiffeisenxml\response\OrgInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType $contacts)
    {
        $this->contacts = $contacts;
        return $this;
    }

    /**
     * Adds as orgKpp
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgKppType $orgKpp
     */
    public function addToOrgKpps(\common\models\raiffeisenxml\response\OrgKppType $orgKpp)
    {
        $this->orgKpps[] = $orgKpp;
        return $this;
    }

    /**
     * isset orgKpps
     *
     * @param int|string $index
     * @return bool
     */
    public function issetOrgKpps($index)
    {
        return isset($this->orgKpps[$index]);
    }

    /**
     * unset orgKpps
     *
     * @param int|string $index
     * @return void
     */
    public function unsetOrgKpps($index)
    {
        unset($this->orgKpps[$index]);
    }

    /**
     * Gets as orgKpps
     *
     * @return \common\models\raiffeisenxml\response\OrgKppType[]
     */
    public function getOrgKpps()
    {
        return $this->orgKpps;
    }

    /**
     * Sets a new orgKpps
     *
     * @param \common\models\raiffeisenxml\response\OrgKppType[] $orgKpps
     * @return static
     */
    public function setOrgKpps(array $orgKpps)
    {
        $this->orgKpps = $orgKpps;
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
     * Gets as curControlCertificatesName
     *
     * Полное или сокр. наименование ВК (справки)
     *
     * @return string
     */
    public function getCurControlCertificatesName()
    {
        return $this->curControlCertificatesName;
    }

    /**
     * Sets a new curControlCertificatesName
     *
     * Полное или сокр. наименование ВК (справки)
     *
     * @param string $curControlCertificatesName
     * @return static
     */
    public function setCurControlCertificatesName($curControlCertificatesName)
    {
        $this->curControlCertificatesName = $curControlCertificatesName;
        return $this;
    }

    /**
     * Gets as curControlPassportsName
     *
     * Полное или сокр. наименование ВК (ПС)
     *
     * @return string
     */
    public function getCurControlPassportsName()
    {
        return $this->curControlPassportsName;
    }

    /**
     * Sets a new curControlPassportsName
     *
     * Полное или сокр. наименование ВК (ПС)
     *
     * @param string $curControlPassportsName
     * @return static
     */
    public function setCurControlPassportsName($curControlPassportsName)
    {
        $this->curControlPassportsName = $curControlPassportsName;
        return $this;
    }

    /**
     * Gets as certAuthId
     *
     * Код удостоверяющего центра. Идентификатор организации в удостоверяющем центре.
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
     * Код удостоверяющего центра. Идентификатор организации в удостоверяющем центре.
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
     * Номер последнего порядкового номера сертификата в удостоверяющем центре
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
     * Номер последнего порядкового номера сертификата в удостоверяющем центре
     *
     * @param string $lastCertifNum
     * @return static
     */
    public function setLastCertifNum($lastCertifNum)
    {
        $this->lastCertifNum = $lastCertifNum;
        return $this;
    }


}

