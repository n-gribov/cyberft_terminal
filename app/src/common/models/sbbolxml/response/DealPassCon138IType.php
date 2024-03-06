<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing DealPassCon138IType
 *
 * Паспорт сделки по контракту
 * XSD Type: DealPassCon138I
 */
class DealPassCon138IType
{

    /**
     * Идентификатор паспорта сделки по контракту в СББОЛ (UUID документа)
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Идентификатор организации в СББОЛ
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Код состояния документа
     *
     * @property string $statusStateCode
     */
    private $statusStateCode = null;

    /**
     * Общие реквизиты ВК (паспортов сделок) ДБО
     *
     * @property \common\models\sbbolxml\response\DocDataCCType $docData
     */
    private $docData = null;

    /**
     * Заголовок паспорта сделки
     *
     * @property \common\models\sbbolxml\response\DealPassHeaderType $header
     */
    private $header = null;

    /**
     * Сведения о резиденте
     *
     * @property \common\models\sbbolxml\response\ResInfoType $resInfo
     */
    private $resInfo = null;

    /**
     * Реквизиты нерезидентов
     *
     * @property \common\models\sbbolxml\response\BeneficiarInfoType[] $beneficiarInfo
     */
    private $beneficiarInfo = null;

    /**
     * Общие сведения о кредитном договоре
     *
     * @property \common\models\sbbolxml\response\ComDataType $comData
     */
    private $comData = null;

    /**
     * Сведения о ранее оформленном паспорте сделки по контракту
     *
     * @property string $numPSOtherBank
     */
    private $numPSOtherBank = null;

    /**
     * Справочная информация
     *
     * @property \common\models\sbbolxml\response\SuppleInfoType $suppleInfo
     */
    private $suppleInfo = null;

    /**
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @property \common\models\sbbolxml\response\DPEndDataType[] $dPEnd
     */
    private $dPEnd = null;

    /**
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @property \common\models\sbbolxml\response\DPReissueDataType[] $dPRe
     */
    private $dPRe = null;

    /**
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @property \common\models\sbbolxml\response\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\sbbolxml\response\AttachmentType[] $attachments
     */
    private $attachments = null;

    /**
     * Электронная подпись
     *
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

    /**
     * Gets as docId
     *
     * Идентификатор паспорта сделки по контракту в СББОЛ (UUID документа)
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
     * Идентификатор паспорта сделки по контракту в СББОЛ (UUID документа)
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
     * Gets as orgId
     *
     * Идентификатор организации в СББОЛ
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
     * Идентификатор организации в СББОЛ
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
     * Gets as docData
     *
     * Общие реквизиты ВК (паспортов сделок) ДБО
     *
     * @return \common\models\sbbolxml\response\DocDataCCType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты ВК (паспортов сделок) ДБО
     *
     * @param \common\models\sbbolxml\response\DocDataCCType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\response\DocDataCCType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as header
     *
     * Заголовок паспорта сделки
     *
     * @return \common\models\sbbolxml\response\DealPassHeaderType
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Sets a new header
     *
     * Заголовок паспорта сделки
     *
     * @param \common\models\sbbolxml\response\DealPassHeaderType $header
     * @return static
     */
    public function setHeader(\common\models\sbbolxml\response\DealPassHeaderType $header)
    {
        $this->header = $header;
        return $this;
    }

    /**
     * Gets as resInfo
     *
     * Сведения о резиденте
     *
     * @return \common\models\sbbolxml\response\ResInfoType
     */
    public function getResInfo()
    {
        return $this->resInfo;
    }

    /**
     * Sets a new resInfo
     *
     * Сведения о резиденте
     *
     * @param \common\models\sbbolxml\response\ResInfoType $resInfo
     * @return static
     */
    public function setResInfo(\common\models\sbbolxml\response\ResInfoType $resInfo)
    {
        $this->resInfo = $resInfo;
        return $this;
    }

    /**
     * Adds as beneficiar
     *
     * Реквизиты нерезидентов
     *
     * @return static
     * @param \common\models\sbbolxml\response\BeneficiarInfoType $beneficiar
     */
    public function addToBeneficiarInfo(\common\models\sbbolxml\response\BeneficiarInfoType $beneficiar)
    {
        $this->beneficiarInfo[] = $beneficiar;
        return $this;
    }

    /**
     * isset beneficiarInfo
     *
     * Реквизиты нерезидентов
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBeneficiarInfo($index)
    {
        return isset($this->beneficiarInfo[$index]);
    }

    /**
     * unset beneficiarInfo
     *
     * Реквизиты нерезидентов
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBeneficiarInfo($index)
    {
        unset($this->beneficiarInfo[$index]);
    }

    /**
     * Gets as beneficiarInfo
     *
     * Реквизиты нерезидентов
     *
     * @return \common\models\sbbolxml\response\BeneficiarInfoType[]
     */
    public function getBeneficiarInfo()
    {
        return $this->beneficiarInfo;
    }

    /**
     * Sets a new beneficiarInfo
     *
     * Реквизиты нерезидентов
     *
     * @param \common\models\sbbolxml\response\BeneficiarInfoType[] $beneficiarInfo
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
     * Общие сведения о кредитном договоре
     *
     * @return \common\models\sbbolxml\response\ComDataType
     */
    public function getComData()
    {
        return $this->comData;
    }

