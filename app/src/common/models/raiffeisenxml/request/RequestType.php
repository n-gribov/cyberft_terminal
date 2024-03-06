<?php

namespace common\models\raiffeisenxml\request;

/**
 * Class representing RequestType
 *
 *
 * XSD Type: Request
 */
class RequestType
{

    /**
     * Уникальный идентификатор запроса
     *
     * @property string $requestId
     */
    private $requestId = null;

    /**
     * Номер версии формата обмена
     *
     * @property string $version
     */
    private $version = null;

    /**
     * Система-отправитель
     *
     * @property string $sender
     */
    private $sender = null;

    /**
     * Система-получатель
     *
     * @property string $receiver
     */
    private $receiver = null;

    /**
     * Запрос информации о результатах обработки документов (подпись будем проверять
     *  в соответствии с порядком
     *  в строк е)
     *
     * @property \common\models\raiffeisenxml\request\RequestType\DocIdsAType\DocIdAType[] $docIds
     */
    private $docIds = null;

    /**
     * Платёжное поручение рублёвое
     *
     * @property \common\models\raiffeisenxml\request\PayDocRuType $payDocRu
     */
    private $payDocRu = null;

    /**
     * КОРОБКА. Письмо в банк.
     *
     * @property \common\models\raiffeisenxml\request\LetterInBankType $letterInBank
     */
    private $letterInBank = null;

    /**
     * КОРОБКА. Справка о подтверждающих документах 138И
     *
     * @property \common\models\raiffeisenxml\request\ConfDocCertificate138IType $confDocCertificate138I
     */
    private $confDocCertificate138I = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Справка о подтверждающих документах 138И.
     *
     * @property \common\models\raiffeisenxml\request\ConfDocCertificate138IRaifType $confDocCertificate138IRaif
     */
    private $confDocCertificate138IRaif = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Справка о подтверждающих документах 181И.
     *
     * @property \common\models\raiffeisenxml\request\ConfDocCertificate181IRaifType $confDocCertificate181IRaif
     */
    private $confDocCertificate181IRaif = null;

    /**
     * КОРОБКА. Справка о валютных операциях.
     *
     * @property \common\models\raiffeisenxml\request\CurrDealCertificate138IType $currDealCertificate138I
     */
    private $currDealCertificate138I = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Справка о валютных операциях 138И
     *
     * @property \common\models\raiffeisenxml\request\CurrDealCertificate138IRaifType $currDealCertificate138IRaif
     */
    private $currDealCertificate138IRaif = null;

    /**
     * КОРОБКА. Паспорт сделки по контракту 138И
     *
     * @property \common\models\raiffeisenxml\request\DealPassCon138IType $dealPassCon138I
     */
    private $dealPassCon138I = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по контракту 138И
     *
     * @property \common\models\raiffeisenxml\request\DealPassCon138IRaifType $dealPassCon138IRaif
     */
    private $dealPassCon138IRaif = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по контракту 181И
     *
     * @property \common\models\raiffeisenxml\request\DealPassCon181IRaifType $dealPassCon181IRaif
     */
    private $dealPassCon181IRaif = null;

    /**
     * КОРОБКА. Паспорт сделки по кредитному договору 138И
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred138IType $dealPassCred138I
     */
    private $dealPassCred138I = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по кредитному договору 138И
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred138IRaifType $dealPassCred138IRaif
     */
    private $dealPassCred138IRaif = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по кредитному договору 181И
     *
     * @property \common\models\raiffeisenxml\request\DealPassCred181IRaifType $dealPassCred181IRaif
     */
    private $dealPassCred181IRaif = null;

    /**
     * КОРОБКА. Заявление о переоформлении паспорта сделки 138И
     *
     * @property \common\models\raiffeisenxml\request\ChanDPType $chanDP
     */
    private $chanDP = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Заявление о переоформлении паспорта сделки по Контракту
     *
     * @property \common\models\raiffeisenxml\request\ChanDPConRaifType $chanDPConRaif
     */
    private $chanDPConRaif = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Заявление о переоформлении паспорта сделки по кредитному договору.
     *
     * @property \common\models\raiffeisenxml\request\ChanDPCredRaifType $chanDPCredRaif
     */
    private $chanDPCredRaif = null;

    /**
     * КОРОБКА. Заявление о закрытии паспорта сделки 138И
     *
     * @property \common\models\raiffeisenxml\request\ClDPType $clDP
     */
    private $clDP = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Заявление о закрытии паспорта сделки.
     *
     * @property \common\models\raiffeisenxml\request\CIDPRaifType $cIDPRaif
     */
    private $cIDPRaif = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Заявление о закрытии паспорта сделки. 181И
     *
     * @property \common\models\raiffeisenxml\request\CIDPRaif181iType $cIDPRaif181i
     */
    private $cIDPRaif181i = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Информация для валютного контроля
     *
     * @property \common\models\raiffeisenxml\request\LetterInBankRaifType $cCInfo
     */
    private $cCInfo = null;

    /**
     * КОРОБКА. Валютный перевод
     *
     * @property \common\models\raiffeisenxml\request\PayDocCurType $payDocCur
     */
    private $payDocCur = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Валютный перевод
     *
     * @property \common\models\raiffeisenxml\request\PayDocCurRaifType $payDocCurRaif
     */
    private $payDocCurRaif = null;

    /**
     * КОРОБКА. Поручение на продажу
     *
     * @property \common\models\raiffeisenxml\request\CurrSellType $currSell
     */
    private $currSell = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Поручение на продажу
     *
     * @property \common\models\raiffeisenxml\request\CurrSellRaifType $currSellRaif
     */
    private $currSellRaif = null;

    /**
     * КОРОБКА. Поручение на покупку валюты
     *
     * @property \common\models\raiffeisenxml\request\CurrBuyType $currBuy
     */
    private $currBuy = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Поручение на покупку валюты
     *
     * @property \common\models\raiffeisenxml\request\CurrBuyRaifType $currBuyRaif
     */
    private $currBuyRaif = null;

    /**
     * КОРОБКА. Поручение на покупку / конверсию
     *
     * @property \common\models\raiffeisenxml\request\CurrConvType $currConv
     */
    private $currConv = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Заявка на конвертацию
     *
     * @property \common\models\raiffeisenxml\request\CurrConvRaifType $currConvRaif
     */
    private $currConvRaif = null;

