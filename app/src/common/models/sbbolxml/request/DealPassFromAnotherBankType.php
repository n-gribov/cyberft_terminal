<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing DealPassFromAnotherBankType
 *
 * Паспорт сделки из другого банка
 * XSD Type: DealPassFromAnotherBank
 */
class DealPassFromAnotherBankType extends DocBaseType
{

    /**
     * Общие реквизиты ВК (паспортов сделок) ДБО
     *
     * @property \common\models\sbbolxml\request\DocDataCCType $docData
     */
    private $docData = null;

    /**
     * Наименование XML-файла с паспортом сделки
     *
     * @property string $xmlFileNamePs
     */
    private $xmlFileNamePs = null;

    /**
     * Хэш файла-вложения с паспортом сделки
     *
     * @property string $fileHashPs
     */
    private $fileHashPs = null;

    /**
     * Наименование XML-файла с ВБК
     *
     * @property string $xmlFileNameVbk
     */
    private $xmlFileNameVbk = null;

    /**
     * Хэш файла-вложения с ведомостью банковского контроля
     *
     * @property string $fileHashVbk
     */
    private $fileHashVbk = null;

    /**
     * Номер ПС
     *
     * @property string $dpNum
     */
    private $dpNum = null;

    /**
     * Дата ПС
     *
     * @property \DateTime $dpDate
     */
    private $dpDate = null;

    /**
     * Тип документа: true-контракт, false-кредитный договор
     *
     * @property boolean $conCheck
     */
    private $conCheck = null;

    /**
     * @property \common\models\sbbolxml\request\ContractType $contract
     */
    private $contract = null;

    /**
     * Признак того, что документ нельзя распечатать
     *
     * @property boolean $unprintable
     */
    private $unprintable = null;

    /**
     * Признак того, что по этому документу есть непрочитанные сообщения
     *
     * @property boolean $hasUnreadMessages
     */
    private $hasUnreadMessages = null;

    /**
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @property \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
     */
    private $linkedDocs = null;

    /**
     * Приложенные к документу отсканированные образы-вложения
     *
     * @property \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[] $attachments
     */
    private $attachments = null;

    /**
     * Данные о больших файлах, связанных с сущностью
     *
     * @property \common\models\sbbolxml\request\BigFileAttachmentType[] $bigFileAttachments
     */
    private $bigFileAttachments = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты ВК (паспортов сделок) ДБО
     *
     * @return \common\models\sbbolxml\request\DocDataCCType
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
     * @param \common\models\sbbolxml\request\DocDataCCType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\DocDataCCType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as xmlFileNamePs
     *
     * Наименование XML-файла с паспортом сделки
     *
     * @return string
     */
    public function getXmlFileNamePs()
    {
        return $this->xmlFileNamePs;
    }

    /**
     * Sets a new xmlFileNamePs
     *
     * Наименование XML-файла с паспортом сделки
     *
     * @param string $xmlFileNamePs
     * @return static
     */
    public function setXmlFileNamePs($xmlFileNamePs)
    {
        $this->xmlFileNamePs = $xmlFileNamePs;
        return $this;
    }

    /**
     * Gets as fileHashPs
     *
     * Хэш файла-вложения с паспортом сделки
     *
     * @return string
     */
    public function getFileHashPs()
    {
        return $this->fileHashPs;
    }

    /**
     * Sets a new fileHashPs
     *
     * Хэш файла-вложения с паспортом сделки
     *
     * @param string $fileHashPs
     * @return static
     */
    public function setFileHashPs($fileHashPs)
    {
        $this->fileHashPs = $fileHashPs;
        return $this;
    }

    /**
     * Gets as xmlFileNameVbk
     *
     * Наименование XML-файла с ВБК
     *
     * @return string
     */
    public function getXmlFileNameVbk()
    {
        return $this->xmlFileNameVbk;
    }

    /**
     * Sets a new xmlFileNameVbk
     *
     * Наименование XML-файла с ВБК
     *
     * @param string $xmlFileNameVbk
     * @return static
     */
    public function setXmlFileNameVbk($xmlFileNameVbk)
    {
        $this->xmlFileNameVbk = $xmlFileNameVbk;
        return $this;
    }

    /**
     * Gets as fileHashVbk
     *
     * Хэш файла-вложения с ведомостью банковского контроля
     *
     * @return string
     */
    public function getFileHashVbk()
    {
        return $this->fileHashVbk;
    }

    /**
     * Sets a new fileHashVbk
     *
     * Хэш файла-вложения с ведомостью банковского контроля
     *
     * @param string $fileHashVbk
     * @return static
     */
    public function setFileHashVbk($fileHashVbk)
    {
        $this->fileHashVbk = $fileHashVbk;
        return $this;
    }

