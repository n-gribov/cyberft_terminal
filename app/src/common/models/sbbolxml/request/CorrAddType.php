<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing CorrAddType
 *
 *
 * XSD Type: CorrAdd
 */
class CorrAddType extends DocBaseType
{

    /**
     * Идентификатор сущности в СББОЛ.
     *  При отсутствии идентификатора - в СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей
     *  записи с данным id
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Наименование организации корреспондента
     *
     * @property string $name
     */
    private $name = null;

    /**
     * ИНН организации корреспондента
     *
     * @property string $iNN
     */
    private $iNN = null;

    /**
     * КПП организации корреспондента
     *
     * @property string $kPP
     */
    private $kPP = null;

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
     * Сгенерировать SMS-код для подтверждения записи?
     *
     * @property string $genSMSSign
     */
    private $genSMSSign = null;

    /**
     * Признак загрузки Банком: 1 - установлен, 0 - не установлен
     *
     * @property boolean $importFromBank
     */
    private $importFromBank = null;

    /**
     * Банк контрагента
     *
     * @property \common\models\sbbolxml\request\CorrBankType $bank
     */
    private $bank = null;

    /**
     * Назначение платежа
     *
     * @property string $purpose
     */
    private $purpose = null;

    /**
     * Gets as docId
     *
     * Идентификатор сущности в СББОЛ.
     *  При отсутствии идентификатора - в СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей
     *  записи с данным id
     *
     * @return string
     */
    public function getDocId()
    {
        return $this->docId;
    }

    /**
     * Sets a new docId
     *
     * Идентификатор сущности в СББОЛ.
     *  При отсутствии идентификатора - в СББОЛ будет создана новая запись.
     *  При наличии идентификатора - в СББОЛ будет произведено изменение существующей
     *  записи с данным id
     *
     * @param string $docId
     * @return static
     */
    public function setDocId($docId)
    {
        $this->docId = $docId;
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
     * Gets as iNN
     *
     * ИНН организации корреспондента
     *
     * @return string
     */
    public function getINN()
    {
        return $this->iNN;
    }

    /**
     * Sets a new iNN
     *
     * ИНН организации корреспондента
     *
     * @param string $iNN
     * @return static
     */
    public function setINN($iNN)
    {
        $this->iNN = $iNN;
        return $this;
    }

    /**
     * Gets as kPP
     *
     * КПП организации корреспондента
     *
     * @return string
     */
    public function getKPP()
    {
        return $this->kPP;
    }

    /**
     * Sets a new kPP
     *
     * КПП организации корреспондента
     *
     * @param string $kPP
     * @return static
     */
    public function setKPP($kPP)
    {
        $this->kPP = $kPP;
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
     * Gets as genSMSSign
     *
     * Сгенерировать SMS-код для подтверждения записи?
     *
     * @return string
     */
    public function getGenSMSSign()
    {
        return $this->genSMSSign;
    }

    /**
     * Sets a new genSMSSign
     *
     * Сгенерировать SMS-код для подтверждения записи?
     *
     * @param string $genSMSSign
     * @return static
     */
    public function setGenSMSSign($genSMSSign)
    {
        $this->genSMSSign = $genSMSSign;
        return $this;
    }

    /**
     * Gets as importFromBank
     *
     * Признак загрузки Банком: 1 - установлен, 0 - не установлен
     *
     * @return boolean
     */
    public function getImportFromBank()
    {
        return $this->importFromBank;
    }

    /**
     * Sets a new importFromBank
     *
     * Признак загрузки Банком: 1 - установлен, 0 - не установлен
     *
     * @param boolean $importFromBank
     * @return static
     */
    public function setImportFromBank($importFromBank)
    {
        $this->importFromBank = $importFromBank;
        return $this;
    }

    /**
     * Gets as bank
     *
     * Банк контрагента
     *
     * @return \common\models\sbbolxml\request\CorrBankType
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
     * @param \common\models\sbbolxml\request\CorrBankType $bank
     * @return static
     */
    public function setBank(\common\models\sbbolxml\request\CorrBankType $bank)
    {
        $this->bank = $bank;
        return $this;
    }

    /**
     * Gets as purpose
     *
     * Назначение платежа
     *
     * @return string
     */
    public function getPurpose()
    {
        return $this->purpose;
    }

    /**
     * Sets a new purpose
     *
     * Назначение платежа
     *
     * @param string $purpose
     * @return static
     */
    public function setPurpose($purpose)
    {
        $this->purpose = $purpose;
        return $this;
    }


}

