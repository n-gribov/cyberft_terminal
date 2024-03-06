<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing MandatorySaleRaifType
 *
 *
 * XSD Type: MandatorySaleRaif
 */
class MandatorySaleRaifType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Общие реквизиты платёжного документа ДБО
     *
     * @property \common\models\raiffeisenxml\request\ComDocDataRaifType $docData
     */
    private $docData = null;

    /**
     * Адрес
     *
     * @property string $address
     */
    private $address = null;

    /**
     * Дата списания
     *
     * @property \DateTime $writeOffDate
     */
    private $writeOffDate = null;

    /**
     * Транзитный счет
     *
     * @property \common\models\raiffeisenxml\request\AccountNoNameType $accDoc
     */
    private $accDoc = null;

    /**
     * Общая сумма поступивших денежных средств в валюте счета
     *
     * @property \common\models\raiffeisenxml\request\MandatorySaleRaifType\TotalSumAType $totalSum
     */
    private $totalSum = null;

    /**
     * Продажа
     *
     * @property \common\models\raiffeisenxml\request\MandatorySaleRaifType\SellAType $sell
     */
    private $sell = null;

    /**
     * Зачисление
     *
     * @property \common\models\raiffeisenxml\request\MandatorySaleRaifType\TransAType $trans
     */
    private $trans = null;

    /**
     * @property \common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType $currControl
     */
    private $currControl = null;

    /**
     * Документы по экспортному контракту будут представлены позднее
     *
     * @property bool $docsLater
     */
    private $docsLater = null;

    /**
     * Документы не требуются
     *
     * @property bool $nodocs
     */
    private $nodocs = null;

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
     * Общие реквизиты платёжного документа ДБО
     *
     * @return \common\models\raiffeisenxml\request\ComDocDataRaifType
     */
    public function getDocData()
    {
        return $this->docData;
    }

    /**
     * Sets a new docData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @param \common\models\raiffeisenxml\request\ComDocDataRaifType $docData
     * @return static
     */
    public function setDocData(\common\models\raiffeisenxml\request\ComDocDataRaifType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as address
     *
     * Адрес
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Sets a new address
     *
     * Адрес
     *
     * @param string $address
     * @return static
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * Gets as writeOffDate
     *
     * Дата списания
     *
     * @return \DateTime
     */
    public function getWriteOffDate()
    {
        return $this->writeOffDate;
    }

    /**
     * Sets a new writeOffDate
     *
     * Дата списания
     *
     * @param \DateTime $writeOffDate
     * @return static
     */
    public function setWriteOffDate(\DateTime $writeOffDate)
    {
        $this->writeOffDate = $writeOffDate;
        return $this;
    }

    /**
     * Gets as accDoc
     *
     * Транзитный счет
     *
     * @return \common\models\raiffeisenxml\request\AccountNoNameType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Транзитный счет
     *
     * @param \common\models\raiffeisenxml\request\AccountNoNameType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\raiffeisenxml\request\AccountNoNameType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as totalSum
     *
     * Общая сумма поступивших денежных средств в валюте счета
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleRaifType\TotalSumAType
     */
    public function getTotalSum()
    {
        return $this->totalSum;
    }

    /**
     * Sets a new totalSum
     *
     * Общая сумма поступивших денежных средств в валюте счета
     *
     * @param \common\models\raiffeisenxml\request\MandatorySaleRaifType\TotalSumAType $totalSum
     * @return static
     */
    public function setTotalSum(\common\models\raiffeisenxml\request\MandatorySaleRaifType\TotalSumAType $totalSum)
    {
        $this->totalSum = $totalSum;
        return $this;
    }

    /**
     * Gets as sell
     *
     * Продажа
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleRaifType\SellAType
     */
    public function getSell()
    {
        return $this->sell;
    }

    /**
     * Sets a new sell
     *
     * Продажа
     *
     * @param \common\models\raiffeisenxml\request\MandatorySaleRaifType\SellAType $sell
     * @return static
     */
    public function setSell(\common\models\raiffeisenxml\request\MandatorySaleRaifType\SellAType $sell)
    {
        $this->sell = $sell;
        return $this;
    }

    /**
     * Gets as trans
     *
     * Зачисление
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleRaifType\TransAType
     */
    public function getTrans()
    {
        return $this->trans;
    }

    /**
     * Sets a new trans
     *
     * Зачисление
     *
     * @param \common\models\raiffeisenxml\request\MandatorySaleRaifType\TransAType $trans
     * @return static
     */
    public function setTrans(\common\models\raiffeisenxml\request\MandatorySaleRaifType\TransAType $trans)
    {
        $this->trans = $trans;
        return $this;
    }

    /**
     * Gets as currControl
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType
     */
    public function getCurrControl()
    {
        return $this->currControl;
    }

    /**
     * Sets a new currControl
     *
     * @param \common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType $currControl
     * @return static
     */
    public function setCurrControl(\common\models\raiffeisenxml\request\MandatorySaleRaifType\CurrControlAType $currControl)
    {
        $this->currControl = $currControl;
        return $this;
    }

    /**
     * Gets as docsLater
     *
     * Документы по экспортному контракту будут представлены позднее
     *
     * @return bool
     */
    public function getDocsLater()
    {
        return $this->docsLater;
    }

    /**
     * Sets a new docsLater
     *
     * Документы по экспортному контракту будут представлены позднее
     *
     * @param bool $docsLater
     * @return static
     */
    public function setDocsLater($docsLater)
    {
        $this->docsLater = $docsLater;
        return $this;
    }

    /**
     * Gets as nodocs
     *
     * Документы не требуются
     *
     * @return bool
     */
    public function getNodocs()
    {
        return $this->nodocs;
    }

    /**
     * Sets a new nodocs
     *
     * Документы не требуются
     *
     * @param bool $nodocs
     * @return static
     */
    public function setNodocs($nodocs)
    {
        $this->nodocs = $nodocs;
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

