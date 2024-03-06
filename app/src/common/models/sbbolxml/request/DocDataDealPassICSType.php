<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DocDataDealPassICSType
 *
 * Общие реквизиты ведомости банковского контроля
 * XSD Type: DocDataDealPassICS
 */
class DocDataDealPassICSType
{

    /**
     * В случае досыла документов ссылка на первоначальный документ
     *
     * @property string $docExtGuid
     */
    private $docExtGuid = null;

    /**
     * Дата создания документа по местному времени в формате ДД.ММ.ГГГГ
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер сформированного документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Признак перехода контракта (кредитного договора) по уступке:
     *  0 - признак не установлен
     *  1 - признак установлен
     *
     * @property boolean $transferDebtRights
     */
    private $transferDebtRights = null;

    /**
     * Данные ответственного исполнителя
     *
     * @property \common\models\sbbolxml\request\AuthPersICSType $authPers
     */
    private $authPers = null;

    /**
     * Gets as docExtGuid
     *
     * В случае досыла документов ссылка на первоначальный документ
     *
     * @return string
     */
    public function getDocExtGuid()
    {
        return $this->docExtGuid;
    }

    /**
     * Sets a new docExtGuid
     *
     * В случае досыла документов ссылка на первоначальный документ
     *
     * @param string $docExtGuid
     * @return static
     */
    public function setDocExtGuid($docExtGuid)
    {
        $this->docExtGuid = $docExtGuid;
        return $this;
    }

    /**
     * Gets as docDate
     *
     * Дата создания документа по местному времени в формате ДД.ММ.ГГГГ
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
     * Дата создания документа по местному времени в формате ДД.ММ.ГГГГ
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
     * Номер сформированного документа
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
     * Номер сформированного документа
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
     * Gets as transferDebtRights
     *
     * Признак перехода контракта (кредитного договора) по уступке:
     *  0 - признак не установлен
     *  1 - признак установлен
     *
     * @return boolean
     */
    public function getTransferDebtRights()
    {
        return $this->transferDebtRights;
    }

    /**
     * Sets a new transferDebtRights
     *
     * Признак перехода контракта (кредитного договора) по уступке:
     *  0 - признак не установлен
     *  1 - признак установлен
     *
     * @param boolean $transferDebtRights
     * @return static
     */
    public function setTransferDebtRights($transferDebtRights)
    {
        $this->transferDebtRights = $transferDebtRights;
        return $this;
    }

    /**
     * Gets as authPers
     *
     * Данные ответственного исполнителя
     *
     * @return \common\models\sbbolxml\request\AuthPersICSType
     */
    public function getAuthPers()
    {
        return $this->authPers;
    }

    /**
     * Sets a new authPers
     *
     * Данные ответственного исполнителя
     *
     * @param \common\models\sbbolxml\request\AuthPersICSType $authPers
     * @return static
     */
    public function setAuthPers(\common\models\sbbolxml\request\AuthPersICSType $authPers)
    {
        $this->authPers = $authPers;
        return $this;
    }


}