    /**
     * КОРОБКА. Распоряжение на списание средств с транзитного валютного счета
     *
     * @property \common\models\raiffeisenxml\request\MandatorySaleBoxType $mandatorySale
     */
    private $mandatorySale = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Распоряжение о списание средств с транзитного счета
     *
     * @property \common\models\raiffeisenxml\request\MandatorySaleRaifType $mandatorySaleRaif
     */
    private $mandatorySaleRaif = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Рублевый аккредитив.
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveRubRaifType $accreditiveRubRaif
     */
    private $accreditiveRubRaif = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Валютный аккредитив.
     *
     * @property \common\models\raiffeisenxml\request\AccreditiveCurRaifType $accreditiveCurRaif
     */
    private $accreditiveCurRaif = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Заявка на размещение депозита.
     *
     * @property \common\models\raiffeisenxml\request\LetterOfDepositRaifType $letterOfDepositRaif
     */
    private $letterOfDepositRaif = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Заявление на предоставление кредита.
     *
     * @property \common\models\raiffeisenxml\request\CreditRaifType $creditRaif
     */
    private $creditRaif = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Заявление на выдачу гарантии.
     *
     * @property \common\models\raiffeisenxml\request\GuaranteeRaifType $guaranteeRaif
     */
    private $guaranteeRaif = null;

    /**
     * Запрос о получении данных, получаемых со стороны банка ("ночных выписок",
     *  выписок, писем из банка).
     *
     * @property \common\models\raiffeisenxml\request\RequestType\IncomingAType $incoming
     */
    private $incoming = null;

    /**
     * Запрос криптографической информации (криптопрофилей, сертификатов)
     *
     * @property \common\models\raiffeisenxml\request\RequestType\CryptoIncomingAType $cryptoIncoming
     */
    private $cryptoIncoming = null;

    /**
     * КОРОБКА. Запрос на получение информации о движении ден. средств
     *
     * @property \common\models\raiffeisenxml\request\StmtReqType $stmtReq
     */
    private $stmtReq = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Запрос на получение информации о движении ден. средств
     *
     * @property \common\models\raiffeisenxml\request\StmtReqTypeRaifType $stmtReqRaif
     */
    private $stmtReqRaif = null;

    /**
     * Запрос персональных данных
     *
     * @property \common\models\raiffeisenxml\request\RequestType\PersonalInfoAType $personalInfo
     */
    private $personalInfo = null;

    /**
     * Запрос на получение штампов
     *
     * @property \common\models\raiffeisenxml\request\RequestType\StampsAType $stamps
     */
    private $stamps = null;

    /**
     * @property \common\models\raiffeisenxml\request\DictType[] $dicts
     */
    private $dicts = null;

    /**
     * Запрос на выпуск нового сертификата
     *
     * @property \common\models\raiffeisenxml\request\CertifRequestType $certifRequest
     */
    private $certifRequest = null;

    /**
     * Запрос обновлений для Offline-клиента
     *
     * @property \common\models\raiffeisenxml\request\UpdateRequestType $clientAppUpdateRequest
     */
    private $clientAppUpdateRequest = null;

    /**
     * Запрос на изменение пароля транпортной учетной записи
     *
     * @property \common\models\raiffeisenxml\request\ChangePasswordRequestType $changePasswordRequest
     */
    private $changePasswordRequest = null;

    /**
     * Запрос закрытия сессии
     *
     * @property \common\models\raiffeisenxml\request\RequestType\LogoutRequestAType $logoutRequest
     */
    private $logoutRequest = null;

    /**
     * Запрос на отзыв документов
     *
     * @property \common\models\raiffeisenxml\request\RevocationRequestType $revocationRequest
     */
    private $revocationRequest = null;

    /**
     * РАЙФФАЙЗЕН БАНК. Подтверждение получения документа из Банка
     *
     * @property \common\models\raiffeisenxml\request\RequestType\TicketRaifAType $ticketRaif
     */
    private $ticketRaif = null;

    /**
     * Подтверждение получения документа из Банка
     *
     * @property \common\models\raiffeisenxml\request\RequestType\LetterTicketAType $letterTicket
     */
    private $letterTicket = null;

    /**
     * @property \common\models\raiffeisenxml\request\DigitalSignType[] $signs
     */
    private $signs = null;

    /**
     * Закодированное значение, полученное из скрипта "pm_fp.js" (Фрод)
     *
     * @property string $devicePrint
     */
    private $devicePrint = null;

    /**
     * Gets as requestId
     *
     * Уникальный идентификатор запроса
     *
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * Sets a new requestId
     *
     * Уникальный идентификатор запроса
     *
     * @param string $requestId
     * @return static
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
        return $this;
    }

    /**
     * Gets as version
     *
     * Номер версии формата обмена
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets a new version
     *
     * Номер версии формата обмена
     *
     * @param string $version
     * @return static
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Gets as sender
     *
     * Система-отправитель
     *
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Sets a new sender
     *
     * Система-отправитель
     *
     * @param string $sender
     * @return static
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Gets as receiver
     *
     * Система-получатель
     *
     * @return string
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * Sets a new receiver
     *
     * Система-получатель
     *
     * @param string $receiver
     * @return static
     */
    public function setReceiver($receiver)
    {
        $this->receiver = $receiver;
        return $this;
    }

    /**
     * Adds as docId
     *
     * Запрос информации о результатах обработки документов (подпись будем проверять
     *  в соответствии с порядком
     *  в строк е)
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\RequestType\DocIdsAType\DocIdAType $docId
     */
    public function addToDocIds(\common\models\raiffeisenxml\request\RequestType\DocIdsAType\DocIdAType $docId)
    {
        $this->docIds[] = $docId;
        return $this;
    }

    /**
     * isset docIds
     *
     * Запрос информации о результатах обработки документов (подпись будем проверять
     *  в соответствии с порядком
     *  в строк е)
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDocIds($index)
    {
        return isset($this->docIds[$index]);
    }

    /**
     * unset docIds
     *
     * Запрос информации о результатах обработки документов (подпись будем проверять
     *  в соответствии с порядком
     *  в строк е)
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDocIds($index)
    {
        unset($this->docIds[$index]);
    }

    /**
     * Gets as docIds
     *
     * Запрос информации о результатах обработки документов (подпись будем проверять
     *  в соответствии с порядком
     *  в строк е)
     *
     * @return \common\models\raiffeisenxml\request\RequestType\DocIdsAType\DocIdAType[]
     */
    public function getDocIds()
    {
        return $this->docIds;
    }

