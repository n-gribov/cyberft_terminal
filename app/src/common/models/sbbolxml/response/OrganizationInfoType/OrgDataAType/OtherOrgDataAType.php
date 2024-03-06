<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType;

/**
 * Class representing OtherOrgDataAType
 */
class OtherOrgDataAType
{

    /**
     * Идентификатор клиентской группы. Возможные значения: 1 -
     *  Индивидуальный предприниматель, 2 - Все остальные клиенты, (пусто)
     *
     * @property integer $clientGroupDepositOnline
     */
    private $clientGroupDepositOnline = null;

    /**
     * Полное международное наименование организации
     *
     * @property string $internationalName
     */
    private $internationalName = null;

    /**
     * Сокращенное международное наименование
     *
     * @property string $intShortName
     */
    private $intShortName = null;

    /**
     * Наименование организации в документах
     *
     * @property string $nameInDocs
     */
    private $nameInDocs = null;

    /**
     * Использовать наименование организации в документах
     *  0 - не использовать
     *  1 - использовать
     *
     * @property boolean $useNameInDocs
     */
    private $useNameInDocs = null;

    /**
     * Признак блокировки/ приостановки обслуживания организации
     *  по системе ДБО
     *
     *  Возможные значения: 0-признак не установлен, 1 – признак установлен
     *
     * @property boolean $locked
     */
    private $locked = null;

    /**
     * Признак "Не обслуживается по системе ДБО"
     *  1 - не обслуживается
     *  0 - обслуживается
     *
     * @property boolean $dboUse
     */
    private $dboUse = null;

    /**
     * Список КПП
     *
     * @property \common\models\sbbolxml\response\OrgKppType[] $orgKpp
     */
    private $orgKpp = array(
        
    );

    /**
     * Признак "Использовать подтвержденный справочник
     *  контрагентов"
     *  1 - использовать (чек выставлен)
     *  0 - не использовать
     *
     * @property boolean $dictContrUse
     */
    private $dictContrUse = null;

    /**
     * Признак "ФРОД-мониторинг"
     *  1 - используется
     *  0 - не используется
     *
     * @property boolean $frod
     */
    private $frod = null;

    /**
     * Признак "E-invoicing"
     *  1 - используется
     *  0 - не используется
     *
     * @property boolean $eInvoising
     */
    private $eInvoising = null;

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
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType $contacts
     */
    private $contacts = null;

    /**
     * Настройки валютного контроля
     *
     * @property \common\models\sbbolxml\response\CurrControlSettingsType $currControlSettings
     */
    private $currControlSettings = null;

    /**
     * Договоры внесения средств
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType $admContract
     */
    private $admContract = null;

    /**
     * Список идентификаторов организации в ЕПС
     *
     * @property string[] $ePSIds
     */
    private $ePSIds = null;

    /**
     * Контроль удаленного управлениия
     *
     * @property boolean $remoteAccessProtect
     */
    private $remoteAccessProtect = null;

    /**
     * Gets as clientGroupDepositOnline
     *
     * Идентификатор клиентской группы. Возможные значения: 1 -
     *  Индивидуальный предприниматель, 2 - Все остальные клиенты, (пусто)
     *
     * @return integer
     */
    public function getClientGroupDepositOnline()
    {
        return $this->clientGroupDepositOnline;
    }

    /**
     * Sets a new clientGroupDepositOnline
     *
     * Идентификатор клиентской группы. Возможные значения: 1 -
     *  Индивидуальный предприниматель, 2 - Все остальные клиенты, (пусто)
     *
     * @param integer $clientGroupDepositOnline
     * @return static
     */
    public function setClientGroupDepositOnline($clientGroupDepositOnline)
    {
        $this->clientGroupDepositOnline = $clientGroupDepositOnline;
        return $this;
    }

    /**
     * Gets as internationalName
     *
     * Полное международное наименование организации
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
     * Полное международное наименование организации
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
     * Gets as intShortName
     *
     * Сокращенное международное наименование
     *
     * @return string
     */
    public function getIntShortName()
    {
        return $this->intShortName;
    }

    /**
     * Sets a new intShortName
     *
     * Сокращенное международное наименование
     *
     * @param string $intShortName
     * @return static
     */
    public function setIntShortName($intShortName)
    {
        $this->intShortName = $intShortName;
        return $this;
    }

    /**
     * Gets as nameInDocs
     *
     * Наименование организации в документах
     *
     * @return string
     */
    public function getNameInDocs()
    {
        return $this->nameInDocs;
    }

    /**
     * Sets a new nameInDocs
     *
     * Наименование организации в документах
     *
     * @param string $nameInDocs
     * @return static
     */
    public function setNameInDocs($nameInDocs)
    {
        $this->nameInDocs = $nameInDocs;
        return $this;
    }

    /**
     * Gets as useNameInDocs
     *
     * Использовать наименование организации в документах
     *  0 - не использовать
     *  1 - использовать
     *
     * @return boolean
     */
    public function getUseNameInDocs()
    {
        return $this->useNameInDocs;
    }

    /**
     * Sets a new useNameInDocs
     *
     * Использовать наименование организации в документах
     *  0 - не использовать
     *  1 - использовать
     *
     * @param boolean $useNameInDocs
     * @return static
     */
    public function setUseNameInDocs($useNameInDocs)
    {
        $this->useNameInDocs = $useNameInDocs;
        return $this;
    }

    /**
     * Gets as locked
     *
     * Признак блокировки/ приостановки обслуживания организации
     *  по системе ДБО
     *
     *  Возможные значения: 0-признак не установлен, 1 – признак установлен
     *
     * @return boolean
     */
    public function getLocked()
    {
        return $this->locked;
    }

