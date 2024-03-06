<?php

namespace common\models\raiffeisenxml\response\TicketType;

/**
 * Class representing InfoAType
 */
class InfoAType
{

    /**
     * Код состояния документа
     *
     * @property string $statusStateCode
     */
    private $statusStateCode = null;

    /**
     * Идентификатор организации
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Банковские даты
     *
     * @property \common\models\raiffeisenxml\response\BankDateType $bankDate
     */
    private $bankDate = null;

    /**
     * @property \common\models\raiffeisenxml\response\TicketType\InfoAType\MsgFromBankAType $msgFromBank
     */
    private $msgFromBank = null;

    /**
     * @property \common\models\raiffeisenxml\response\TicketType\InfoAType\AddInfoAType $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as statusStateCode
     *
     * Код состояния документа
     *
     * @return string
     */
    public function getStatusStateCode()
    {
        return $this->statusStateCode;
    }

    /**
     * Sets a new statusStateCode
     *
     * Код состояния документа
     *
     * @param string $statusStateCode
     * @return static
     */
    public function setStatusStateCode($statusStateCode)
    {
        $this->statusStateCode = $statusStateCode;
        return $this;
    }

    /**
     * Gets as orgId
     *
     * Идентификатор организации
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
     * Идентификатор организации
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
     * Gets as docExtId
     *
     * Идентификатор документа в УС
     *
     * @return string
     */
    public function getDocExtId()
    {
        return $this->docExtId;
    }

    /**
     * Sets a new docExtId
     *
     * Идентификатор документа в УС
     *
     * @param string $docExtId
     * @return static
     */
    public function setDocExtId($docExtId)
    {
        $this->docExtId = $docExtId;
        return $this;
    }

    /**
     * Gets as bankDate
     *
     * Банковские даты
     *
     * @return \common\models\raiffeisenxml\response\BankDateType
     */
    public function getBankDate()
    {
        return $this->bankDate;
    }

    /**
     * Sets a new bankDate
     *
     * Банковские даты
     *
     * @param \common\models\raiffeisenxml\response\BankDateType $bankDate
     * @return static
     */
    public function setBankDate(\common\models\raiffeisenxml\response\BankDateType $bankDate)
    {
        $this->bankDate = $bankDate;
        return $this;
    }

    /**
     * Gets as msgFromBank
     *
     * @return \common\models\raiffeisenxml\response\TicketType\InfoAType\MsgFromBankAType
     */
    public function getMsgFromBank()
    {
        return $this->msgFromBank;
    }

    /**
     * Sets a new msgFromBank
     *
     * @param \common\models\raiffeisenxml\response\TicketType\InfoAType\MsgFromBankAType $msgFromBank
     * @return static
     */
    public function setMsgFromBank(\common\models\raiffeisenxml\response\TicketType\InfoAType\MsgFromBankAType $msgFromBank)
    {
        $this->msgFromBank = $msgFromBank;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * @return \common\models\raiffeisenxml\response\TicketType\InfoAType\AddInfoAType
     */
    public function getAddInfo()
    {
        return $this->addInfo;
    }

    /**
     * Sets a new addInfo
     *
     * @param \common\models\raiffeisenxml\response\TicketType\InfoAType\AddInfoAType $addInfo
     * @return static
     */
    public function setAddInfo(\common\models\raiffeisenxml\response\TicketType\InfoAType\AddInfoAType $addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}