    /**
     * Sets a new docIds
     *
     * Запрос информации о результатах обработки документов (подпись будем проверять
     *  в соответствии с порядком
     *  в строк е)
     *
     * @param \common\models\raiffeisenxml\request\RequestType\DocIdsAType\DocIdAType[] $docIds
     * @return static
     */
    public function setDocIds(array $docIds)
    {
        $this->docIds = $docIds;
        return $this;
    }

    /**
     * Gets as payDocRu
     *
     * Платёжное поручение рублёвое
     *
     * @return \common\models\raiffeisenxml\request\PayDocRuType
     */
    public function getPayDocRu()
    {
        return $this->payDocRu;
    }

    /**
     * Sets a new payDocRu
     *
     * Платёжное поручение рублёвое
     *
     * @param \common\models\raiffeisenxml\request\PayDocRuType $payDocRu
     * @return static
     */
    public function setPayDocRu(\common\models\raiffeisenxml\request\PayDocRuType $payDocRu)
    {
        $this->payDocRu = $payDocRu;
        return $this;
    }

    /**
     * Gets as letterInBank
     *
     * КОРОБКА. Письмо в банк.
     *
     * @return \common\models\raiffeisenxml\request\LetterInBankType
     */
    public function getLetterInBank()
    {
        return $this->letterInBank;
    }

    /**
     * Sets a new letterInBank
     *
     * КОРОБКА. Письмо в банк.
     *
     * @param \common\models\raiffeisenxml\request\LetterInBankType $letterInBank
     * @return static
     */
    public function setLetterInBank(\common\models\raiffeisenxml\request\LetterInBankType $letterInBank)
    {
        $this->letterInBank = $letterInBank;
        return $this;
    }

    /**
     * Gets as confDocCertificate138I
     *
     * КОРОБКА. Справка о подтверждающих документах 138И
     *
     * @return \common\models\raiffeisenxml\request\ConfDocCertificate138IType
     */
    public function getConfDocCertificate138I()
    {
        return $this->confDocCertificate138I;
    }

    /**
     * Sets a new confDocCertificate138I
     *
     * КОРОБКА. Справка о подтверждающих документах 138И
     *
     * @param \common\models\raiffeisenxml\request\ConfDocCertificate138IType $confDocCertificate138I
     * @return static
     */
    public function setConfDocCertificate138I(\common\models\raiffeisenxml\request\ConfDocCertificate138IType $confDocCertificate138I)
    {
        $this->confDocCertificate138I = $confDocCertificate138I;
        return $this;
    }

    /**
     * Gets as confDocCertificate138IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Справка о подтверждающих документах 138И.
     *
     * @return \common\models\raiffeisenxml\request\ConfDocCertificate138IRaifType
     */
    public function getConfDocCertificate138IRaif()
    {
        return $this->confDocCertificate138IRaif;
    }

    /**
     * Sets a new confDocCertificate138IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Справка о подтверждающих документах 138И.
     *
     * @param \common\models\raiffeisenxml\request\ConfDocCertificate138IRaifType $confDocCertificate138IRaif
     * @return static
     */
    public function setConfDocCertificate138IRaif(\common\models\raiffeisenxml\request\ConfDocCertificate138IRaifType $confDocCertificate138IRaif)
    {
        $this->confDocCertificate138IRaif = $confDocCertificate138IRaif;
        return $this;
    }

    /**
     * Gets as confDocCertificate181IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Справка о подтверждающих документах 181И.
     *
     * @return \common\models\raiffeisenxml\request\ConfDocCertificate181IRaifType
     */
    public function getConfDocCertificate181IRaif()
    {
        return $this->confDocCertificate181IRaif;
    }

    /**
     * Sets a new confDocCertificate181IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Справка о подтверждающих документах 181И.
     *
     * @param \common\models\raiffeisenxml\request\ConfDocCertificate181IRaifType $confDocCertificate181IRaif
     * @return static
     */
    public function setConfDocCertificate181IRaif(\common\models\raiffeisenxml\request\ConfDocCertificate181IRaifType $confDocCertificate181IRaif)
    {
        $this->confDocCertificate181IRaif = $confDocCertificate181IRaif;
        return $this;
    }

    /**
     * Gets as currDealCertificate138I
     *
     * КОРОБКА. Справка о валютных операциях.
     *
     * @return \common\models\raiffeisenxml\request\CurrDealCertificate138IType
     */
    public function getCurrDealCertificate138I()
    {
        return $this->currDealCertificate138I;
    }

    /**
     * Sets a new currDealCertificate138I
     *
     * КОРОБКА. Справка о валютных операциях.
     *
     * @param \common\models\raiffeisenxml\request\CurrDealCertificate138IType $currDealCertificate138I
     * @return static
     */
    public function setCurrDealCertificate138I(\common\models\raiffeisenxml\request\CurrDealCertificate138IType $currDealCertificate138I)
    {
        $this->currDealCertificate138I = $currDealCertificate138I;
        return $this;
    }

    /**
     * Gets as currDealCertificate138IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Справка о валютных операциях 138И
     *
     * @return \common\models\raiffeisenxml\request\CurrDealCertificate138IRaifType
     */
    public function getCurrDealCertificate138IRaif()
    {
        return $this->currDealCertificate138IRaif;
    }

    /**
     * Sets a new currDealCertificate138IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Справка о валютных операциях 138И
     *
     * @param \common\models\raiffeisenxml\request\CurrDealCertificate138IRaifType $currDealCertificate138IRaif
     * @return static
     */
    public function setCurrDealCertificate138IRaif(\common\models\raiffeisenxml\request\CurrDealCertificate138IRaifType $currDealCertificate138IRaif)
    {
        $this->currDealCertificate138IRaif = $currDealCertificate138IRaif;
        return $this;
    }

    /**
     * Gets as dealPassCon138I
     *
     * КОРОБКА. Паспорт сделки по контракту 138И
     *
     * @return \common\models\raiffeisenxml\request\DealPassCon138IType
     */
    public function getDealPassCon138I()
    {
        return $this->dealPassCon138I;
    }

