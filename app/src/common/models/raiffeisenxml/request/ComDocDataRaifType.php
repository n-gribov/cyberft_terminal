<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing ComDocDataRaifType
 *
 *
 * XSD Type: ComDocDataRaif
 */
class ComDocDataRaifType
{

    /**
     * Дата составления документа
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Номер счета для определения подразделения, в которое направлен документ.
     *
     * @property string $accNum
     */
    private $accNum = null;

    /**
     * БИК для определения подразделения, в которое направлен документ.
     *
     * @property string $bic
     */
    private $bic = null;

    /**
     * Основные реквизиты организации, указываемые в документе
     *
     * @property \common\models\raiffeisenxml\request\OrgDataType $orgData
     */
    private $orgData = null;

    /**
     * Уполномоченный сотрудник организации клиента
     *
     * @property \common\models\raiffeisenxml\request\AuthPersCCType $authPers
     */
    private $authPers = null;

    /**
     * Gets as docDate
     *
     * Дата составления документа
     *
     * @return \DateTime
     */
    public function getDocDate()
    {
        return $this->docDate;
    }

    /**
     * Sets a new docDate
     *
     * Дата составления документа
     *
     * @param \DateTime $docDate
     * @return static
     */
    public function setDocDate(\DateTime $docDate)
    {
        $this->docDate = $docDate;
        return $this;
    }

    /**
     * Gets as docNum
     *
     * Номер документа
     *
     * @return string
     */
    public function getDocNum()
    {
        return $this->docNum;
    }

    /**
     * Sets a new docNum
     *
     * Номер документа
     *
     * @param string $docNum
     * @return static
     */
    public function setDocNum($docNum)
    {
        $this->docNum = $docNum;
        return $this;
    }

    /**
     * Gets as accNum
     *
     * Номер счета для определения подразделения, в которое направлен документ.
     *
     * @return string
     */
    public function getAccNum()
    {
        return $this->accNum;
    }

    /**
     * Sets a new accNum
     *
     * Номер счета для определения подразделения, в которое направлен документ.
     *
     * @param string $accNum
     * @return static
     */
    public function setAccNum($accNum)
    {
        $this->accNum = $accNum;
        return $this;
    }

    /**
     * Gets as bic
     *
     * БИК для определения подразделения, в которое направлен документ.
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * БИК для определения подразделения, в которое направлен документ.
     *
     * @param string $bic
     * @return static
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as orgData
     *
     * Основные реквизиты организации, указываемые в документе
     *
     * @return \common\models\raiffeisenxml\request\OrgDataType
     */
    public function getOrgData()
    {
        return $this->orgData;
    }

    /**
     * Sets a new orgData
     *
     * Основные реквизиты организации, указываемые в документе
     *
     * @param \common\models\raiffeisenxml\request\OrgDataType $orgData
     * @return static
     */
    public function setOrgData(\common\models\raiffeisenxml\request\OrgDataType $orgData)
    {
        $this->orgData = $orgData;
        return $this;
    }

    /**
     * Gets as authPers
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @return \common\models\raiffeisenxml\request\AuthPersCCType
     */
    public function getAuthPers()
    {
        return $this->authPers;
    }

    /**
     * Sets a new authPers
     *
     * Уполномоченный сотрудник организации клиента
     *
     * @param \common\models\raiffeisenxml\request\AuthPersCCType $authPers
     * @return static
     */
    public function setAuthPers(\common\models\raiffeisenxml\request\AuthPersCCType $authPers)
    {
        $this->authPers = $authPers;
        return $this;
    }


}

