<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType;

/**
 * Class representing AuthPersonAType
 */
class AuthPersonAType
{

    /**
     * @property boolean $blocked
     */
    private $blocked = null;

    /**
     * Системное имя
     *
     * @property string $login
     */
    private $login = null;

    /**
     * Физическое лицо, ФИО
     *
     * @property string $fIO
     */
    private $fIO = null;

    /**
     * id Организации
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Признак "Аутентификация одноразовым паролем"
     *
     * @property boolean $oneTimePassword
     */
    private $oneTimePassword = null;

    /**
     * Телефон контактного лица по вопросам перевода
     *
     * @property string $tel
     */
    private $tel = null;

    /**
     * Криптопрофили, доступные учетной записи
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType\SignDeviceAType[] $signDevices
     */
    private $signDevices = null;

    /**
     * Идентификатор клиента в подсистеме Сервер нотификации
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\CHInfoAType $cHInfo
     */
    private $cHInfo = null;

    /**
     * Дополнительная информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Роли клиента
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\UserRolesAType\UserRoleAType[] $userRoles
     */
    private $userRoles = null;

    /**
     * Индентификатор пользователя
     *
     * @property integer $userId
     */
    private $userId = null;

    /**
     * Глобальный индентификатор пользователя
     *
     * @property string $userGuid
     */
    private $userGuid = null;

    /**
     * Ограничения учетной записи
     *
     * @property \common\models\sbbolxml\response\UserRestrictionType[] $userRestrictions
     */
    private $userRestrictions = null;

    /**
     * Сотрудник организации
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType $employee
     */
    private $employee = null;

    /**
     * Gets as blocked
     *
     * @return boolean
     */
    public function getBlocked()
    {
        return $this->blocked;
    }

    /**
     * Sets a new blocked
     *
     * @param boolean $blocked
     * @return static
     */
    public function setBlocked($blocked)
    {
        $this->blocked = $blocked;
        return $this;
    }

    /**
     * Gets as login
     *
     * Системное имя
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Sets a new login
     *
     * Системное имя
     *
     * @param string $login
     * @return static
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    /**
     * Gets as fIO
     *
     * Физическое лицо, ФИО
     *
     * @return string
     */
    public function getFIO()
    {
        return $this->fIO;
    }

    /**
     * Sets a new fIO
     *
     * Физическое лицо, ФИО
     *
     * @param string $fIO
     * @return static
     */
    public function setFIO($fIO)
    {
        $this->fIO = $fIO;
        return $this;
    }

    /**
     * Gets as orgId
     *
     * id Организации
     *
     * @return string
     */
    public function getOrgId()
    {
        return $this->orgId;
    }

    /**
     * Sets a new orgId
     *
     * id Организации
     *
     * @param string $orgId
     * @return static
     */
    public function setOrgId($orgId)
    {
        $this->orgId = $orgId;
        return $this;
    }

    /**
     * Gets as oneTimePassword
     *
     * Признак "Аутентификация одноразовым паролем"
     *
     * @return boolean
     */
    public function getOneTimePassword()
    {
        return $this->oneTimePassword;
    }

    /**
     * Sets a new oneTimePassword
     *
     * Признак "Аутентификация одноразовым паролем"
     *
     * @param boolean $oneTimePassword
     * @return static
     */
    public function setOneTimePassword($oneTimePassword)
    {
        $this->oneTimePassword = $oneTimePassword;
        return $this;
    }

    /**
     * Gets as tel
     *
     * Телефон контактного лица по вопросам перевода
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Sets a new tel
     *
     * Телефон контактного лица по вопросам перевода
     *
     * @param string $tel
     * @return static
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
        return $this;
    }

    /**
     * Adds as signDevice
     *
     * Криптопрофили, доступные учетной записи
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType\SignDeviceAType $signDevice
     */
    public function addToSignDevices(\common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType\SignDeviceAType $signDevice)
    {
        $this->signDevices[] = $signDevice;
        return $this;
    }

    /**
     * isset signDevices
     *
     * Криптопрофили, доступные учетной записи
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSignDevices($index)
    {
        return isset($this->signDevices[$index]);
    }

    /**
     * unset signDevices
     *
     * Криптопрофили, доступные учетной записи
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSignDevices($index)
    {
        unset($this->signDevices[$index]);
    }

    /**
     * Gets as signDevices
     *
     * Криптопрофили, доступные учетной записи
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType\SignDeviceAType[]
     */
    public function getSignDevices()
    {
        return $this->signDevices;
    }