    /**
     * Sets a new dealPassCon138I
     *
     * КОРОБКА. Паспорт сделки по контракту 138И
     *
     * @param \common\models\raiffeisenxml\request\DealPassCon138IType $dealPassCon138I
     * @return static
     */
    public function setDealPassCon138I(\common\models\raiffeisenxml\request\DealPassCon138IType $dealPassCon138I)
    {
        $this->dealPassCon138I = $dealPassCon138I;
        return $this;
    }

    /**
     * Gets as dealPassCon138IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по контракту 138И
     *
     * @return \common\models\raiffeisenxml\request\DealPassCon138IRaifType
     */
    public function getDealPassCon138IRaif()
    {
        return $this->dealPassCon138IRaif;
    }

    /**
     * Sets a new dealPassCon138IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по контракту 138И
     *
     * @param \common\models\raiffeisenxml\request\DealPassCon138IRaifType $dealPassCon138IRaif
     * @return static
     */
    public function setDealPassCon138IRaif(\common\models\raiffeisenxml\request\DealPassCon138IRaifType $dealPassCon138IRaif)
    {
        $this->dealPassCon138IRaif = $dealPassCon138IRaif;
        return $this;
    }

    /**
     * Gets as dealPassCon181IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по контракту 181И
     *
     * @return \common\models\raiffeisenxml\request\DealPassCon181IRaifType
     */
    public function getDealPassCon181IRaif()
    {
        return $this->dealPassCon181IRaif;
    }

    /**
     * Sets a new dealPassCon181IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по контракту 181И
     *
     * @param \common\models\raiffeisenxml\request\DealPassCon181IRaifType $dealPassCon181IRaif
     * @return static
     */
    public function setDealPassCon181IRaif(\common\models\raiffeisenxml\request\DealPassCon181IRaifType $dealPassCon181IRaif)
    {
        $this->dealPassCon181IRaif = $dealPassCon181IRaif;
        return $this;
    }

    /**
     * Gets as dealPassCred138I
     *
     * КОРОБКА. Паспорт сделки по кредитному договору 138И
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred138IType
     */
    public function getDealPassCred138I()
    {
        return $this->dealPassCred138I;
    }

    /**
     * Sets a new dealPassCred138I
     *
     * КОРОБКА. Паспорт сделки по кредитному договору 138И
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred138IType $dealPassCred138I
     * @return static
     */
    public function setDealPassCred138I(\common\models\raiffeisenxml\request\DealPassCred138IType $dealPassCred138I)
    {
        $this->dealPassCred138I = $dealPassCred138I;
        return $this;
    }

    /**
     * Gets as dealPassCred138IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по кредитному договору 138И
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred138IRaifType
     */
    public function getDealPassCred138IRaif()
    {
        return $this->dealPassCred138IRaif;
    }

    /**
     * Sets a new dealPassCred138IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по кредитному договору 138И
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred138IRaifType $dealPassCred138IRaif
     * @return static
     */
    public function setDealPassCred138IRaif(\common\models\raiffeisenxml\request\DealPassCred138IRaifType $dealPassCred138IRaif)
    {
        $this->dealPassCred138IRaif = $dealPassCred138IRaif;
        return $this;
    }

    /**
     * Gets as dealPassCred181IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по кредитному договору 181И
     *
     * @return \common\models\raiffeisenxml\request\DealPassCred181IRaifType
     */
    public function getDealPassCred181IRaif()
    {
        return $this->dealPassCred181IRaif;
    }

    /**
     * Sets a new dealPassCred181IRaif
     *
     * РАЙФФАЙЗЕН БАНК. Паспорт сделки по кредитному договору 181И
     *
     * @param \common\models\raiffeisenxml\request\DealPassCred181IRaifType $dealPassCred181IRaif
     * @return static
     */
    public function setDealPassCred181IRaif(\common\models\raiffeisenxml\request\DealPassCred181IRaifType $dealPassCred181IRaif)
    {
        $this->dealPassCred181IRaif = $dealPassCred181IRaif;
        return $this;
    }

    /**
     * Gets as chanDP
     *
     * КОРОБКА. Заявление о переоформлении паспорта сделки 138И
     *
     * @return \common\models\raiffeisenxml\request\ChanDPType
     */
    public function getChanDP()
    {
        return $this->chanDP;
    }

    /**
     * Sets a new chanDP
     *
     * КОРОБКА. Заявление о переоформлении паспорта сделки 138И
     *
     * @param \common\models\raiffeisenxml\request\ChanDPType $chanDP
     * @return static
     */
    public function setChanDP(\common\models\raiffeisenxml\request\ChanDPType $chanDP)
    {
        $this->chanDP = $chanDP;
        return $this;
    }

    /**
     * Gets as chanDPConRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявление о переоформлении паспорта сделки по Контракту
     *
     * @return \common\models\raiffeisenxml\request\ChanDPConRaifType
     */
    public function getChanDPConRaif()
    {
        return $this->chanDPConRaif;
    }

    /**
     * Sets a new chanDPConRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявление о переоформлении паспорта сделки по Контракту
     *
     * @param \common\models\raiffeisenxml\request\ChanDPConRaifType $chanDPConRaif
     * @return static
     */
    public function setChanDPConRaif(\common\models\raiffeisenxml\request\ChanDPConRaifType $chanDPConRaif)
    {
        $this->chanDPConRaif = $chanDPConRaif;
        return $this;
    }

    /**
     * Gets as chanDPCredRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявление о переоформлении паспорта сделки по кредитному договору.
     *
     * @return \common\models\raiffeisenxml\request\ChanDPCredRaifType
     */
    public function getChanDPCredRaif()
    {
        return $this->chanDPCredRaif;
    }

    /**
     * Sets a new chanDPCredRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявление о переоформлении паспорта сделки по кредитному договору.
     *
     * @param \common\models\raiffeisenxml\request\ChanDPCredRaifType $chanDPCredRaif
     * @return static
     */
    public function setChanDPCredRaif(\common\models\raiffeisenxml\request\ChanDPCredRaifType $chanDPCredRaif)
    {
        $this->chanDPCredRaif = $chanDPCredRaif;
        return $this;
    }

    /**
     * Gets as clDP
     *
     * КОРОБКА. Заявление о закрытии паспорта сделки 138И
     *
     * @return \common\models\raiffeisenxml\request\ClDPType
     */
    public function getClDP()
    {
        return $this->clDP;
    }

