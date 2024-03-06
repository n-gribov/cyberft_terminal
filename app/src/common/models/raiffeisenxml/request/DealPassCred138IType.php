<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing DealPassCred138IType
 *
 *
 * XSD Type: DealPassCred138I
 */
class DealPassCred138IType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты документа ВК ДБО
     *
     * @property \common\models\raiffeisenxml\request\DocDataCCType $docData
     */
    private $docData = null;

    /**
     * 1. Сведения о резиденте
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred138IType\ResInfoAType $resInfo
     */
    private $resInfo = null;

    /**
     * 2. Реквизиты иностранного контрагента
     *
     * @property \common\models\raiffeisenxml\request\BeneficiarInfoType[] $beneficiarInfo
     */
    private $beneficiarInfo = null;

    /**
     * 3.1 Общие сведения о кредитном договоре
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred138IType\ComDataAType $comData
     */
    private $comData = null;

    /**
     * 3.2 Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred138IType\TrancheInfoAType\OperAType[] $trancheInfo
     */
    private $trancheInfo = null;

    /**
     * 6. Сведения о ранее оформленном паспорте сделки по контракту
     *
     * @property string $numPS
     */
    private $numPS = null;

    /**
     * 7. Справочная информация
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred138IType\RefInfoAType $refInfo
     */
    private $refInfo = null;

    /**
     * Срочное оформление ПС
     *
     * @property bool $urgent
     */
    private $urgent = null;

    /**
     * 8. Специальные сведения о кредитном договоре
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred138IType\SpecDataAType $specData
     */
    private $specData = null;

    /**
     * 9 Справочная информация о кредитном договоре
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred138IType\HelpInfoAType $helpInfo
     */
    private $helpInfo = null;

    /**
     * Доп. информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @property \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType[] $attachments
     */
    private $attachments = null;

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
     * Gets as docData
     *
     * Общие реквизиты документа ВК ДБО
     *
     * @return \common\models\raiffeisenxml\request\DocDataCCType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты документа ВК ДБО
     *
     * @param \common\models\raiffeisenxml\request\DocDataCCType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\DocDataCCType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as resInfo
     *
     * 1. Сведения о резиденте
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred138IType\ResInfoAType
     */
    public function getResInfo()
    {
        return $this->resInfo;
    }

    /**
     * Sets a new resInfo
     *
     * 1. Сведения о резиденте
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred138IType\ResInfoAType $resInfo
     * @return static
     */
    public function setResInfo(\common\models\raiffeisenxml\request\DealPassCred138IType\ResInfoAType $resInfo)
    {
        $this->resInfo = $resInfo;
        return $this;
    }

    /**
     * Adds as beneficiar
     *
     * 2. Реквизиты иностранного контрагента
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\BeneficiarInfoType $beneficiar
     */
    public function addToBeneficiarInfo(\common\models\raiffeisenxml\request\BeneficiarInfoType $beneficiar)
    {
        $this->beneficiarInfo[] = $beneficiar;
        return $this;
    }

    /**
     * isset beneficiarInfo
     *
     * 2. Реквизиты иностранного контрагента
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBeneficiarInfo($index)
    {
        return isset($this->beneficiarInfo[$index]);
    }

    /**
     * unset beneficiarInfo
     *
     * 2. Реквизиты иностранного контрагента
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBeneficiarInfo($index)
    {
        unset($this->beneficiarInfo[$index]);
    }

    /**
     * Gets as beneficiarInfo
     *
     * 2. Реквизиты иностранного контрагента
     *
     * @return \common\models\raiffeisenxml\request\BeneficiarInfoType[]
     */
    public function getBeneficiarInfo()
    {
        return $this->beneficiarInfo;
    }

    /**
     * Sets a new beneficiarInfo
     *
     * 2. Реквизиты иностранного контрагента
     *
     * @param \common\models\raiffeisenxml\request\BeneficiarInfoType[] $beneficiarInfo
     * @return static
     */
    public function setBeneficiarInfo(array $beneficiarInfo)
    {
        $this->beneficiarInfo = $beneficiarInfo;
        return $this;
    }

    /**
     * Gets as comData
     *
     * 3.1 Общие сведения о кредитном договоре
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred138IType\ComDataAType
     */
    public function getComData()
    {
        return $this->comData;
    }

    /**
     * Sets a new comData
     *
     * 3.1 Общие сведения о кредитном договоре
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred138IType\ComDataAType $comData
     * @return static
     */
    public function setComData(\common\models\raiffeisenxml\request\DealPassCred138IType\ComDataAType $comData)
    {
        $this->comData = $comData;
        return $this;
    }

    /**
     * Adds as oper
     *
     * 3.2 Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\DealPassCred138IType\TrancheInfoAType\OperAType $oper
     */
    public function addToTrancheInfo(\common\models\raiffeisenxml\request\DealPassCred138IType\TrancheInfoAType\OperAType $oper)
    {
        $this->trancheInfo[] = $oper;
        return $this;
    }

    /**
     * isset trancheInfo
     *
     * 3.2 Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTrancheInfo($index)
    {
        return isset($this->trancheInfo[$index]);
    }

    /**
     * unset trancheInfo
     *
     * 3.2 Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTrancheInfo($index)
    {
        unset($this->trancheInfo[$index]);
    }

    /**
     * Gets as trancheInfo
     *
     * 3.2 Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred138IType\TrancheInfoAType\OperAType[]
     */
    public function getTrancheInfo()
    {
        return $this->trancheInfo;
    }

    /**
     * Sets a new trancheInfo
     *
     * 3.2 Сведения о сумме и сроках привлечения (предоставления) траншей по кредитному
     *  договору
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred138IType\TrancheInfoAType\OperAType[] $trancheInfo
     * @return static
     */
    public function setTrancheInfo(array $trancheInfo)
    {
        $this->trancheInfo = $trancheInfo;
        return $this;
    }

    /**
     * Gets as numPS
     *
     * 6. Сведения о ранее оформленном паспорте сделки по контракту
     *
     * @return string
     */
    public function getNumPS()
    {
        return $this->numPS;
    }

    /**
     * Sets a new numPS
     *
     * 6. Сведения о ранее оформленном паспорте сделки по контракту
     *
     * @param string $numPS
     * @return static
     */
    public function setNumPS($numPS)
    {
        $this->numPS = $numPS;
        return $this;
    }

    /**
     * Gets as refInfo
     *
     * 7. Справочная информация
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred138IType\RefInfoAType
     */
    public function getRefInfo()
    {
        return $this->refInfo;
    }

    /**
     * Sets a new refInfo
     *
     * 7. Справочная информация
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred138IType\RefInfoAType $refInfo
     * @return static
     */
    public function setRefInfo(\common\models\raiffeisenxml\request\DealPassCred138IType\RefInfoAType $refInfo)
    {
        $this->refInfo = $refInfo;
        return $this;
    }

    /**
     * Gets as urgent
     *
     * Срочное оформление ПС
     *
     * @return bool
     */
    public function getUrgent()
    {
        return $this->urgent;
    }

    /**
     * Sets a new urgent
     *
     * Срочное оформление ПС
     *
     * @param bool $urgent
     * @return static
     */
    public function setUrgent($urgent)
    {
        $this->urgent = $urgent;
        return $this;
    }

    /**
     * Gets as specData
     *
     * 8. Специальные сведения о кредитном договоре
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred138IType\SpecDataAType
     */
    public function getSpecData()
    {
        return $this->specData;
    }

    /**
     * Sets a new specData
     *
     * 8. Специальные сведения о кредитном договоре
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred138IType\SpecDataAType $specData
     * @return static
     */
    public function setSpecData(\common\models\raiffeisenxml\request\DealPassCred138IType\SpecDataAType $specData)
    {
        $this->specData = $specData;
        return $this;
    }

    /**
     * Gets as helpInfo
     *
     * 9 Справочная информация о кредитном договоре
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred138IType\HelpInfoAType
     */
    public function getHelpInfo()
    {
        return $this->helpInfo;
    }

    /**
     * Sets a new helpInfo
     *
     * 9 Справочная информация о кредитном договоре
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred138IType\HelpInfoAType $helpInfo
     * @return static
     */
    public function setHelpInfo(\common\models\raiffeisenxml\request\DealPassCred138IType\HelpInfoAType $helpInfo)
    {
        $this->helpInfo = $helpInfo;
        return $this;
    }

    /**
     * Gets as addInfo
     *
     * Доп. информация
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
     * Доп. информация
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
     * Adds as lDoc
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\raiffeisenxml\request\LinkedDocsType\LDocAType $lDoc)
    {
        $this->linkedDocs[] = $lDoc;
        return $this;
    }

    /**
     * isset linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLinkedDocs($index)
    {
        return isset($this->linkedDocs[$index]);
    }

    /**
     * unset linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLinkedDocs($index)
    {
        unset($this->linkedDocs[$index]);
    }

    /**
     * Gets as linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @return \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[]
     */
    public function getLinkedDocs()
    {
        return $this->linkedDocs;
    }

    /**
     * Sets a new linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
     *
     * @param \common\models\raiffeisenxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     * @return static
     */
    public function setLinkedDocs(array $linkedDocs)
    {
        $this->linkedDocs = $linkedDocs;
        return $this;
    }

    /**
     * Adds as attachment
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType $attachment
     */
    public function addToAttachments(\common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * isset attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAttachments($index)
    {
        return isset($this->attachments[$index]);
    }

    /**
     * unset attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAttachments($index)
    {
        unset($this->attachments[$index]);
    }

    /**
     * Gets as attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @return \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType[]
     */
    public function getAttachments()
    {
        return $this->attachments;
    }

    /**
     * Sets a new attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param \common\models\raiffeisenxml\request\AttachmentsType\AttachmentAType[] $attachments
     * @return static
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }


}

