<?php

namespace common\models\sbbolxml\request;

/**
 * Class representing SalaryDocType
 *
 *
 * XSD Type: SalaryDoc
 */
class SalaryDocType extends DocBaseType
{

    /**
     * Общие реквизиты платёжного документа ДБО
     *
     * @property \common\models\sbbolxml\request\SalaryDocDataType $docData
     */
    private $docData = null;

    /**
     * Реквизиты расчётного счёта клиента
     *
     * @property \common\models\sbbolxml\request\AccSalaryDocType $account
     */
    private $account = null;

    /**
     * Реквизиты зарплатного договора
     *
     * @property \common\models\sbbolxml\request\SalaryDocType\SalContractAType $salContract
     */
    private $salContract = null;

    /**
     * @property integer $total
     */
    private $total = null;

    /**
     * @property \common\models\sbbolxml\request\CurrAmountType $totalSum
     */
    private $totalSum = null;

    /**
     * @property \common\models\sbbolxml\request\SalaryDocType\TransfInfoAType\TransfAType[] $transfInfo
     */
    private $transfInfo = null;

    /**
     * Платежные документы, которыми перечисляется зар. плата
     *
     * @property \common\models\sbbolxml\request\SalaryPayDocRuType[] $payDocs
     */
    private $payDocs = null;

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
     * @property \common\models\sbbolxml\request\ReportPeriodType $reportPeriod
     */
    private $reportPeriod = null;

    /**
     * Многострочная аналитика и параметры обработки платежного документа в РЦК (СБК)
     *
     * @property \common\models\sbbolxml\request\RzkType $rzk
     */
    private $rzk = null;

    /**
     * Gets as docData
     *
     * Общие реквизиты платёжного документа ДБО
     *
     * @return \common\models\sbbolxml\request\SalaryDocDataType
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
     * @param \common\models\sbbolxml\request\SalaryDocDataType $docData
     * @return static
     */
    public function setDocData(\common\models\sbbolxml\request\SalaryDocDataType $docData)
    {
        $this->docData = $docData;
        return $this;
    }

    /**
     * Gets as account
     *
     * Реквизиты расчётного счёта клиента
     *
     * @return \common\models\sbbolxml\request\AccSalaryDocType
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * Sets a new account
     *
     * Реквизиты расчётного счёта клиента
     *
     * @param \common\models\sbbolxml\request\AccSalaryDocType $account
     * @return static
     */
    public function setAccount(\common\models\sbbolxml\request\AccSalaryDocType $account)
    {
        $this->account = $account;
        return $this;
    }

    /**
     * Gets as salContract
     *
     * Реквизиты зарплатного договора
     *
     * @return \common\models\sbbolxml\request\SalaryDocType\SalContractAType
     */
    public function getSalContract()
    {
        return $this->salContract;
    }

    /**
     * Sets a new salContract
     *
     * Реквизиты зарплатного договора
     *
     * @param \common\models\sbbolxml\request\SalaryDocType\SalContractAType $salContract
     * @return static
     */
    public function setSalContract(\common\models\sbbolxml\request\SalaryDocType\SalContractAType $salContract)
    {
        $this->salContract = $salContract;
        return $this;
    }

    /**
     * Gets as total
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
     * @return \common\models\sbbolxml\request\CurrAmountType
     */
    public function getTotalSum()
    {
        return $this->totalSum;
    }

    /**
     * Sets a new totalSum
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
     * Adds as transf
     *
     * @return static
     * @param \common\models\sbbolxml\request\SalaryDocType\TransfInfoAType\TransfAType $transf
     */
    public function addToTransfInfo(\common\models\sbbolxml\request\SalaryDocType\TransfInfoAType\TransfAType $transf)
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
     * @return \common\models\sbbolxml\request\SalaryDocType\TransfInfoAType\TransfAType[]
     */
    public function getTransfInfo()
    {
        return $this->transfInfo;
    }

    /**
     * Sets a new transfInfo
     *
     * @param \common\models\sbbolxml\request\SalaryDocType\TransfInfoAType\TransfAType[] $transfInfo
     * @return static
     */
    public function setTransfInfo(array $transfInfo)
    {
        $this->transfInfo = $transfInfo;
        return $this;
    }

    /**
     * Adds as payDocRu
     *
     * Платежные документы, которыми перечисляется зар. плата
     *
     * @return static
     * @param \common\models\sbbolxml\request\SalaryPayDocRuType $payDocRu
     */
    public function addToPayDocs(\common\models\sbbolxml\request\SalaryPayDocRuType $payDocRu)
    {
        $this->payDocs[] = $payDocRu;
        return $this;
    }

    /**
     * isset payDocs
     *
     * Платежные документы, которыми перечисляется зар. плата
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPayDocs($index)
    {
        return isset($this->payDocs[$index]);
    }

    /**
     * unset payDocs
     *
     * Платежные документы, которыми перечисляется зар. плата
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPayDocs($index)
    {
        unset($this->payDocs[$index]);
    }

    /**
     * Gets as payDocs
     *
     * Платежные документы, которыми перечисляется зар. плата
     *
     * @return \common\models\sbbolxml\request\SalaryPayDocRuType[]
     */
    public function getPayDocs()
    {
        return $this->payDocs;
    }

    /**
     * Sets a new payDocs
     *
     * Платежные документы, которыми перечисляется зар. плата
     *
     * @param \common\models\sbbolxml\request\SalaryPayDocRuType[] $payDocs
     * @return static
     */
    public function setPayDocs(array $payDocs)
    {
        $this->payDocs = $payDocs;
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
     * Gets as reportPeriod
     *
     * @return \common\models\sbbolxml\request\ReportPeriodType
     */
    public function getReportPeriod()
    {
        return $this->reportPeriod;
    }

    /**
     * Sets a new reportPeriod
     *
     * @param \common\models\sbbolxml\request\ReportPeriodType $reportPeriod
     * @return static
     */
    public function setReportPeriod(\common\models\sbbolxml\request\ReportPeriodType $reportPeriod)
    {
        $this->reportPeriod = $reportPeriod;
        return $this;
    }

    /**
     * Gets as rzk
     *
     * Многострочная аналитика и параметры обработки платежного документа в РЦК (СБК)
     *
     * @return \common\models\sbbolxml\request\RzkType
     */
    public function getRzk()
    {
        return $this->rzk;
    }

    /**
     * Sets a new rzk
     *
     * Многострочная аналитика и параметры обработки платежного документа в РЦК (СБК)
     *
     * @param \common\models\sbbolxml\request\RzkType $rzk
     * @return static
     */
    public function setRzk(\common\models\sbbolxml\request\RzkType $rzk)
    {
        $this->rzk = $rzk;
        return $this;
    }


}