    /**
     * Sets a new comData
     *
     * Общие сведения о кредитном договоре
     *
     * @param \common\models\sbbolxml\response\ComDataType $comData
     * @return static
     */
    public function setComData(\common\models\sbbolxml\response\ComDataType $comData)
    {
        $this->comData = $comData;
        return $this;
    }

    /**
     * Gets as numPSOtherBank
     *
     * Сведения о ранее оформленном паспорте сделки по контракту
     *
     * @return string
     */
    public function getNumPSOtherBank()
    {
        return $this->numPSOtherBank;
    }

    /**
     * Sets a new numPSOtherBank
     *
     * Сведения о ранее оформленном паспорте сделки по контракту
     *
     * @param string $numPSOtherBank
     * @return static
     */
    public function setNumPSOtherBank($numPSOtherBank)
    {
        $this->numPSOtherBank = $numPSOtherBank;
        return $this;
    }

    /**
     * Gets as suppleInfo
     *
     * Справочная информация
     *
     * @return \common\models\sbbolxml\response\SuppleInfoType
     */
    public function getSuppleInfo()
    {
        return $this->suppleInfo;
    }

    /**
     * Sets a new suppleInfo
     *
     * Справочная информация
     *
     * @param \common\models\sbbolxml\response\SuppleInfoType $suppleInfo
     * @return static
     */
    public function setSuppleInfo(\common\models\sbbolxml\response\SuppleInfoType $suppleInfo)
    {
        $this->suppleInfo = $suppleInfo;
        return $this;
    }

    /**
     * Adds as dPEndData
     *
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @return static
     * @param \common\models\sbbolxml\response\DPEndDataType $dPEndData
     */
    public function addToDPEnd(\common\models\sbbolxml\response\DPEndDataType $dPEndData)
    {
        $this->dPEnd[] = $dPEndData;
        return $this;
    }

    /**
     * isset dPEnd
     *
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDPEnd($index)
    {
        return isset($this->dPEnd[$index]);
    }

    /**
     * unset dPEnd
     *
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDPEnd($index)
    {
        unset($this->dPEnd[$index]);
    }

    /**
     * Gets as dPEnd
     *
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @return \common\models\sbbolxml\response\DPEndDataType[]
     */
    public function getDPEnd()
    {
        return $this->dPEnd;
    }

    /**
     * Sets a new dPEnd
     *
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @param \common\models\sbbolxml\response\DPEndDataType[] $dPEnd
     * @return static
     */
    public function setDPEnd(array $dPEnd)
    {
        $this->dPEnd = $dPEnd;
        return $this;
    }

    /**
     * Adds as dPReissueData
     *
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @return static
     * @param \common\models\sbbolxml\response\DPReissueDataType $dPReissueData
     */
    public function addToDPRe(\common\models\sbbolxml\response\DPReissueDataType $dPReissueData)
    {
        $this->dPRe[] = $dPReissueData;
        return $this;
    }

    /**
     * isset dPRe
     *
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDPRe($index)
    {
        return isset($this->dPRe[$index]);
    }

    /**
     * unset dPRe
     *
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDPRe($index)
    {
        unset($this->dPRe[$index]);
    }

    /**
     * Gets as dPRe
     *
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @return \common\models\sbbolxml\response\DPReissueDataType[]
     */
    public function getDPRe()
    {
        return $this->dPRe;
    }

    /**
     * Sets a new dPRe
     *
     * Сведения об оформлении, о переводе и закрытии паспорта сделки
     *
     * @param \common\models\sbbolxml\response\DPReissueDataType[] $dPRe
     * @return static
     */
    public function setDPRe(array $dPRe)
    {
        $this->dPRe = $dPRe;
        return $this;
    }

    /**
     * Adds as lDoc
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @return static
     * @param \common\models\sbbolxml\response\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\sbbolxml\response\LinkedDocsType\LDocAType $lDoc)
    {
        $this->linkedDocs[] = $lDoc;
        return $this;
    }

    /**
     * isset linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetLinkedDocs($index)
    {
        return isset($this->linkedDocs[$index]);
    }

    /**
     * unset linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetLinkedDocs($index)
    {
        unset($this->linkedDocs[$index]);
    }

    /**
     * Gets as linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @return \common\models\sbbolxml\response\LinkedDocsType\LDocAType[]
     */
    public function getLinkedDocs()
    {
        return $this->linkedDocs;
    }

    /**
     * Sets a new linkedDocs
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @param \common\models\sbbolxml\response\LinkedDocsType\LDocAType[] $linkedDocs
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
     * @param \common\models\sbbolxml\response\AttachmentType $attachment
     */
    public function addToAttachments(\common\models\sbbolxml\response\AttachmentType $attachment)
    {
        $this->attachments[] = $attachment;
        return $this;
    }

    /**
     * isset attachments
     *
     * Приложенные к документу отсканированные образы-вложения
     *
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\response\AttachmentType[]
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
     * @param \common\models\sbbolxml\response\AttachmentType[] $attachments
     * @return static
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Gets as sign
     *
     * Электронная подпись
     *
     * @return \common\models\sbbolxml\response\DigitalSignType
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * Электронная подпись
     *
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

