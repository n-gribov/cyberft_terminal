<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing RegOfCorpCardsType
 *
 *
 * XSD Type: RegOfCorpCards
 */
class RegOfCorpCardsType extends DocBaseType
{

    /**
     * Общие реквизиты платёжного документа ДБО
     *
     * @property \common\models\sbbolxml\request\ComDocData2Type $docData
     */
    private $docData = null;

    /**
     * Реквизиты бизнес-счета клиента
     *
     * @property \common\models\sbbolxml\request\AccNumBicType $account
     */
    private $account = null;

    /**
     * @property \common\models\sbbolxml\request\RegOfCorpCardsType\TransfInfoAType\TransfAType[] $transfInfo
     */
    private $transfInfo = null;

    /**
     * Общее количество сотрудников (количество строк в таблице)
     *
     * @property integer $total
     */
    private $total = null;

    /**
     * Общая сумма, вычисляемое поле (рассчитывается как сумма для зачисления по всем
     *  строкам таблицы)
     *
     * @property \common\models\sbbolxml\request\CurrAmountType $totalSum
     */
    private $totalSum = null;

    /**
     * Реквизиты платежного поручения
     *
     * @property \common\models\sbbolxml\request\SalaryPayDocRuType $payDocRu
     */
    private $payDocRu = null;

    /**
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
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
     * Дополнительная информация
     *
     * @property string $addInfo
     */
    private $addInfo = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @return \common\models\sbbolxml\request\ComDocData2Type
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
     * @param \common\models\sbbolxml\request\ComDocData2Type $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\ComDocData2Type $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as account
     *
     * Реквизиты бизнес-счета клиента
     *
     * @return \common\models\sbbolxml\request\AccNumBicType
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Реквизиты бизнес-счета клиента
     *
     * @param \common\models\sbbolxml\request\AccNumBicType $account
     * @return static
     */
    public function setAccount(\common\models\sbbolxml\request\AccNumBicType $account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Adds as transf
     *
     * @return static
     * @param \common\models\sbbolxml\request\RegOfCorpCardsType\TransfInfoAType\TransfAType $transf
     */
    public function addToTransfInfo(\common\models\sbbolxml\request\RegOfCorpCardsType\TransfInfoAType\TransfAType $transf)
    {
        $this->transfInfo[] = $transf;
        return $this;
    }

    /**
     * isset transfInfo
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetTransfInfo($index)
    {
        return isset($this->transfInfo[$index]);
    }

    /**
     * unset transfInfo
     *
     * @param scalar $index
     * @return void
     */
    public function unsetTransfInfo($index)
    {
        unset($this->transfInfo[$index]);
    }

    /**
     * Gets as transfInfo
     *
     * @return \common\models\sbbolxml\request\RegOfCorpCardsType\TransfInfoAType\TransfAType[]
     */
    public function getTransfInfo()
    {
        return $this->transfInfo;
    }

    /**
     * Sets a new transfInfo
     *
     * @param \common\models\sbbolxml\request\RegOfCorpCardsType\TransfInfoAType\TransfAType[] $transfInfo
     * @return static
     */
    public function setTransfInfo(array $transfInfo)
    {
        $this->transfInfo = $transfInfo;
        return $this;
    }

    /**
     * Gets as total
     *
     * Общее количество сотрудников (количество строк в таблице)
     *
     * @return integer
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Sets a new total
     *
     * Общее количество сотрудников (количество строк в таблице)
     *
     * @param integer $total
     * @return static
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Gets as totalSum
     *
     * Общая сумма, вычисляемое поле (рассчитывается как сумма для зачисления по всем
     *  строкам таблицы)
     *
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getTotalSum()
    {
        return $this->totalSum;
    }

    /**
     * Sets a new totalSum
     *
     * Общая сумма, вычисляемое поле (рассчитывается как сумма для зачисления по всем
     *  строкам таблицы)
     *
     * @param \common\models\sbbolxml\request\CurrAmountType $totalSum
     * @return static
     */
    public function setTotalSum(\common\models\sbbolxml\request\CurrAmountType $totalSum)
    {
        $this->totalSum = $totalSum;
        return $this;
    }

    /**
     * Gets as payDocRu
     *
     * Реквизиты платежного поручения
     *
     * @return \common\models\sbbolxml\request\SalaryPayDocRuType
     */
    public function getPayDocRu()
    {
        return $this->payDocRu;
    }

    /**
     * Sets a new payDocRu
     *
     * Реквизиты платежного поручения
     *
     * @param \common\models\sbbolxml\request\SalaryPayDocRuType $payDocRu
     * @return static
     */
    public function setPayDocRu(\common\models\sbbolxml\request\SalaryPayDocRuType $payDocRu)
    {
        $this->payDocRu = $payDocRu;
        return $this;
    }

    /**
     * Adds as lDoc
     *
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
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
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
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
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
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
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
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
     * Связанные документы, например, реестр пополнения корп. карт, зарплатные ведомости
     *  и т.п.
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

    /**
     * Gets as addInfo
     *
     * Дополнительная информация
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
     * Дополнительная информация
     *
     * @param string $addInfo
     * @return static
     */
    public function setAddInfo($addInfo)
    {
        $this->addInfo = $addInfo;
        return $this;
    }


}

