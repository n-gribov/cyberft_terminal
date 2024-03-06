<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DocDataVBKType
 *
 * Общие реквизиты ВК (паспортов сделок) ДБО
 * XSD Type: DocDataVBK
 */
class DocDataVBKType
{

    /**
     * Системное наименование подразделения банка
     *
     * @property string $branchSystemName
     */
    private $branchSystemName = null;

    /**
     * Наименование банка УК
     *
     * @property string $authBankName
     */
    private $authBankName = null;

    /**
     * Номер сформированного документа
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Дата создания документа по местному времени
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Валютный контролер
     *
     * @property string $author
     */
    private $author = null;

    /**
     * @property boolean $isActual
     */
    private $isActual = null;

    /**
     * Тип ВБК: OnContract - По Контракту, OnCredit - По кредитному договору
     *
     * @property string $iCSType
     */
    private $iCSType = null;

    /**
     * Режим создания ВБК (импорт/экспорт товаров и/или услуг, уступка)
     *  VbkConRegistration, VbkConInformation, VbkConLicenseLost, VbkConAnotherBank, VbkConAssignment,
     *  VbkCredRegistration, VbkCredLicenseLost, VbkCredAnotherBank, VbkCredAssignment
     *
     * @property string $icsCreationMode
     */
    private $icsCreationMode = null;

    /**
     * Код вида контракта, заполняемый для экспортных контрактов при представлении
     *  сведений по контракту без контракта (Режим создания ВБК - VbkConInformation)
     *
     * @property integer $iCSContractTypeCode
     */
    private $iCSContractTypeCode = null;

    /**
     * Возможные подтипы документа "Ведомость банковского контроля
     *  SEND_DOCUMENTS - Досыл документов по контракту (кредитному договору)
     *  REGISTRATION - Постановка контракта (кредитного договора) на учет
     *  STATEMENT - ВБК по контракту (кредитному договору)
     *
     * @property string $iCSContractSubTypeCode
     */
    private $iCSContractSubTypeCode = null;

    /**
     * Признак перехода контракта (кредитного договора) по уступке
     *
     * @property boolean $isAssignmentOfRights
     */
    private $isAssignmentOfRights = null;

    /**
     * Признак доступных непрочитанных сообщений по данному документу ВБК
     *
     * @property boolean $hasUnreadMessages
     */
    private $hasUnreadMessages = null;

    /**
     * Флаг досыла документа
     *
     * @property boolean $hasSentDocument
     */
    private $hasSentDocument = null;

    /**
     * Gets as branchSystemName
     *
     * Системное наименование подразделения банка
     *
     * @return string
     */
    public function getBranchSystemName()
    {
        return $this->branchSystemName;
    }

    /**
     * Sets a new branchSystemName
     *
     * Системное наименование подразделения банка
     *
     * @param string $branchSystemName
     * @return static
     */
    public function setBranchSystemName($branchSystemName)
    {
        $this->branchSystemName = $branchSystemName;
        return $this;
    }

    /**
     * Gets as authBankName
     *
     * Наименование банка УК
     *
     * @return string
     */
    public function getAuthBankName()
    {
        return $this->authBankName;
    }