    /**
     * Gets as dpNum
     *
     * Номер ПС
     *
     * @return string
     */
    public function getDpNum()
    {
        return $this->dpNum;
    }

    /**
     * Sets a new dpNum
     *
     * Номер ПС
     *
     * @param string $dpNum
     * @return static
     */
    public function setDpNum($dpNum)
    {
        $this->dpNum = $dpNum;
        return $this;
    }

    /**
     * Gets as dpDate
     *
     * Дата ПС
     *
     * @return \DateTime
     */
    public function getDpDate()
    {
        return $this->dpDate;
    }

    /**
     * Sets a new dpDate
     *
     * Дата ПС
     *
     * @param \DateTime $dpDate
     * @return static
     */
    public function setDpDate(\DateTime $dpDate)
    {
        $this->dpDate = $dpDate;
        return $this;
    }

    /**
     * Gets as conCheck
     *
     * Тип документа: true-контракт, false-кредитный договор
     *
     * @return boolean
     */
    public function getConCheck()
    {
        return $this->conCheck;
    }

    /**
     * Sets a new conCheck
     *
     * Тип документа: true-контракт, false-кредитный договор
     *
     * @param boolean $conCheck
     * @return static
     */
    public function setConCheck($conCheck)
    {
        $this->conCheck = $conCheck;
        return $this;
    }

    /**
     * Gets as contract
     *
     * @return \common\models\sbbolxml\request\ContractType
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Sets a new contract
     *
     * @param \common\models\sbbolxml\request\ContractType $contract
     * @return static
     */
    public function setContract(\common\models\sbbolxml\request\ContractType $contract)
    {
        $this->contract = $contract;
        return $this;
    }

    /**
     * Gets as unprintable
     *
     * Признак того, что документ нельзя распечатать
     *
     * @return boolean
     */
    public function getUnprintable()
    {
        return $this->unprintable;
    }

    /**
     * Sets a new unprintable
     *
     * Признак того, что документ нельзя распечатать
     *
     * @param boolean $unprintable
     * @return static
     */
    public function setUnprintable($unprintable)
    {
        $this->unprintable = $unprintable;
        return $this;
    }

    /**
     * Gets as hasUnreadMessages
     *
     * Признак того, что по этому документу есть непрочитанные сообщения
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
     * Признак того, что по этому документу есть непрочитанные сообщения
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
     * Adds as lDoc
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости и т.п.
     *
     * @return static
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType $lDoc
     */
    public function addToLinkedDocs(\common\models\sbbolxml\request\LinkedDocsType\LDocAType $lDoc)
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
     * @return \common\models\sbbolxml\request\LinkedDocsType\LDocAType[]
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
     * @param \common\models\sbbolxml\request\LinkedDocsType\LDocAType[] $linkedDocs
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
     * @param \common\models\sbbolxml\request\AttachmentsType\AttachmentAType $attachment
     */
    public function addToAttachments(\common\models\sbbolxml\request\AttachmentsType\AttachmentAType $attachment)
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
     * @return \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[]
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
     * @param \common\models\sbbolxml\request\AttachmentsType\AttachmentAType[] $attachments
     * @return static
     */
    public function setAttachments(array $attachments)
    {
        $this->attachments = $attachments;
        return $this;
    }

    /**
     * Adds as bigFileAttachment
     *
     * Данные о больших файлах, связанных с сущностью
     *
     * @return static
     * @param \common\models\sbbolxml\request\BigFileAttachmentType $bigFileAttachment
     */
    public function addToBigFileAttachments(\common\models\sbbolxml\request\BigFileAttachmentType $bigFileAttachment)
    {
        $this->bigFileAttachments[] = $bigFileAttachment;
        return $this;
    }

    /**
     * isset bigFileAttachments
     *
     * Данные о больших файлах, связанных с сущностью
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBigFileAttachments($index)
    {
        return isset($this->bigFileAttachments[$index]);
    }

    /**
     * unset bigFileAttachments
     *
     * Данные о больших файлах, связанных с сущностью
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBigFileAttachments($index)
    {
        unset($this->bigFileAttachments[$index]);
    }

    /**
     * Gets as bigFileAttachments
     *
     * Данные о больших файлах, связанных с сущностью
     *
     * @return \common\models\sbbolxml\request\BigFileAttachmentType[]
     */
    public function getBigFileAttachments()
    {
        return $this->bigFileAttachments;
    }

    /**
     * Sets a new bigFileAttachments
     *
     * Данные о больших файлах, связанных с сущностью
     *
     * @param \common\models\sbbolxml\request\BigFileAttachmentType[] $bigFileAttachments
     * @return static
     */
    public function setBigFileAttachments(array $bigFileAttachments)
    {
        $this->bigFileAttachments = $bigFileAttachments;
        return $this;
    }


}

