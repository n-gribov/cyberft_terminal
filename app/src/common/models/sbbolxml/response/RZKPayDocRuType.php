<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing RZKPayDocRuType
 *
 *
 * XSD Type: RZKPayDocRu
 */
class RZKPayDocRuType
{

    /**
     * Идентификатор документа в УС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Идентификатор организации в СББОЛ
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Реквизиты платёжного документа
     *
     * @property \common\models\sbbolxml\response\AccDocType $accDoc
     */
    private $accDoc = null;

    /**
     * Реквизиты плательщика
     *
     * @property \common\models\sbbolxml\response\ClientType $payer
     */
    private $payer = null;

    /**
     * Реквизиты получателя
     *
     * @property \common\models\sbbolxml\response\ContragentType $payee
     */
    private $payee = null;

    /**
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @property \common\models\sbbolxml\response\DepartmentalInfoType $departmentalInfo
     */
    private $departmentalInfo = null;

    /**
     * Информация получателю
     *
     * @property string $information
     */
    private $information = null;

    /**
     * Доп. реквизиты. Указываются для платёжных документов, проводимых за счёт
     *  предоставляемого банком кредита
     *
     * @property \common\models\sbbolxml\response\CreditType $credit
     */
    private $credit = null;

    /**
     * Многострочная аналитика и параметры обработки платежного документа в РЦК (СБК)
     *
     * @property \common\models\sbbolxml\response\RZKPayDocRuType\RzkAType $rzk
     */
    private $rzk = null;

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
     * Продукты и услуги плательщика (доп. продукты РЦК, например, консолидация,
     *  финансирование и т.п.)
     *
     * @property \common\models\sbbolxml\response\ServicesType $services
     */
    private $services = null;

    /**
     * @property \common\models\sbbolxml\response\DigitalSignType $sign
     */
    private $sign = null;

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
     * Gets as accDoc
     *
     * Реквизиты платёжного документа
     *
     * @return \common\models\sbbolxml\response\AccDocType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Реквизиты платёжного документа
     *
     * @param \common\models\sbbolxml\response\AccDocType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\sbbolxml\response\AccDocType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as payer
     *
     * Реквизиты плательщика
     *
     * @return \common\models\sbbolxml\response\ClientType
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * Sets a new payer
     *
     * Реквизиты плательщика
     *
     * @param \common\models\sbbolxml\response\ClientType $payer
     * @return static
     */
    public function setPayer(\common\models\sbbolxml\response\ClientType $payer)
    {
        $this->payer = $payer;
        return $this;
    }

    /**
     * Gets as payee
     *
     * Реквизиты получателя
     *
     * @return \common\models\sbbolxml\response\ContragentType
     */
    public function getPayee()
    {
        return $this->payee;
    }

    /**
     * Sets a new payee
     *
     * Реквизиты получателя
     *
     * @param \common\models\sbbolxml\response\ContragentType $payee
     * @return static
     */
    public function setPayee(\common\models\sbbolxml\response\ContragentType $payee)
    {
        $this->payee = $payee;
        return $this;
    }

    /**
     * Gets as departmentalInfo
     *
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @return \common\models\sbbolxml\response\DepartmentalInfoType
     */
    public function getDepartmentalInfo()
    {
        return $this->departmentalInfo;
    }

    /**
     * Sets a new departmentalInfo
     *
     * Налоговая информация: поля 101, 104 - 110 платёжного поручения
     *
     * @param \common\models\sbbolxml\response\DepartmentalInfoType $departmentalInfo
     * @return static
     */
    public function setDepartmentalInfo(\common\models\sbbolxml\response\DepartmentalInfoType $departmentalInfo)
    {
        $this->departmentalInfo = $departmentalInfo;
        return $this;
    }

    /**
     * Gets as information
     *
     * Информация получателю
     *
     * @return string
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Sets a new information
     *
     * Информация получателю
     *
     * @param string $information
     * @return static
     */
    public function setInformation($information)
    {
        $this->information = $information;
        return $this;
    }

    /**
     * Gets as credit
     *
     * Доп. реквизиты. Указываются для платёжных документов, проводимых за счёт
     *  предоставляемого банком кредита
     *
     * @return \common\models\sbbolxml\response\CreditType
     */
    public function getCredit()
    {
        return $this->credit;
    }

    /**
     * Sets a new credit
     *
     * Доп. реквизиты. Указываются для платёжных документов, проводимых за счёт
     *  предоставляемого банком кредита
     *
     * @param \common\models\sbbolxml\response\CreditType $credit
     * @return static
     */
    public function setCredit(\common\models\sbbolxml\response\CreditType $credit)
    {
        $this->credit = $credit;
        return $this;
    }

    /**
     * Gets as rzk
     *
     * Многострочная аналитика и параметры обработки платежного документа в РЦК (СБК)
     *
     * @return \common\models\sbbolxml\response\RZKPayDocRuType\RzkAType
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
     * @param \common\models\sbbolxml\response\RZKPayDocRuType\RzkAType $rzk
     * @return static
     */
    public function setRzk(\common\models\sbbolxml\response\RZKPayDocRuType\RzkAType $rzk)
    {
        $this->rzk = $rzk;
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
     * Gets as services
     *
     * Продукты и услуги плательщика (доп. продукты РЦК, например, консолидация,
     *  финансирование и т.п.)
     *
     * @return \common\models\sbbolxml\response\ServicesType
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Sets a new services
     *
     * Продукты и услуги плательщика (доп. продукты РЦК, например, консолидация,
     *  финансирование и т.п.)
     *
     * @param \common\models\sbbolxml\response\ServicesType $services
     * @return static
     */
    public function setServices(\common\models\sbbolxml\response\ServicesType $services)
    {
        $this->services = $services;
        return $this;
    }

    /**
     * Gets as sign
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
     * @param \common\models\sbbolxml\response\DigitalSignType $sign
     * @return static
     */
    public function setSign(\common\models\sbbolxml\response\DigitalSignType $sign)
    {
        $this->sign = $sign;
        return $this;
    }


}

