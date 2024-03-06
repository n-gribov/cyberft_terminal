<?php

namespace common\models\sbbolxml\response\AccountRubType\OtherAccountDataAType;

/**
 * Class representing AddInfoSBKAType
 */
class AddInfoSBKAType
{

    /**
     * Подключение счета к договору о предосталении информации по счетам клиента
     *
     * @property boolean $accClientInfo
     */
    private $accClientInfo = null;

    /**
     * Подключение счета к договору о предосталении информации для контроля операций по счетам клиента
     *
     * @property boolean $controlOper
     */
    private $controlOper = null;

    /**
     * Подключение счета к дополнителдьному согласшению к договору банковского счета о контроле и акцепте платежных документов
     *
     * @property boolean $acceptPP
     */
    private $acceptPP = null;

    /**
     * Подключение счета к соглашению о лимите дебетового счета
     *
     * @property boolean $debetLimit
     */
    private $debetLimit = null;

    /**
     * Подключение счета к соглашению о предосталении услуги "Единый остаток"
     *
     * @property boolean $singleResidue
     */
    private $singleResidue = null;

    /**
     * Заключено доп соглашение к договору банковского счета по Единому остатку
     *
     * @property boolean $addAgree
     */
    private $addAgree = null;

    /**
     * Подключение счета к соглашению о предосталении услуги овердрафт с общим лимитом
     *
     * @property boolean $overdraft
     */
    private $overdraft = null;

    /**
     * Подключение счета к договору о прямом управлении счетами
     *
     * @property boolean $directControl
     */
    private $directControl = null;

    /**
     * Подключение счета к дополнительному соглашению к договору банковского счета о прямом управлении счетами
     *
     * @property boolean $addDirectControl
     */
    private $addDirectControl = null;

    /**
     * Идентификатор счета в РЦК (во внешней системе)
     *
     * @property string $rzkId
     */
    private $rzkId = null;

    /**
     * Идентификатор типа счета в РЦК (во внешней системе)
     *
     * @property string $rzkOrgAccTypeId
     */
    private $rzkOrgAccTypeId = null;

    /**
     * Идентификатор организации счета в РЦК (во внешней системе)
     *
     * @property string $rzkOrgId
     */
    private $rzkOrgId = null;

    /**
     * Gets as accClientInfo
     *
     * Подключение счета к договору о предосталении информации по счетам клиента
     *
     * @return boolean
     */
    public function getAccClientInfo()
    {
        return $this->accClientInfo;
    }

    /**
     * Sets a new accClientInfo
     *
     * Подключение счета к договору о предосталении информации по счетам клиента
     *
     * @param boolean $accClientInfo
     * @return static
     */
    public function setAccClientInfo($accClientInfo)
    {
        $this->accClientInfo = $accClientInfo;
        return $this;
    }

    /**
     * Gets as controlOper
     *
     * Подключение счета к договору о предосталении информации для контроля операций по счетам клиента
     *
     * @return boolean
     */
    public function getControlOper()
    {
        return $this->controlOper;
    }

    /**
     * Sets a new controlOper
     *
     * Подключение счета к договору о предосталении информации для контроля операций по счетам клиента
     *
     * @param boolean $controlOper
     * @return static
     */
    public function setControlOper($controlOper)
    {
        $this->controlOper = $controlOper;
        return $this;
    }

    /**
     * Gets as acceptPP
     *
     * Подключение счета к дополнителдьному согласшению к договору банковского счета о контроле и акцепте платежных документов
     *
     * @return boolean
     */
    public function getAcceptPP()
    {
        return $this->acceptPP;
    }

    /**
     * Sets a new acceptPP
     *
     * Подключение счета к дополнителдьному согласшению к договору банковского счета о контроле и акцепте платежных документов
     *
     * @param boolean $acceptPP
     * @return static
     */
    public function setAcceptPP($acceptPP)
    {
        $this->acceptPP = $acceptPP;
        return $this;
    }

    /**
     * Gets as debetLimit
     *
     * Подключение счета к соглашению о лимите дебетового счета
     *
     * @return boolean
     */
    public function getDebetLimit()
    {
        return $this->debetLimit;
    }

    /**
     * Sets a new debetLimit
     *
     * Подключение счета к соглашению о лимите дебетового счета
     *
     * @param boolean $debetLimit
     * @return static
     */
    public function setDebetLimit($debetLimit)
    {
        $this->debetLimit = $debetLimit;
        return $this;
    }

