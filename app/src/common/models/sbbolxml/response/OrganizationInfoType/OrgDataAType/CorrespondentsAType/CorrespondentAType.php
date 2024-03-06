<?php

namespace common\models\sbbolxml\response\OrganizationInfoType\OrgDataAType\CorrespondentsAType;

/**
 * Class representing CorrespondentAType
 */
class CorrespondentAType
{

    /**
     * Идентификатор документа в СББ
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Наименование организации корреспондента
     *
     * @property string $name
     */
    private $name = null;

    /**
     * ИНН организации корреспондента
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * КПП организации корреспондента
     *
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * Реквизиты счёта контрагента
     *
     * @property string $personalAcc
     */
    private $personalAcc = null;

    /**
     * Комментарий
     *
     * @property string $comment
     */
    private $comment = null;

    /**
     * Видимость: 1-подписан; 0- не подписан.
     *
     * @property boolean $signed
     */
    private $signed = null;

    /**
     * Видимость: 1-подтвержден банком; 0- не подтвержден
     *  банком.
     *
     * @property boolean $bankConfirm
     */
    private $bankConfirm = null;

    /**
     * Признак массовой загрузки банком
     *
     * @property boolean $importfrombank
     */
    private $importfrombank = null;

    /**
     * Банк контрагента
     *
     * @property \common\models\sbbolxml\response\CorrBankType $bank
     */
    private $bank = null;

    /**
     * Gets as docExtId
     *
     * Идентификатор документа в СББ
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
     * Идентификатор документа в СББ
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
     * Gets as name
     *
     * Наименование организации корреспондента
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Наименование организации корреспондента
     *
     * @param string $name
     * @return static
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as inn
     *
     * ИНН организации корреспондента
     *
     * @return string
     */
    public function getInn()
    {
        return $this->inn;
    }

    /**
     * Sets a new inn
     *
     * ИНН организации корреспондента
     *
     * @param string $inn
     * @return static
     */
    public function setInn($inn)
    {
        $this->inn = $inn;
        return $this;
    }

    /**
     * Gets as kpp
     *
     * КПП организации корреспондента
     *
     * @return string
     */
    public function getKpp()
    {
        return $this->kpp;
    }

    /**
     * Sets a new kpp
     *
     * КПП организации корреспондента
     *
     * @param string $kpp
     * @return static
     */
    public function setKpp($kpp)
    {
        $this->kpp = $kpp;
        return $this;
    }

    /**
     * Gets as personalAcc
     *
     * Реквизиты счёта контрагента
     *
     * @return string
     */
    public function getPersonalAcc()
    {
        return $this->personalAcc;
    }

    /**
     * Sets a new personalAcc
     *
     * Реквизиты счёта контрагента
     *
     * @param string $personalAcc
     * @return static
     */
    public function setPersonalAcc($personalAcc)
    {
        $this->personalAcc = $personalAcc;
        return $this;
    }

    /**
     * Gets as comment
     *
     * Комментарий
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Sets a new comment
     *
     * Комментарий
     *
     * @param string $comment
     * @return static
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
        return $this;
    }

    /**
     * Gets as signed
     *
     * Видимость: 1-подписан; 0- не подписан.
     *
     * @return boolean
     */
    public function getSigned()
    {
        return $this->signed;
    }

    /**
     * Sets a new signed
     *
     * Видимость: 1-подписан; 0- не подписан.
     *
     * @param boolean $signed
     * @return static
     */
    public function setSigned($signed)
    {
        $this->signed = $signed;
        return $this;
    }

    /**
     * Gets as bankConfirm
     *
     * Видимость: 1-подтвержден банком; 0- не подтвержден
     *  банком.
     *
     * @return boolean
     */
    public function getBankConfirm()
    {
        return $this->bankConfirm;
    }

    /**
     * Sets a new bankConfirm
     *
     * Видимость: 1-подтвержден банком; 0- не подтвержден
     *  банком.
     *
     * @param boolean $bankConfirm
     * @return static
     */
    public function setBankConfirm($bankConfirm)
    {
        $this->bankConfirm = $bankConfirm;
        return $this;
    }

    /**
     * Gets as importfrombank
     *
     * Признак массовой загрузки банком
     *
     * @return boolean
     */
    public function getImportfrombank()
    {
        return $this->importfrombank;
    }

    /**
     * Sets a new importfrombank
     *
     * Признак массовой загрузки банком
     *
     * @param boolean $importfrombank
     * @return static
     */
    public function setImportfrombank($importfrombank)
    {
        $this->importfrombank = $importfrombank;
        return $this;
    }

    /**
     * Gets as bank
     *
     * Банк контрагента
     *
     * @return \common\models\sbbolxml\response\CorrBankType
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Sets a new bank
     *
     * Банк контрагента
     *
     * @param \common\models\sbbolxml\response\CorrBankType $bank
     * @return static
     */
    public function setBank(\common\models\sbbolxml\response\CorrBankType $bank)
    {
        $this->bank = $bank;
        return $this;
    }


}