    /**
     * Sets a new clDP
     *
     * КОРОБКА. Заявление о закрытии паспорта сделки 138И
     *
     * @param \common\models\raiffeisenxml\request\ClDPType $clDP
     * @return static
     */
    public function setClDP(\common\models\raiffeisenxml\request\ClDPType $clDP)
    {
        $this->clDP = $clDP;
        return $this;
    }

    /**
     * Gets as cIDPRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявление о закрытии паспорта сделки.
     *
     * @return \common\models\raiffeisenxml\request\CIDPRaifType
     */
    public function getCIDPRaif()
    {
        return $this->cIDPRaif;
    }

    /**
     * Sets a new cIDPRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявление о закрытии паспорта сделки.
     *
     * @param \common\models\raiffeisenxml\request\CIDPRaifType $cIDPRaif
     * @return static
     */
    public function setCIDPRaif(\common\models\raiffeisenxml\request\CIDPRaifType $cIDPRaif)
    {
        $this->cIDPRaif = $cIDPRaif;
        return $this;
    }

    /**
     * Gets as cIDPRaif181i
     *
     * РАЙФФАЙЗЕН БАНК. Заявление о закрытии паспорта сделки. 181И
     *
     * @return \common\models\raiffeisenxml\request\CIDPRaif181iType
     */
    public function getCIDPRaif181i()
    {
        return $this->cIDPRaif181i;
    }

    /**
     * Sets a new cIDPRaif181i
     *
     * РАЙФФАЙЗЕН БАНК. Заявление о закрытии паспорта сделки. 181И
     *
     * @param \common\models\raiffeisenxml\request\CIDPRaif181iType $cIDPRaif181i
     * @return static
     */
    public function setCIDPRaif181i(\common\models\raiffeisenxml\request\CIDPRaif181iType $cIDPRaif181i)
    {
        $this->cIDPRaif181i = $cIDPRaif181i;
        return $this;
    }

    /**
     * Gets as cCInfo
     *
     * РАЙФФАЙЗЕН БАНК. Информация для валютного контроля
     *
     * @return \common\models\raiffeisenxml\request\LetterInBankRaifType
     */
    public function getCCInfo()
    {
        return $this->cCInfo;
    }

    /**
     * Sets a new cCInfo
     *
     * РАЙФФАЙЗЕН БАНК. Информация для валютного контроля
     *
     * @param \common\models\raiffeisenxml\request\LetterInBankRaifType $cCInfo
     * @return static
     */
    public function setCCInfo(\common\models\raiffeisenxml\request\LetterInBankRaifType $cCInfo)
    {
        $this->cCInfo = $cCInfo;
        return $this;
    }

    /**
     * Gets as payDocCur
     *
     * КОРОБКА. Валютный перевод
     *
     * @return \common\models\raiffeisenxml\request\PayDocCurType
     */
    public function getPayDocCur()
    {
        return $this->payDocCur;
    }

    /**
     * Sets a new payDocCur
     *
     * КОРОБКА. Валютный перевод
     *
     * @param \common\models\raiffeisenxml\request\PayDocCurType $payDocCur
     * @return static
     */
    public function setPayDocCur(\common\models\raiffeisenxml\request\PayDocCurType $payDocCur)
    {
        $this->payDocCur = $payDocCur;
        return $this;
    }

    /**
     * Gets as payDocCurRaif
     *
     * РАЙФФАЙЗЕН БАНК. Валютный перевод
     *
     * @return \common\models\raiffeisenxml\request\PayDocCurRaifType
     */
    public function getPayDocCurRaif()
    {
        return $this->payDocCurRaif;
    }

    /**
     * Sets a new payDocCurRaif
     *
     * РАЙФФАЙЗЕН БАНК. Валютный перевод
     *
     * @param \common\models\raiffeisenxml\request\PayDocCurRaifType $payDocCurRaif
     * @return static
     */
    public function setPayDocCurRaif(\common\models\raiffeisenxml\request\PayDocCurRaifType $payDocCurRaif)
    {
        $this->payDocCurRaif = $payDocCurRaif;
        return $this;
    }

    /**
     * Gets as currSell
     *
     * КОРОБКА. Поручение на продажу
     *
     * @return \common\models\raiffeisenxml\request\CurrSellType
     */
    public function getCurrSell()
    {
        return $this->currSell;
    }

    /**
     * Sets a new currSell
     *
     * КОРОБКА. Поручение на продажу
     *
     * @param \common\models\raiffeisenxml\request\CurrSellType $currSell
     * @return static
     */
    public function setCurrSell(\common\models\raiffeisenxml\request\CurrSellType $currSell)
    {
        $this->currSell = $currSell;
        return $this;
    }

    /**
     * Gets as currSellRaif
     *
     * РАЙФФАЙЗЕН БАНК. Поручение на продажу
     *
     * @return \common\models\raiffeisenxml\request\CurrSellRaifType
     */
    public function getCurrSellRaif()
    {
        return $this->currSellRaif;
    }

    /**
     * Sets a new currSellRaif
     *
     * РАЙФФАЙЗЕН БАНК. Поручение на продажу
     *
     * @param \common\models\raiffeisenxml\request\CurrSellRaifType $currSellRaif
     * @return static
     */
    public function setCurrSellRaif(\common\models\raiffeisenxml\request\CurrSellRaifType $currSellRaif)
    {
        $this->currSellRaif = $currSellRaif;
        return $this;
    }

    /**
     * Gets as currBuy
     *
     * КОРОБКА. Поручение на покупку валюты
     *
     * @return \common\models\raiffeisenxml\request\CurrBuyType
     */
    public function getCurrBuy()
    {
        return $this->currBuy;
    }

    /**
     * Sets a new currBuy
     *
     * КОРОБКА. Поручение на покупку валюты
     *
     * @param \common\models\raiffeisenxml\request\CurrBuyType $currBuy
     * @return static
     */
    public function setCurrBuy(\common\models\raiffeisenxml\request\CurrBuyType $currBuy)
    {
        $this->currBuy = $currBuy;
        return $this;
    }

    /**
     * Gets as currBuyRaif
     *
     * РАЙФФАЙЗЕН БАНК. Поручение на покупку валюты
     *
     * @return \common\models\raiffeisenxml\request\CurrBuyRaifType
     */
    public function getCurrBuyRaif()
    {
        return $this->currBuyRaif;
    }