    /**
     * Gets as singleResidue
     *
     * Подключение счета к соглашению о предосталении услуги "Единый остаток"
     *
     * @return boolean
     */
    public function getSingleResidue()
    {
        return $this->singleResidue;
    }

    /**
     * Sets a new singleResidue
     *
     * Подключение счета к соглашению о предосталении услуги "Единый остаток"
     *
     * @param boolean $singleResidue
     * @return static
     */
    public function setSingleResidue($singleResidue)
    {
        $this->singleResidue = $singleResidue;
        return $this;
    }

    /**
     * Gets as addAgree
     *
     * Заключено доп соглашение к договору банковского счета по Единому остатку
     *
     * @return boolean
     */
    public function getAddAgree()
    {
        return $this->addAgree;
    }

    /**
     * Sets a new addAgree
     *
     * Заключено доп соглашение к договору банковского счета по Единому остатку
     *
     * @param boolean $addAgree
     * @return static
     */
    public function setAddAgree($addAgree)
    {
        $this->addAgree = $addAgree;
        return $this;
    }

    /**
     * Gets as overdraft
     *
     * Подключение счета к соглашению о предосталении услуги овердрафт с общим лимитом
     *
     * @return boolean
     */
    public function getOverdraft()
    {
        return $this->overdraft;
    }

    /**
     * Sets a new overdraft
     *
     * Подключение счета к соглашению о предосталении услуги овердрафт с общим лимитом
     *
     * @param boolean $overdraft
     * @return static
     */
    public function setOverdraft($overdraft)
    {
        $this->overdraft = $overdraft;
        return $this;
    }

    /**
     * Gets as directControl
     *
     * Подключение счета к договору о прямом управлении счетами
     *
     * @return boolean
     */
    public function getDirectControl()
    {
        return $this->directControl;
    }

    /**
     * Sets a new directControl
     *
     * Подключение счета к договору о прямом управлении счетами
     *
     * @param boolean $directControl
     * @return static
     */
    public function setDirectControl($directControl)
    {
        $this->directControl = $directControl;
        return $this;
    }

    /**
     * Gets as addDirectControl
     *
     * Подключение счета к дополнительному соглашению к договору банковского счета о прямом управлении счетами
     *
     * @return boolean
     */
    public function getAddDirectControl()
    {
        return $this->addDirectControl;
    }

    /**
     * Sets a new addDirectControl
     *
     * Подключение счета к дополнительному соглашению к договору банковского счета о прямом управлении счетами
     *
     * @param boolean $addDirectControl
     * @return static
     */
    public function setAddDirectControl($addDirectControl)
    {
        $this->addDirectControl = $addDirectControl;
        return $this;
    }

    /**
     * Gets as rzkId
     *
     * Идентификатор счета в РЦК (во внешней системе)
     *
     * @return string
     */
    public function getRzkId()
    {
        return $this->rzkId;
    }

    /**
     * Sets a new rzkId
     *
     * Идентификатор счета в РЦК (во внешней системе)
     *
     * @param string $rzkId
     * @return static
     */
    public function setRzkId($rzkId)
    {
        $this->rzkId = $rzkId;
        return $this;
    }

    /**
     * Gets as rzkOrgAccTypeId
     *
     * Идентификатор типа счета в РЦК (во внешней системе)
     *
     * @return string
     */
    public function getRzkOrgAccTypeId()
    {
        return $this->rzkOrgAccTypeId;
    }

    /**
     * Sets a new rzkOrgAccTypeId
     *
     * Идентификатор типа счета в РЦК (во внешней системе)
     *
     * @param string $rzkOrgAccTypeId
     * @return static
     */
    public function setRzkOrgAccTypeId($rzkOrgAccTypeId)
    {
        $this->rzkOrgAccTypeId = $rzkOrgAccTypeId;
        return $this;
    }

    /**
     * Gets as rzkOrgId
     *
     * Идентификатор организации счета в РЦК (во внешней системе)
     *
     * @return string
     */
    public function getRzkOrgId()
    {
        return $this->rzkOrgId;
    }

    /**
     * Sets a new rzkOrgId
     *
     * Идентификатор организации счета в РЦК (во внешней системе)
     *
     * @param string $rzkOrgId
     * @return static
     */
    public function setRzkOrgId($rzkOrgId)
    {
        $this->rzkOrgId = $rzkOrgId;
        return $this;
    }


}

