<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing CurrencyNoticeType
 *
 * Уведомление о послуплении денежных средств на транзитный валютный счет
 * XSD Type: CurrencyNotice
 */
class CurrencyNoticeType
{

    /**
     * Дата уведомления
     *
     * @property \DateTime $docDate
     */
    private $docDate = null;

    /**
     * Номер уведомления
     *
     * @property string $docNum
     */
    private $docNum = null;

    /**
     * Идентификатор документа в СББОЛ
     *
     * @property string $docId
     */
    private $docId = null;

    /**
     * Идентификатор документа в АБС
     *
     * @property string $docExtId
     */
    private $docExtId = null;

    /**
     * Системное имя подразделения банка, которое передается в рамках OrganizationsInfo
     *
     * @property string $bankName
     */
    private $bankName = null;

    /**
     * Идентификатор организации в СББОЛ
     *
     * @property string $orgId
     */
    private $orgId = null;

    /**
     * Наименование организации клиента (сокращенное наименование - как в платежных руб. документах)
     *
     * @property string $orgName
     */
    private $orgName = null;

    /**
     * Подписант уведомления
     *
     * @property string $author
     */
    private $author = null;

    /**
     * ИНН получателя
     *
     * @property string $inn
     */
    private $inn = null;

    /**
     * КПП получателя
     *
     * @property string $kpp
     */
    private $kpp = null;

    /**
     * Номер счёта и БИК перевододателя Customer Account
     *
     * @property \common\models\sbbolxml\response\AccNumBicType $accDoc
     */
    private $accDoc = null;

    /**
     * Дата поступления средств на транзитный счет
     *
     * @property \DateTime $receiveDate
     */
    private $receiveDate = null;

    /**
     * Срок предоставления обосновывающих документов
     *
     * @property \DateTime $deadLine
     */
    private $deadLine = null;

    /**
     * Сумма уведомления
     *
     * @property \common\models\sbbolxml\response\CurrencyNoticeSumType $currencyNoticeSum
     */
    private $currencyNoticeSum = null;

    /**
     * Сообщение клиенту
     *
     * @property string $message
     */
    private $message = null;

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
     * Gets as docDate
     *
     * Дата уведомления
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
     * Дата уведомления
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
     * Gets as docNum
     *
     * Номер уведомления
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
     * Номер уведомления
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
     * Gets as docId
     *
     * Идентификатор документа в СББОЛ
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
     * Идентификатор документа в СББОЛ
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
     * Gets as docExtId
     *
     * Идентификатор документа в АБС
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
     * Идентификатор документа в АБС
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
     * Gets as bankName
     *
     * Системное имя подразделения банка, которое передается в рамках OrganizationsInfo
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Sets a new bankName
     *
     * Системное имя подразделения банка, которое передается в рамках OrganizationsInfo
     *
     * @param string $bankName
     * @return static
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;
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
     * Gets as orgName
     *
     * Наименование организации клиента (сокращенное наименование - как в платежных руб. документах)
     *
     * @return string
     */
    public function getOrgName()
    {
        return $this->orgName;
    }

    /**
     * Sets a new orgName
     *
     * Наименование организации клиента (сокращенное наименование - как в платежных руб. документах)
     *
     * @param string $orgName
     * @return static
     */
    public function setOrgName($orgName)
    {
        $this->orgName = $orgName;
        return $this;
    }

    /**
     * Gets as author
     *
     * Подписант уведомления
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
     * Подписант уведомления
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
     * Gets as inn
     *
     * ИНН получателя
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
     * ИНН получателя
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
     * КПП получателя
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
     * КПП получателя
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
     * Gets as accDoc
     *
     * Номер счёта и БИК перевододателя Customer Account
     *
     * @return \common\models\sbbolxml\response\AccNumBicType
     */
    public function getAccDoc()
    {
        return $this->accDoc;
    }

    /**
     * Sets a new accDoc
     *
     * Номер счёта и БИК перевододателя Customer Account
     *
     * @param \common\models\sbbolxml\response\AccNumBicType $accDoc
     * @return static
     */
    public function setAccDoc(\common\models\sbbolxml\response\AccNumBicType $accDoc)
    {
        $this->accDoc = $accDoc;
        return $this;
    }

    /**
     * Gets as receiveDate
     *
     * Дата поступления средств на транзитный счет
     *
     * @return \DateTime
     */
    public function getReceiveDate()
    {
        return $this->receiveDate;
    }

    /**
     * Sets a new receiveDate
     *
     * Дата поступления средств на транзитный счет
     *
     * @param \DateTime $receiveDate
     * @return static
     */
    public function setReceiveDate(\DateTime $receiveDate)
    {
        $this->receiveDate = $receiveDate;
        return $this;
    }

    /**
     * Gets as deadLine
     *
     * Срок предоставления обосновывающих документов
     *
     * @return \DateTime
     */
    public function getDeadLine()
    {
        return $this->deadLine;
    }

    /**
     * Sets a new deadLine
     *
     * Срок предоставления обосновывающих документов
     *
     * @param \DateTime $deadLine
     * @return static
     */
    public function setDeadLine(\DateTime $deadLine)
    {
        $this->deadLine = $deadLine;
        return $this;
    }

    /**
     * Gets as currencyNoticeSum
     *
     * Сумма уведомления
     *
     * @return \common\models\sbbolxml\response\CurrencyNoticeSumType
     */
    public function getCurrencyNoticeSum()
    {
        return $this->currencyNoticeSum;
    }

    /**
     * Sets a new currencyNoticeSum
     *
     * Сумма уведомления
     *
     * @param \common\models\sbbolxml\response\CurrencyNoticeSumType $currencyNoticeSum
     * @return static
     */
    public function setCurrencyNoticeSum(\common\models\sbbolxml\response\CurrencyNoticeSumType $currencyNoticeSum)
    {
        $this->currencyNoticeSum = $currencyNoticeSum;
        return $this;
    }

    /**
     * Gets as message
     *
     * Сообщение клиенту
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Sets a new message
     *
     * Сообщение клиенту
     *
     * @param string $message
     * @return static
     */
    public function setMessage($message)
    {
        $this->message = $message;
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