    /**
     * Sets a new authBankName
     *
     * Наименование банка УК
     *
     * @param string $authBankName
     * @return static
     */
    public function setAuthBankName($authBankName)
    {
        $this->authBankName = $authBankName;
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
     * Gets as docDate
     *
     * Дата создания документа по местному времени
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
     * Дата создания документа по местному времени
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
     * Gets as author
     *
     * Валютный контролер
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Sets a new author
     *
     * Валютный контролер
     *
     * @param string $author
     * @return static
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * Gets as isActual
     *
     * @return boolean
     */
    public function getIsActual()
    {
        return $this->isActual;
    }

    /**
     * Sets a new isActual
     *
     * @param boolean $isActual
     * @return static
     */
    public function setIsActual($isActual)
    {
        $this->isActual = $isActual;
        return $this;
    }

    /**
     * Gets as iCSType
     *
     * Тип ВБК: OnContract - По Контракту, OnCredit - По кредитному договору
     *
     * @return string
     */
    public function getICSType()
    {
        return $this->iCSType;
    }

    /**
     * Sets a new iCSType
     *
     * Тип ВБК: OnContract - По Контракту, OnCredit - По кредитному договору
     *
     * @param string $iCSType
     * @return static
     */
    public function setICSType($iCSType)
    {
        $this->iCSType = $iCSType;
        return $this;
    }

    /**
     * Gets as icsCreationMode
     *
     * Режим создания ВБК (импорт/экспорт товаров и/или услуг, уступка)
     *  VbkConRegistration, VbkConInformation, VbkConLicenseLost, VbkConAnotherBank, VbkConAssignment,
     *  VbkCredRegistration, VbkCredLicenseLost, VbkCredAnotherBank, VbkCredAssignment
     *
     * @return string
     */
    public function getIcsCreationMode()
    {
        return $this->icsCreationMode;
    }

    /**
     * Sets a new icsCreationMode
     *
     * Режим создания ВБК (импорт/экспорт товаров и/или услуг, уступка)
     *  VbkConRegistration, VbkConInformation, VbkConLicenseLost, VbkConAnotherBank, VbkConAssignment,
     *  VbkCredRegistration, VbkCredLicenseLost, VbkCredAnotherBank, VbkCredAssignment
     *
     * @param string $icsCreationMode
     * @return static
     */
    public function setIcsCreationMode($icsCreationMode)
    {
        $this->icsCreationMode = $icsCreationMode;
        return $this;
    }

    /**
     * Gets as iCSContractTypeCode
     *
     * Код вида контракта, заполняемый для экспортных контрактов при представлении
     *  сведений по контракту без контракта (Режим создания ВБК - VbkConInformation)
     *
     * @return integer
     */
    public function getICSContractTypeCode()
    {
        return $this->iCSContractTypeCode;
    }

    /**
     * Sets a new iCSContractTypeCode
     *
     * Код вида контракта, заполняемый для экспортных контрактов при представлении
     *  сведений по контракту без контракта (Режим создания ВБК - VbkConInformation)
     *
     * @param integer $iCSContractTypeCode
     * @return static
     */
    public function setICSContractTypeCode($iCSContractTypeCode)
    {
        $this->iCSContractTypeCode = $iCSContractTypeCode;
        return $this;
    }

    /**
     * Gets as iCSContractSubTypeCode
     *
     * Возможные подтипы документа "Ведомость банковского контроля
     *  SEND_DOCUMENTS - Досыл документов по контракту (кредитному договору)
     *  REGISTRATION - Постановка контракта (кредитного договора) на учет
     *  STATEMENT - ВБК по контракту (кредитному договору)
     *
     * @return string
     */
    public function getICSContractSubTypeCode()
    {
        return $this->iCSContractSubTypeCode;
    }

    /**
     * Sets a new iCSContractSubTypeCode
     *
     * Возможные подтипы документа "Ведомость банковского контроля
     *  SEND_DOCUMENTS - Досыл документов по контракту (кредитному договору)
     *  REGISTRATION - Постановка контракта (кредитного договора) на учет
     *  STATEMENT - ВБК по контракту (кредитному договору)
     *
     * @param string $iCSContractSubTypeCode
     * @return static
     */
    public function setICSContractSubTypeCode($iCSContractSubTypeCode)
    {
        $this->iCSContractSubTypeCode = $iCSContractSubTypeCode;
        return $this;
    }

    /**
     * Gets as isAssignmentOfRights
     *
     * Признак перехода контракта (кредитного договора) по уступке
     *
     * @return boolean
     */
    public function getIsAssignmentOfRights()
    {
        return $this->isAssignmentOfRights;
    }

    /**
     * Sets a new isAssignmentOfRights
     *
     * Признак перехода контракта (кредитного договора) по уступке
     *
     * @param boolean $isAssignmentOfRights
     * @return static
     */
    public function setIsAssignmentOfRights($isAssignmentOfRights)
    {
        $this->isAssignmentOfRights = $isAssignmentOfRights;
        return $this;
    }

    /**
     * Gets as hasUnreadMessages
     *
     * Признак доступных непрочитанных сообщений по данному документу ВБК
     *
     * @return boolean
     */
    public function getHasUnreadMessages()
    {
        return $this->hasUnreadMessages;
    }

    /**
     * Sets a new hasUnreadMessages
     *
     * Признак доступных непрочитанных сообщений по данному документу ВБК
     *
     * @param boolean $hasUnreadMessages
     * @return static
     */
    public function setHasUnreadMessages($hasUnreadMessages)
    {
        $this->hasUnreadMessages = $hasUnreadMessages;
        return $this;
    }

    /**
     * Gets as hasSentDocument
     *
     * Флаг досыла документа
     *
     * @return boolean
     */
    public function getHasSentDocument()
    {
        return $this->hasSentDocument;
    }

    /**
     * Sets a new hasSentDocument
     *
     * Флаг досыла документа
     *
     * @param boolean $hasSentDocument
     * @return static
     */
    public function setHasSentDocument($hasSentDocument)
    {
        $this->hasSentDocument = $hasSentDocument;
        return $this;
    }


}