    /**
     * Sets a new currBuyRaif
     *
     * РАЙФФАЙЗЕН БАНК. Поручение на покупку валюты
     *
     * @param \common\models\raiffeisenxml\request\CurrBuyRaifType $currBuyRaif
     * @return static
     */
    public function setCurrBuyRaif(\common\models\raiffeisenxml\request\CurrBuyRaifType $currBuyRaif)
    {
        $this->currBuyRaif = $currBuyRaif;
        return $this;
    }

    /**
     * Gets as currConv
     *
     * КОРОБКА. Поручение на покупку / конверсию
     *
     * @return \common\models\raiffeisenxml\request\CurrConvType
     */
    public function getCurrConv()
    {
        return $this->currConv;
    }

    /**
     * Sets a new currConv
     *
     * КОРОБКА. Поручение на покупку / конверсию
     *
     * @param \common\models\raiffeisenxml\request\CurrConvType $currConv
     * @return static
     */
    public function setCurrConv(\common\models\raiffeisenxml\request\CurrConvType $currConv)
    {
        $this->currConv = $currConv;
        return $this;
    }

    /**
     * Gets as currConvRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявка на конвертацию
     *
     * @return \common\models\raiffeisenxml\request\CurrConvRaifType
     */
    public function getCurrConvRaif()
    {
        return $this->currConvRaif;
    }

    /**
     * Sets a new currConvRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявка на конвертацию
     *
     * @param \common\models\raiffeisenxml\request\CurrConvRaifType $currConvRaif
     * @return static
     */
    public function setCurrConvRaif(\common\models\raiffeisenxml\request\CurrConvRaifType $currConvRaif)
    {
        $this->currConvRaif = $currConvRaif;
        return $this;
    }

    /**
     * Gets as mandatorySale
     *
     * КОРОБКА. Распоряжение на списание средств с транзитного валютного счета
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleBoxType
     */
    public function getMandatorySale()
    {
        return $this->mandatorySale;
    }

    /**
     * Sets a new mandatorySale
     *
     * КОРОБКА. Распоряжение на списание средств с транзитного валютного счета
     *
     * @param \common\models\raiffeisenxml\request\MandatorySaleBoxType $mandatorySale
     * @return static
     */
    public function setMandatorySale(\common\models\raiffeisenxml\request\MandatorySaleBoxType $mandatorySale)
    {
        $this->mandatorySale = $mandatorySale;
        return $this;
    }

    /**
     * Gets as mandatorySaleRaif
     *
     * РАЙФФАЙЗЕН БАНК. Распоряжение о списание средств с транзитного счета
     *
     * @return \common\models\raiffeisenxml\request\MandatorySaleRaifType
     */
    public function getMandatorySaleRaif()
    {
        return $this->mandatorySaleRaif;
    }

    /**
     * Sets a new mandatorySaleRaif
     *
     * РАЙФФАЙЗЕН БАНК. Распоряжение о списание средств с транзитного счета
     *
     * @param \common\models\raiffeisenxml\request\MandatorySaleRaifType $mandatorySaleRaif
     * @return static
     */
    public function setMandatorySaleRaif(\common\models\raiffeisenxml\request\MandatorySaleRaifType $mandatorySaleRaif)
    {
        $this->mandatorySaleRaif = $mandatorySaleRaif;
        return $this;
    }

    /**
     * Gets as accreditiveRubRaif
     *
     * РАЙФФАЙЗЕН БАНК. Рублевый аккредитив.
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveRubRaifType
     */
    public function getAccreditiveRubRaif()
    {
        return $this->accreditiveRubRaif;
    }

    /**
     * Sets a new accreditiveRubRaif
     *
     * РАЙФФАЙЗЕН БАНК. Рублевый аккредитив.
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveRubRaifType $accreditiveRubRaif
     * @return static
     */
    public function setAccreditiveRubRaif(\common\models\raiffeisenxml\request\AccreditiveRubRaifType $accreditiveRubRaif)
    {
        $this->accreditiveRubRaif = $accreditiveRubRaif;
        return $this;
    }

    /**
     * Gets as accreditiveCurRaif
     *
     * РАЙФФАЙЗЕН БАНК. Валютный аккредитив.
     *
     * @return \common\models\raiffeisenxml\request\AccreditiveCurRaifType
     */
    public function getAccreditiveCurRaif()
    {
        return $this->accreditiveCurRaif;
    }

    /**
     * Sets a new accreditiveCurRaif
     *
     * РАЙФФАЙЗЕН БАНК. Валютный аккредитив.
     *
     * @param \common\models\raiffeisenxml\request\AccreditiveCurRaifType $accreditiveCurRaif
     * @return static
     */
    public function setAccreditiveCurRaif(\common\models\raiffeisenxml\request\AccreditiveCurRaifType $accreditiveCurRaif)
    {
        $this->accreditiveCurRaif = $accreditiveCurRaif;
        return $this;
    }

    /**
     * Gets as letterOfDepositRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявка на размещение депозита.
     *
     * @return \common\models\raiffeisenxml\request\LetterOfDepositRaifType
     */
    public function getLetterOfDepositRaif()
    {
        return $this->letterOfDepositRaif;
    }

    /**
     * Sets a new letterOfDepositRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявка на размещение депозита.
     *
     * @param \common\models\raiffeisenxml\request\LetterOfDepositRaifType $letterOfDepositRaif
     * @return static
     */
    public function setLetterOfDepositRaif(\common\models\raiffeisenxml\request\LetterOfDepositRaifType $letterOfDepositRaif)
    {
        $this->letterOfDepositRaif = $letterOfDepositRaif;
        return $this;
    }

    /**
     * Gets as creditRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявление на предоставление кредита.
     *
     * @return \common\models\raiffeisenxml\request\CreditRaifType
     */
    public function getCreditRaif()
    {
        return $this->creditRaif;
    }

    /**
     * Sets a new creditRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявление на предоставление кредита.
     *
     * @param \common\models\raiffeisenxml\request\CreditRaifType $creditRaif
     * @return static
     */
    public function setCreditRaif(\common\models\raiffeisenxml\request\CreditRaifType $creditRaif)
    {
        $this->creditRaif = $creditRaif;
        return $this;
    }