    /**
     * Sets a new locked
     *
     * Признак блокировки/ приостановки обслуживания организации
     *  по системе ДБО
     *
     *  Возможные значения: 0-признак не установлен, 1 – признак установлен
     *
     * @param boolean $locked
     * @return static
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;
        return $this;
    }

    /**
     * Gets as dboUse
     *
     * Признак "Не обслуживается по системе ДБО"
     *  1 - не обслуживается
     *  0 - обслуживается
     *
     * @return boolean
     */
    public function getDboUse()
    {
        return $this->dboUse;
    }

    /**
     * Sets a new dboUse
     *
     * Признак "Не обслуживается по системе ДБО"
     *  1 - не обслуживается
     *  0 - обслуживается
     *
     * @param boolean $dboUse
     * @return static
     */
    public function setDboUse($dboUse)
    {
        $this->dboUse = $dboUse;
        return $this;
    }

    /**
     * Adds as orgKpp
     *
     * Список КПП
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
     * Список КПП
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
     * Список КПП
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
     * Список КПП
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
     * Список КПП
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
     * Gets as dictContrUse
     *
     * Признак "Использовать подтвержденный справочник
     *  контрагентов"
     *  1 - использовать (чек выставлен)
     *  0 - не использовать
     *
     * @return boolean
     */
    public function getDictContrUse()
    {
        return $this->dictContrUse;
    }

    /**
     * Sets a new dictContrUse
     *
     * Признак "Использовать подтвержденный справочник
     *  контрагентов"
     *  1 - использовать (чек выставлен)
     *  0 - не использовать
     *
     * @param boolean $dictContrUse
     * @return static
     */
    public function setDictContrUse($dictContrUse)
    {
        $this->dictContrUse = $dictContrUse;
        return $this;
    }

    /**
     * Gets as frod
     *
     * Признак "ФРОД-мониторинг"
     *  1 - используется
     *  0 - не используется
     *
     * @return boolean
     */
    public function getFrod()
    {
        return $this->frod;
    }

    /**
     * Sets a new frod
     *
     * Признак "ФРОД-мониторинг"
     *  1 - используется
     *  0 - не используется
     *
     * @param boolean $frod
     * @return static
     */
    public function setFrod($frod)
    {
        $this->frod = $frod;
        return $this;
    }

    /**
     * Gets as eInvoising
     *
     * Признак "E-invoicing"
     *  1 - используется
     *  0 - не используется
     *
     * @return boolean
     */
    public function getEInvoising()
    {
        return $this->eInvoising;
    }

    /**
     * Sets a new eInvoising
     *
     * Признак "E-invoicing"
     *  1 - используется
     *  0 - не используется
     *
     * @param boolean $eInvoising
     * @return static
     */
    public function setEInvoising($eInvoising)
    {
        $this->eInvoising = $eInvoising;
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
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType
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
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType $contacts
     * @return static
     */
    public function setContacts(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\ContactsAType $contacts)
    {
        $this->contacts = $contacts;
        return $this;
    }

    /**
     * Gets as currControlSettings
     *
     * Настройки валютного контроля
     *
     * @return \common\models\sbbolxml\response\CurrControlSettingsType
     */
    public function getCurrControlSettings()
    {
        return $this->currControlSettings;
    }

    /**
     * Sets a new currControlSettings
     *
     * Настройки валютного контроля
     *
     * @param \common\models\sbbolxml\response\CurrControlSettingsType $currControlSettings
     * @return static
     */
    public function setCurrControlSettings(\common\models\sbbolxml\response\CurrControlSettingsType $currControlSettings)
    {
        $this->currControlSettings = $currControlSettings;
        return $this;
    }

    /**
     * Gets as admContract
     *
     * Договоры внесения средств
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType
     */
    public function getAdmContract()
    {
        return $this->admContract;
    }

    /**
     * Sets a new admContract
     *
     * Договоры внесения средств
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType $admContract
     * @return static
     */
    public function setAdmContract(\common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\OtherOrgDataAType\AdmContractAType $admContract)
    {
        $this->admContract = $admContract;
        return $this;
    }

    /**
     * Adds as ePSId
     *
     * Список идентификаторов организации в ЕПС
     *
     * @return static
     * @param string $ePSId
     */
    public function addToEPSIds($ePSId)
    {
        $this->ePSIds[] = $ePSId;
        return $this;
    }

    /**
     * isset ePSIds
     *
     * Список идентификаторов организации в ЕПС
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetEPSIds($index)
    {
        return isset($this->ePSIds[$index]);
    }

    /**
     * unset ePSIds
     *
     * Список идентификаторов организации в ЕПС
     *
     * @param scalar $index
     * @return void
     */
    public function unsetEPSIds($index)
    {
        unset($this->ePSIds[$index]);
    }

    /**
     * Gets as ePSIds
     *
     * Список идентификаторов организации в ЕПС
     *
     * @return string[]
     */
    public function getEPSIds()
    {
        return $this->ePSIds;
    }

    /**
     * Sets a new ePSIds
     *
     * Список идентификаторов организации в ЕПС
     *
     * @param string[] $ePSIds
     * @return static
     */
    public function setEPSIds(array $ePSIds)
    {
        $this->ePSIds = $ePSIds;
        return $this;
    }

    /**
     * Gets as remoteAccessProtect
     *
     * Контроль удаленного управлениия
     *
     * @return boolean
     */
    public function getRemoteAccessProtect()
    {
        return $this->remoteAccessProtect;
    }

    /**
     * Sets a new remoteAccessProtect
     *
     * Контроль удаленного управлениия
     *
     * @param boolean $remoteAccessProtect
     * @return static
     */
    public function setRemoteAccessProtect($remoteAccessProtect)
    {
        $this->remoteAccessProtect = $remoteAccessProtect;
        return $this;
    }


}