    /**
     * Sets a new signDevices
     *
     * Криптопрофили, доступные учетной записи
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\SignDevicesAType\SignDeviceAType[] $signDevices
     * @return static
     */
    public function setSignDevices(array $signDevices)
    {
        $this->signDevices = $signDevices;
        return $this;
    }

    /**
     * Gets as cHInfo
     *
     * Идентификатор клиента в подсистеме Сервер нотификации
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\CHInfoAType
     */
    public function getCHInfo()
    {
        return $this->cHInfo;
    }

    /**
     * Sets a new cHInfo
     *
     * Идентификатор клиента в подсистеме Сервер нотификации
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\CHInfoAType $cHInfo
     * @return static
     */
    public function setCHInfo(\common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\CHInfoAType $cHInfo)
    {
        $this->cHInfo = $cHInfo;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Дополнительная информация
     *
     * @return string
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * Дополнительная информация
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }

    /**
     * Adds as userRole
     *
     * Роли клиента
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\UserRolesAType\UserRoleAType $userRole
     */
    public function addToUserRoles(\common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\UserRolesAType\UserRoleAType $userRole)
    {
        $this->userRoles[] = $userRole;
        return $this;
    }

    /**
     * isset userRoles
     *
     * Роли клиента
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetUserRoles($index)
    {
        return isset($this->userRoles[$index]);
    }

    /**
     * unset userRoles
     *
     * Роли клиента
     *
     * @param scalar $index
     * @return void
     */
    public function unsetUserRoles($index)
    {
        unset($this->userRoles[$index]);
    }

    /**
     * Gets as userRoles
     *
     * Роли клиента
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\UserRolesAType\UserRoleAType[]
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Sets a new userRoles
     *
     * Роли клиента
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\UserRolesAType\UserRoleAType[] $userRoles
     * @return static
     */
    public function setUserRoles(array $userRoles)
    {
        $this->userRoles = $userRoles;
        return $this;
    }

    /**
     * Gets as userId
     *
     * Индентификатор пользователя
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Sets a new userId
     *
     * Индентификатор пользователя
     *
     * @param integer $userId
     * @return static
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * Gets as userGuid
     *
     * Глобальный индентификатор пользователя
     *
     * @return string
     */
    public function getUserGuid()
    {
        return $this->userGuid;
    }

    /**
     * Sets a new userGuid
     *
     * Глобальный индентификатор пользователя
     *
     * @param string $userGuid
     * @return static
     */
    public function setUserGuid($userGuid)
    {
        $this->userGuid = $userGuid;
        return $this;
    }

    /**
     * Adds as userRestriction
     *
     * Ограничения учетной записи
     *
     * @return static
     * @param \common\models\sbbolxml\response\UserRestrictionType $userRestriction
     */
    public function addToUserRestrictions(\common\models\sbbolxml\response\UserRestrictionType $userRestriction)
    {
        $this->userRestrictions[] = $userRestriction;
        return $this;
    }

    /**
     * isset userRestrictions
     *
     * Ограничения учетной записи
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetUserRestrictions($index)
    {
        return isset($this->userRestrictions[$index]);
    }

    /**
     * unset userRestrictions
     *
     * Ограничения учетной записи
     *
     * @param scalar $index
     * @return void
     */
    public function unsetUserRestrictions($index)
    {
        unset($this->userRestrictions[$index]);
    }

    /**
     * Gets as userRestrictions
     *
     * Ограничения учетной записи
     *
     * @return \common\models\sbbolxml\response\UserRestrictionType[]
     */
    public function getUserRestrictions()
    {
        return $this->userRestrictions;
    }

    /**
     * Sets a new userRestrictions
     *
     * Ограничения учетной записи
     *
     * @param \common\models\sbbolxml\response\UserRestrictionType[] $userRestrictions
     * @return static
     */
    public function setUserRestrictions(array $userRestrictions)
    {
        $this->userRestrictions = $userRestrictions;
        return $this;
    }

    /**
     * Gets as employee
     *
     * Сотрудник организации
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Sets a new employee
     *
     * Сотрудник организации
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType $employee
     * @return static
     */
    public function setEmployee(\common\models\sbbolxml\response\OrganizationInfoType\AuthPersonsAType\AuthPersonAType\EmployeeAType $employee)
    {
        $this->employee = $employee;
        return $this;
    }


}

