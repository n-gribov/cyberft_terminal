<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing PersonalInfoType
 *
 *
 * XSD Type: PersonalInfo
 */
class PersonalInfoType
{

    /**
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для получения персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @property string $contractAccessCode
     */
    private $contractAccessCode = null;

    /**
     * Идентификатор организации (для холдингов)
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Признак того, что необходимо выгружать информацию о сотрудниках организации
     *
     * @property boolean $includeEmployees
     */
    private $includeEmployees = null;

    /**
     * Идентификатор последнего имеющегося редактирования справочника бенефициаров
     *
     * @property integer $beneficiariesStepId
     */
    private $beneficiariesStepId = null;

    /**
     * Идентификатор последнего имеющегося редактирования справочника корреспондентов
     *
     * @property integer $correspondentsStepId
     */
    private $correspondentsStepId = null;

    /**
     * Gets as contractAccessCode
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для получения персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @return string
     */
    public function getContractAccessCode()
    {
        return $this->contractAccessCode;
    }

    /**
     * Sets a new contractAccessCode
     *
     * ТОЛЬКО ДЛЯ ТК. Идентификатор, который используется для получения персональных данных.
     *  (DBOContractChannel.accessCode)
     *
     * @param string $contractAccessCode
     * @return static
     */
    public function setContractAccessCode($contractAccessCode)
    {
        $this->contractAccessCode = $contractAccessCode;
        return $this;
    }

    /**
     * Gets as orgId
     *
     * Идентификатор организации (для холдингов)
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
     * Идентификатор организации (для холдингов)
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
     * Gets as includeEmployees
     *
     * Признак того, что необходимо выгружать информацию о сотрудниках организации
     *
     * @return boolean
     */
    public function getIncludeEmployees()
    {
        return $this->includeEmployees;
    }

    /**
     * Sets a new includeEmployees
     *
     * Признак того, что необходимо выгружать информацию о сотрудниках организации
     *
     * @param boolean $includeEmployees
     * @return static
     */
    public function setIncludeEmployees($includeEmployees)
    {
        $this->includeEmployees = $includeEmployees;
        return $this;
    }

    /**
     * Gets as beneficiariesStepId
     *
     * Идентификатор последнего имеющегося редактирования справочника бенефициаров
     *
     * @return integer
     */
    public function getBeneficiariesStepId()
    {
        return $this->beneficiariesStepId;
    }

    /**
     * Sets a new beneficiariesStepId
     *
     * Идентификатор последнего имеющегося редактирования справочника бенефициаров
     *
     * @param integer $beneficiariesStepId
     * @return static
     */
    public function setBeneficiariesStepId($beneficiariesStepId)
    {
        $this->beneficiariesStepId = $beneficiariesStepId;
        return $this;
    }

    /**
     * Gets as correspondentsStepId
     *
     * Идентификатор последнего имеющегося редактирования справочника корреспондентов
     *
     * @return integer
     */
    public function getCorrespondentsStepId()
    {
        return $this->correspondentsStepId;
    }

    /**
     * Sets a new correspondentsStepId
     *
     * Идентификатор последнего имеющегося редактирования справочника корреспондентов
     *
     * @param integer $correspondentsStepId
     * @return static
     */
    public function setCorrespondentsStepId($correspondentsStepId)
    {
        $this->correspondentsStepId = $correspondentsStepId;
        return $this;
    }


}