    /**
     * Gets as guaranteeRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявление на выдачу гарантии.
     *
     * @return \common\models\raiffeisenxml\request\GuaranteeRaifType
     */
    public function getGuaranteeRaif()
    {
        return $this->guaranteeRaif;
    }

    /**
     * Sets a new guaranteeRaif
     *
     * РАЙФФАЙЗЕН БАНК. Заявление на выдачу гарантии.
     *
     * @param \common\models\raiffeisenxml\request\GuaranteeRaifType $guaranteeRaif
     * @return static
     */
    public function setGuaranteeRaif(\common\models\raiffeisenxml\request\GuaranteeRaifType $guaranteeRaif)
    {
        $this->guaranteeRaif = $guaranteeRaif;
        return $this;
    }

    /**
     * Gets as incoming
     *
     * Запрос о получении данных, получаемых со стороны банка ("ночных выписок",
     *  выписок, писем из банка).
     *
     * @return \common\models\raiffeisenxml\request\RequestType\IncomingAType
     */
    public function getIncoming()
    {
        return $this->incoming;
    }

    /**
     * Sets a new incoming
     *
     * Запрос о получении данных, получаемых со стороны банка ("ночных выписок",
     *  выписок, писем из банка).
     *
     * @param \common\models\raiffeisenxml\request\RequestType\IncomingAType $incoming
     * @return static
     */
    public function setIncoming(\common\models\raiffeisenxml\request\RequestType\IncomingAType $incoming)
    {
        $this->incoming = $incoming;
        return $this;
    }

    /**
     * Gets as cryptoIncoming
     *
     * Запрос криптографической информации (криптопрофилей, сертификатов)
     *
     * @return \common\models\raiffeisenxml\request\RequestType\CryptoIncomingAType
     */
    public function getCryptoIncoming()
    {
        return $this->cryptoIncoming;
    }

    /**
     * Sets a new cryptoIncoming
     *
     * Запрос криптографической информации (криптопрофилей, сертификатов)
     *
     * @param \common\models\raiffeisenxml\request\RequestType\CryptoIncomingAType $cryptoIncoming
     * @return static
     */
    public function setCryptoIncoming(\common\models\raiffeisenxml\request\RequestType\CryptoIncomingAType $cryptoIncoming)
    {
        $this->cryptoIncoming = $cryptoIncoming;
        return $this;
    }

    /**
     * Gets as stmtReq
     *
     * КОРОБКА. Запрос на получение информации о движении ден. средств
     *
     * @return \common\models\raiffeisenxml\request\StmtReqType
     */
    public function getStmtReq()
    {
        return $this->stmtReq;
    }

    /**
     * Sets a new stmtReq
     *
     * КОРОБКА. Запрос на получение информации о движении ден. средств
     *
     * @param \common\models\raiffeisenxml\request\StmtReqType $stmtReq
     * @return static
     */
    public function setStmtReq(\common\models\raiffeisenxml\request\StmtReqType $stmtReq)
    {
        $this->stmtReq = $stmtReq;
        return $this;
    }

    /**
     * Gets as stmtReqRaif
     *
     * РАЙФФАЙЗЕН БАНК. Запрос на получение информации о движении ден. средств
     *
     * @return \common\models\raiffeisenxml\request\StmtReqTypeRaifType
     */
    public function getStmtReqRaif()
    {
        return $this->stmtReqRaif;
    }

    /**
     * Sets a new stmtReqRaif
     *
     * РАЙФФАЙЗЕН БАНК. Запрос на получение информации о движении ден. средств
     *
     * @param \common\models\raiffeisenxml\request\StmtReqTypeRaifType $stmtReqRaif
     * @return static
     */
    public function setStmtReqRaif(\common\models\raiffeisenxml\request\StmtReqTypeRaifType $stmtReqRaif)
    {
        $this->stmtReqRaif = $stmtReqRaif;
        return $this;
    }

    /**
     * Gets as personalInfo
     *
     * Запрос персональных данных
     *
     * @return \common\models\raiffeisenxml\request\RequestType\PersonalInfoAType
     */
    public function getPersonalInfo()
    {
        return $this->personalInfo;
    }

    /**
     * Sets a new personalInfo
     *
     * Запрос персональных данных
     *
     * @param \common\models\raiffeisenxml\request\RequestType\PersonalInfoAType $personalInfo
     * @return static
     */
    public function setPersonalInfo(\common\models\raiffeisenxml\request\RequestType\PersonalInfoAType $personalInfo)
    {
        $this->personalInfo = $personalInfo;
        return $this;
    }

    /**
     * Gets as stamps
     *
     * Запрос на получение штампов
     *
     * @return \common\models\raiffeisenxml\request\RequestType\StampsAType
     */
    public function getStamps()
    {
        return $this->stamps;
    }

    /**
     * Sets a new stamps
     *
     * Запрос на получение штампов
     *
     * @param \common\models\raiffeisenxml\request\RequestType\StampsAType $stamps
     * @return static
     */
    public function setStamps(\common\models\raiffeisenxml\request\RequestType\StampsAType $stamps)
    {
        $this->stamps = $stamps;
        return $this;
    }

    /**
     * Adds as dict
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\DictType $dict
     */
    public function addToDicts(\common\models\raiffeisenxml\request\DictType $dict)
    {
        $this->dicts[] = $dict;
        return $this;
    }

    /**
     * isset dicts
     *
     * @param int|string $index
     * @return bool
     */
    public function issetDicts($index)
    {
        return isset($this->dicts[$index]);
    }

    /**
     * unset dicts
     *
     * @param int|string $index
     * @return void
     */
    public function unsetDicts($index)
    {
        unset($this->dicts[$index]);
    }

    /**
     * Gets as dicts
     *
     * @return \common\models\raiffeisenxml\request\DictType[]
     */
    public function getDicts()
    {
        return $this->dicts;
    }

    /**
     * Sets a new dicts
     *
     * @param \common\models\raiffeisenxml\request\DictType[] $dicts
     * @return static
     */
    public function setDicts(array $dicts)
    {
        $this->dicts = $dicts;
        return $this;
    }

    /**
     * Gets as certifRequest
     *
     * Запрос на выпуск нового сертификата
     *
     * @return \common\models\raiffeisenxml\request\CertifRequestType
     */
    public function getCertifRequest()
    {
        return $this->certifRequest;
    }

    /**
     * Sets a new certifRequest
     *
     * Запрос на выпуск нового сертификата
     *
     * @param \common\models\raiffeisenxml\request\CertifRequestType $certifRequest
     * @return static
     */
    public function setCertifRequest(\common\models\raiffeisenxml\request\CertifRequestType $certifRequest)
    {
        $this->certifRequest = $certifRequest;
        return $this;
    }

    /**
     * Gets as clientAppUpdateRequest
     *
     * Запрос обновлений для Offline-клиента
     *
     * @return \common\models\raiffeisenxml\request\UpdateRequestType
     */
    public function getClientAppUpdateRequest()
    {
        return $this->clientAppUpdateRequest;
    }

    /**
     * Sets a new clientAppUpdateRequest
     *
     * Запрос обновлений для Offline-клиента
     *
     * @param \common\models\raiffeisenxml\request\UpdateRequestType $clientAppUpdateRequest
     * @return static
     */
    public function setClientAppUpdateRequest(\common\models\raiffeisenxml\request\UpdateRequestType $clientAppUpdateRequest)
    {
        $this->clientAppUpdateRequest = $clientAppUpdateRequest;
        return $this;
    }

    /**
     * Gets as changePasswordRequest
     *
     * Запрос на изменение пароля транпортной учетной записи
     *
     * @return \common\models\raiffeisenxml\request\ChangePasswordRequestType
     */
    public function getChangePasswordRequest()
    {
        return $this->changePasswordRequest;
    }

    /**
     * Sets a new changePasswordRequest
     *
     * Запрос на изменение пароля транпортной учетной записи
     *
     * @param \common\models\raiffeisenxml\request\ChangePasswordRequestType $changePasswordRequest
     * @return static
     */
    public function setChangePasswordRequest(\common\models\raiffeisenxml\request\ChangePasswordRequestType $changePasswordRequest)
    {
        $this->changePasswordRequest = $changePasswordRequest;
        return $this;
    }

    /**
     * Gets as logoutRequest
     *
     * Запрос закрытия сессии
     *
     * @return \common\models\raiffeisenxml\request\RequestType\LogoutRequestAType
     */
    public function getLogoutRequest()
    {
        return $this->logoutRequest;
    }

    /**
     * Sets a new logoutRequest
     *
     * Запрос закрытия сессии
     *
     * @param \common\models\raiffeisenxml\request\RequestType\LogoutRequestAType $logoutRequest
     * @return static
     */
    public function setLogoutRequest(\common\models\raiffeisenxml\request\RequestType\LogoutRequestAType $logoutRequest)
    {
        $this->logoutRequest = $logoutRequest;
        return $this;
    }

    /**
     * Gets as revocationRequest
     *
     * Запрос на отзыв документов
     *
     * @return \common\models\raiffeisenxml\request\RevocationRequestType
     */
    public function getRevocationRequest()
    {
        return $this->revocationRequest;
    }

    /**
     * Sets a new revocationRequest
     *
     * Запрос на отзыв документов
     *
     * @param \common\models\raiffeisenxml\request\RevocationRequestType $revocationRequest
     * @return static
     */
    public function setRevocationRequest(\common\models\raiffeisenxml\request\RevocationRequestType $revocationRequest)
    {
        $this->revocationRequest = $revocationRequest;
        return $this;
    }

    /**
     * Gets as ticketRaif
     *
     * РАЙФФАЙЗЕН БАНК. Подтверждение получения документа из Банка
     *
     * @return \common\models\raiffeisenxml\request\RequestType\TicketRaifAType
     */
    public function getTicketRaif()
    {
        return $this->ticketRaif;
    }

    /**
     * Sets a new ticketRaif
     *
     * РАЙФФАЙЗЕН БАНК. Подтверждение получения документа из Банка
     *
     * @param \common\models\raiffeisenxml\request\RequestType\TicketRaifAType $ticketRaif
     * @return static
     */
    public function setTicketRaif(\common\models\raiffeisenxml\request\RequestType\TicketRaifAType $ticketRaif)
    {
        $this->ticketRaif = $ticketRaif;
        return $this;
    }

    /**
     * Gets as letterTicket
     *
     * Подтверждение получения документа из Банка
     *
     * @return \common\models\raiffeisenxml\request\RequestType\LetterTicketAType
     */
    public function getLetterTicket()
    {
        return $this->letterTicket;
    }

    /**
     * Sets a new letterTicket
     *
     * Подтверждение получения документа из Банка
     *
     * @param \common\models\raiffeisenxml\request\RequestType\LetterTicketAType $letterTicket
     * @return static
     */
    public function setLetterTicket(\common\models\raiffeisenxml\request\RequestType\LetterTicketAType $letterTicket)
    {
        $this->letterTicket = $letterTicket;
        return $this;
    }

    /**
     * Adds as sign
     *
     * @return static
     * @param \common\models\raiffeisenxml\request\DigitalSignType $sign
     */
    public function addToSigns(\common\models\raiffeisenxml\request\DigitalSignType $sign)
    {
        $this->signs[] = $sign;
        return $this;
    }

    /**
     * isset signs
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSigns($index)
    {
        return isset($this->signs[$index]);
    }

    /**
     * unset signs
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSigns($index)
    {
        unset($this->signs[$index]);
    }

    /**
     * Gets as signs
     *
     * @return \common\models\raiffeisenxml\request\DigitalSignType[]
     */
    public function getSigns()
    {
        return $this->signs;
    }

    /**
     * Sets a new signs
     *
     * @param \common\models\raiffeisenxml\request\DigitalSignType[] $signs
     * @return static
     */
    public function setSigns(array $signs)
    {
        $this->signs = $signs;
        return $this;
    }

    /**
     * Gets as devicePrint
     *
     * Закодированное значение, полученное из скрипта "pm_fp.js" (Фрод)
     *
     * @return string
     */
    public function getDevicePrint()
    {
        return $this->devicePrint;
    }

    /**
     * Sets a new devicePrint
     *
     * Закодированное значение, полученное из скрипта "pm_fp.js" (Фрод)
     *
     * @param string $devicePrint
     * @return static
     */
    public function setDevicePrint($devicePrint)
    {
        $this->devicePrint = $devicePrint;
        return $this;
    }


}

