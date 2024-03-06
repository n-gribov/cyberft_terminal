<?php

namespace common\models\sbbolxml\response;

/**
 * Class representing ResponseType
 *
 *
 * XSD Type: Response
 */
class ResponseType
{

    /**
     * Дата и время формирования ответа местному по времени запрашивающего сервера, с
     *  указанием его часового пояса
     *
     * @property \DateTime $createTime
     */
    private $createTime = null;

    /**
     * Уникальный идентификатор ответа
     *
     * @property string $responseId
     */
    private $responseId = null;

    /**
     * Идентификатор запроса, в результате которого был сформирован данный ответ
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
     * Система-получатель. Если система получатель СББОЛ, то SBBOL_DBO
     *
     * @property string $receiver
     */
    private $receiver = null;

    /**
     * @property \common\models\sbbolxml\response\ErrorType[] $errors
     */
    private $errors = null;

    /**
     * Прими квитки о получении документов из УС
     *  Получи квитки из СББОЛ
     *
     * @property \common\models\sbbolxml\response\TicketType[] $tickets
     */
    private $tickets = null;

    /**
     * Выписки
     *
     * @property \common\models\sbbolxml\response\ResponseType\StatementsAType $statements
     */
    private $statements = null;

    /**
     * Письмо для целей ВК (из банка)
     *
     * @property \common\models\sbbolxml\response\ExchangeMessagesFromBankType[] $exchangeMessagesFromBank
     */
    private $exchangeMessagesFromBank = null;

    /**
     * Письма из банка
     *
     * @property \common\models\sbbolxml\response\LetterFromBankType[] $lettersFromBank
     */
    private $lettersFromBank = null;

    /**
     * Содержит список криптопрофилей для данной организации. Является ответом на
     *  запрос информации о
     *  криптопрофилях.
     *
     * @property \common\models\sbbolxml\response\CryptoproType[] $cryptopros
     */
    private $cryptopros = null;

    /**
     * Содержит данные сертификатов
     *
     * @property \common\models\sbbolxml\response\CertificateType[] $certificates
     */
    private $certificates = null;

    /**
     * Список отозванных сертификатов
     *
     * @property \common\models\sbbolxml\response\ResponseType\RevocationCertificatesAType $revocationCertificates
     */
    private $revocationCertificates = null;

    /**
     * Ответ, содержащий персональные данные организаций для одной транспортной
     *  учетной записи
     *
     * @property \common\models\sbbolxml\response\OrgInfoType[] $orgsInfo
     */
    private $orgsInfo = null;

    /**
     * Ответ, содержащий персональные данные организаций для одной транспортной учетной записи
     *
     * @property \common\models\sbbolxml\response\OrganizationInfoType[] $organizationsInfo
     */
    private $organizationsInfo = null;

    /**
     * Ответ, содержащий список дочерних организаций с указанием счетов
     *
     * @property \common\models\sbbolxml\response\ResponseType\HoldingInfoAType $holdingInfo
     */
    private $holdingInfo = null;

    /**
     * Ошибка контроля ИЛИ отправили СМС
     *
     * @property \common\models\sbbolxml\response\ResponseType\StatusSMSAType $statusSMS
     */
    private $statusSMS = null;

    /**
     * @property \common\models\sbbolxml\response\ClientAppUpdateType[] $clientAppUpdates
     */
    private $clientAppUpdates = null;

    /**
     * Версия и ссылка на обновление прошивки
     *
     * @property \common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType $firmwareUpdate
     */
    private $firmwareUpdate = null;

    /**
     * Версия и ссылка на текущую версию оффлайн-клиента в соотвествии с ОС и типом
     *  оффлайна, переданных в запросе
     *
     * @property \common\models\sbbolxml\response\ResponseType\OfflineVersionAType $offlineVersion
     */
    private $offlineVersion = null;

    /**
     * Репликация справочников
     *
     * @property \common\models\sbbolxml\response\DictType[] $dict
     */
    private $dict = array(
        
    );

    /**
     * @property \common\models\sbbolxml\response\ResponseType\OrgSettingsAType $orgSettings
     */
    private $orgSettings = null;

    /**
     * @property \common\models\sbbolxml\response\ResponseType\NewsAType\NewAType[] $news
     */
    private $news = null;

    /**
     * Корреспондент
     *
     * @property \common\models\sbbolxml\response\ResponseType\CorrespondentAType $correspondent
     */
    private $correspondent = null;

    /**
     * Идентификатор sms-криптопрофиля
     *
     * @property string $smsCryptoProfile
     */
    private $smsCryptoProfile = null;

    /**
     * Входящие платежные требования
     *
     * @property \common\models\sbbolxml\response\PayRequestType[] $payRequests
     */
    private $payRequests = null;

    /**
     * Измененные документы
     *
     * @property \common\models\sbbolxml\response\ResponseType\ChangedDocsAType $changedDocs
     */
    private $changedDocs = null;

    /**
     * Платежные поручения
     *
     * @property \common\models\sbbolxml\response\RZKPayDocRuType[] $payDocsRu
     */
    private $payDocsRu = null;

    /**
     * Поручения на перевод валюты
     *
     * @property \common\models\sbbolxml\response\RZKPayDocCurType[] $payDocsCur
     */
    private $payDocsCur = null;

    /**
     * Электронные реестры (Зарплатные ведомости)
     *
     * @property \common\models\sbbolxml\response\RZKSalaryDocType[] $salaryDocs
     */
    private $salaryDocs = null;

    /**
     * @property \common\models\sbbolxml\response\ListOfEmployeesType $listOfEmployees
     */
    private $listOfEmployees = null;

    /**
     * @property \common\models\sbbolxml\response\DocTypeConfigType[] $docTypeConfigs
     */
    private $docTypeConfigs = null;

    /**
     * Список ведомостей банковского контроля
     *
     * @property \common\models\sbbolxml\response\IntCtrlStatement181IType[] $listIntCtrlStatement181I
     */
    private $listIntCtrlStatement181I = null;

    /**
     * Список паспортов сделки по контракту
     *
     * @property \common\models\sbbolxml\response\DealPassCon138IType[] $listDealPassCon138I
     */
    private $listDealPassCon138I = null;

    /**
     * Список паспортов сделки по кредитному договору
     *
     * @property \common\models\sbbolxml\response\DealPassCred138IType[] $listDealPassCred138I
     */
    private $listDealPassCred138I = null;

    /**
     * Запрос сведений о клиенте
     *
     * @property \common\models\sbbolxml\response\ISKRequestType $iSKRequest
     */
    private $iSKRequest = null;

    /**
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @property \common\models\sbbolxml\response\CurrencyNoticeType[] $currencyNotices
     */
    private $currencyNotices = null;

    /**
     * @property \common\models\sbbolxml\response\ResponsePartType $responsePart
     */
    private $responsePart = null;

    /**
     * Чаты с банком
     *
     * @property \common\models\sbbolxml\response\ChatWithBankMsgType[] $chatWithBankMsgs
     */
    private $chatWithBankMsgs = null;

    /**
     * Справочник курсов валют
     *
     * @property \common\models\sbbolxml\response\ExRateDetailType[] $currCourseEntry
     */
    private $currCourseEntry = null;

    /**
     * @property \common\models\sbbolxml\response\EssenceType[] $linksToBigFiles
     */
    private $linksToBigFiles = null;

    /**
     * @property \common\models\sbbolxml\response\BigFilesStatusType[] $bigFilesStatus
     */
    private $bigFilesStatus = null;

    /**
     * Сообщения о подтверждении сделок
     *
     * @property \common\models\sbbolxml\response\DealConfType[] $dealConfs
     */
    private $dealConfs = null;

    /**
     * @property \common\models\sbbolxml\response\SmsTimeoutsType $smsTimeouts
     */
    private $smsTimeouts = null;

    /**
     * Роли
     *
     * @property \common\models\sbbolxml\response\IncomingRolesType $incomingRoles
     */
    private $incomingRoles = null;

    /**
     * Роли пользователей
     *
     * @property \common\models\sbbolxml\response\UserRolesType $userRoles
     */
    private $userRoles = null;

    /**
     * Предложения
     *
     * @property \common\models\sbbolxml\response\OffersType $offers
     */
    private $offers = null;

    /**
     * Шаблоны внесения средств
     *
     * @property \common\models\sbbolxml\response\AdmPaymentTemplatesType $admPayTemplates
     */
    private $admPayTemplates = null;

    /**
     * Список вносителей средств
     *
     * @property \common\models\sbbolxml\response\AdmCashiersType $admCashiers
     */
    private $admCashiers = null;

    /**
     * Операции внесения средств
     *
     * @property \common\models\sbbolxml\response\AdmOperationsType $admOperations
     */
    private $admOperations = null;

    /**
     * Передача логина вносителя средств в УС
     *
     * @property \common\models\sbbolxml\response\AdmCashierLoginType $admCashierLogin
     */
    private $admCashierLogin = null;

    /**
     * Список карточек депозита
     *
     * @property \common\models\sbbolxml\response\CardDepositsType $cardDeposits
     */
    private $cardDeposits = null;

    /**
     * Список договоров НСО
     *
     * @property \common\models\sbbolxml\response\CardPermBalanceListType $cardPermBalanceList
     */
    private $cardPermBalanceList = null;

    /**
     * Реестры платежей
     *
     * @property \common\models\sbbolxml\response\FeesRegistriesType $feesRegistries
     */
    private $feesRegistries = null;

    /**
     * Ссылки на скачивание
     *
     * @property \common\models\sbbolxml\response\DownloadLinksType $downloadLinks
     */
    private $downloadLinks = null;

    /**
     * Ссылки на загрузку файлов
     *
     * @property \common\models\sbbolxml\response\UploadLinksType $uploadLinks
     */
    private $uploadLinks = null;

    /**
     * Информация о ведомости банковского контроля
     *
     * @property \common\models\sbbolxml\response\IntCtrlStatementXML181IType $intCtrlStatementXML181I
     */
    private $intCtrlStatementXML181I = null;

    /**
     * Письма свободного формата из банка
     *
     * @property \common\models\sbbolxml\response\GenericLetterFromBankType[] $genericLettersFromBank
     */
    private $genericLettersFromBank = null;

    /**
     * Дайджест документа
     *
     * @property string $digest
     */
    private $digest = null;

    /**
     * Gets as createTime
     *
     * Дата и время формирования ответа местному по времени запрашивающего сервера, с
     *  указанием его часового пояса
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Sets a new createTime
     *
     * Дата и время формирования ответа местному по времени запрашивающего сервера, с
     *  указанием его часового пояса
     *
     * @param \DateTime $createTime
     * @return static
     */
    public function setCreateTime(\DateTime $createTime)
    {
        $this->createTime = $createTime;
        return $this;
    }

    /**
     * Gets as responseId
     *
     * Уникальный идентификатор ответа
     *
     * @return string
     */
    public function getResponseId()
    {
        return $this->responseId;
    }

    /**
     * Sets a new responseId
     *
     * Уникальный идентификатор ответа
     *
     * @param string $responseId
     * @return static
     */
    public function setResponseId($responseId)
    {
        $this->responseId = $responseId;
        return $this;
    }

    /**
     * Gets as requestId
     *
     * Идентификатор запроса, в результате которого был сформирован данный ответ
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
     * Идентификатор запроса, в результате которого был сформирован данный ответ
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
     * Система-получатель. Если система получатель СББОЛ, то SBBOL_DBO
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
     * Система-получатель. Если система получатель СББОЛ, то SBBOL_DBO
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
     * Adds as error
     *
     * @return static
     * @param \common\models\sbbolxml\response\ErrorType $error
     */
    public function addToErrors(\common\models\sbbolxml\response\ErrorType $error)
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * isset errors
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetErrors($index)
    {
        return isset($this->errors[$index]);
    }

    /**
     * unset errors
     *
     * @param scalar $index
     * @return void
     */
    public function unsetErrors($index)
    {
        unset($this->errors[$index]);
    }

    /**
     * Gets as errors
     *
     * @return \common\models\sbbolxml\response\ErrorType[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets a new errors
     *
     * @param \common\models\sbbolxml\response\ErrorType[] $errors
     * @return static
     */
    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        return $this;
    }

    /**
     * Adds as ticket
     *
     * Прими квитки о получении документов из УС
     *  Получи квитки из СББОЛ
     *
     * @return static
     * @param \common\models\sbbolxml\response\TicketType $ticket
     */
    public function addToTickets(\common\models\sbbolxml\response\TicketType $ticket)
    {
        $this->tickets[] = $ticket;
        return $this;
    }

    /**
     * isset tickets
     *
     * Прими квитки о получении документов из УС
     *  Получи квитки из СББОЛ
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetTickets($index)
    {
        return isset($this->tickets[$index]);
    }

    /**
     * unset tickets
     *
     * Прими квитки о получении документов из УС
     *  Получи квитки из СББОЛ
     *
     * @param scalar $index
     * @return void
     */
    public function unsetTickets($index)
    {
        unset($this->tickets[$index]);
    }

    /**
     * Gets as tickets
     *
     * Прими квитки о получении документов из УС
     *  Получи квитки из СББОЛ
     *
     * @return \common\models\sbbolxml\response\TicketType[]
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Sets a new tickets
     *
     * Прими квитки о получении документов из УС
     *  Получи квитки из СББОЛ
     *
     * @param \common\models\sbbolxml\response\TicketType[] $tickets
     * @return static
     */
    public function setTickets(array $tickets)
    {
        $this->tickets = $tickets;
        return $this;
    }

    /**
     * Gets as statements
     *
     * Выписки
     *
     * @return \common\models\sbbolxml\response\ResponseType\StatementsAType
     */
    public function getStatements()
    {
        return $this->statements;
    }

    /**
     * Sets a new statements
     *
     * Выписки
     *
     * @param \common\models\sbbolxml\response\ResponseType\StatementsAType $statements
     * @return static
     */
    public function setStatements(\common\models\sbbolxml\response\ResponseType\StatementsAType $statements)
    {
        $this->statements = $statements;
        return $this;
    }

    /**
     * Adds as exchangeMessageFromBank
     *
     * Письмо для целей ВК (из банка)
     *
     * @return static
     * @param \common\models\sbbolxml\response\ExchangeMessagesFromBankType $exchangeMessageFromBank
     */
    public function addToExchangeMessagesFromBank(\common\models\sbbolxml\response\ExchangeMessagesFromBankType $exchangeMessageFromBank)
    {
        $this->exchangeMessagesFromBank[] = $exchangeMessageFromBank;
        return $this;
    }

    /**
     * isset exchangeMessagesFromBank
     *
     * Письмо для целей ВК (из банка)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetExchangeMessagesFromBank($index)
    {
        return isset($this->exchangeMessagesFromBank[$index]);
    }

    /**
     * unset exchangeMessagesFromBank
     *
     * Письмо для целей ВК (из банка)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetExchangeMessagesFromBank($index)
    {
        unset($this->exchangeMessagesFromBank[$index]);
    }

    /**
     * Gets as exchangeMessagesFromBank
     *
     * Письмо для целей ВК (из банка)
     *
     * @return \common\models\sbbolxml\response\ExchangeMessagesFromBankType[]
     */
    public function getExchangeMessagesFromBank()
    {
        return $this->exchangeMessagesFromBank;
    }

    /**
     * Sets a new exchangeMessagesFromBank
     *
     * Письмо для целей ВК (из банка)
     *
     * @param \common\models\sbbolxml\response\ExchangeMessagesFromBankType[] $exchangeMessagesFromBank
     * @return static
     */
    public function setExchangeMessagesFromBank(array $exchangeMessagesFromBank)
    {
        $this->exchangeMessagesFromBank = $exchangeMessagesFromBank;
        return $this;
    }

    /**
     * Adds as letterFromBank
     *
     * Письма из банка
     *
     * @return static
     * @param \common\models\sbbolxml\response\LetterFromBankType $letterFromBank
     */
    public function addToLettersFromBank(\common\models\sbbolxml\response\LetterFromBankType $letterFromBank)
    {
        $this->lettersFromBank[] = $letterFromBank;
        return $this;
    }

    /**
     * isset lettersFromBank
     *
     * Письма из банка
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetLettersFromBank($index)
    {
        return isset($this->lettersFromBank[$index]);
    }

    /**
     * unset lettersFromBank
     *
     * Письма из банка
     *
     * @param scalar $index
     * @return void
     */
    public function unsetLettersFromBank($index)
    {
        unset($this->lettersFromBank[$index]);
    }

    /**
     * Gets as lettersFromBank
     *
     * Письма из банка
     *
     * @return \common\models\sbbolxml\response\LetterFromBankType[]
     */
    public function getLettersFromBank()
    {
        return $this->lettersFromBank;
    }

    /**
     * Sets a new lettersFromBank
     *
     * Письма из банка
     *
     * @param \common\models\sbbolxml\response\LetterFromBankType[] $lettersFromBank
     * @return static
     */
    public function setLettersFromBank(array $lettersFromBank)
    {
        $this->lettersFromBank = $lettersFromBank;
        return $this;
    }

    /**
     * Adds as cryptopro
     *
     * Содержит список криптопрофилей для данной организации. Является ответом на
     *  запрос информации о
     *  криптопрофилях.
     *
     * @return static
     * @param \common\models\sbbolxml\response\CryptoproType $cryptopro
     */
    public function addToCryptopros(\common\models\sbbolxml\response\CryptoproType $cryptopro)
    {
        $this->cryptopros[] = $cryptopro;
        return $this;
    }

    /**
     * isset cryptopros
     *
     * Содержит список криптопрофилей для данной организации. Является ответом на
     *  запрос информации о
     *  криптопрофилях.
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCryptopros($index)
    {
        return isset($this->cryptopros[$index]);
    }

    /**
     * unset cryptopros
     *
     * Содержит список криптопрофилей для данной организации. Является ответом на
     *  запрос информации о
     *  криптопрофилях.
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCryptopros($index)
    {
        unset($this->cryptopros[$index]);
    }

    /**
     * Gets as cryptopros
     *
     * Содержит список криптопрофилей для данной организации. Является ответом на
     *  запрос информации о
     *  криптопрофилях.
     *
     * @return \common\models\sbbolxml\response\CryptoproType[]
     */
    public function getCryptopros()
    {
        return $this->cryptopros;
    }

    /**
     * Sets a new cryptopros
     *
     * Содержит список криптопрофилей для данной организации. Является ответом на
     *  запрос информации о
     *  криптопрофилях.
     *
     * @param \common\models\sbbolxml\response\CryptoproType[] $cryptopros
     * @return static
     */
    public function setCryptopros(array $cryptopros)
    {
        $this->cryptopros = $cryptopros;
        return $this;
    }

    /**
     * Adds as certificate
     *
     * Содержит данные сертификатов
     *
     * @return static
     * @param \common\models\sbbolxml\response\CertificateType $certificate
     */
    public function addToCertificates(\common\models\sbbolxml\response\CertificateType $certificate)
    {
        $this->certificates[] = $certificate;
        return $this;
    }

    /**
     * isset certificates
     *
     * Содержит данные сертификатов
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCertificates($index)
    {
        return isset($this->certificates[$index]);
    }

    /**
     * unset certificates
     *
     * Содержит данные сертификатов
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCertificates($index)
    {
        unset($this->certificates[$index]);
    }

    /**
     * Gets as certificates
     *
     * Содержит данные сертификатов
     *
     * @return \common\models\sbbolxml\response\CertificateType[]
     */
    public function getCertificates()
    {
        return $this->certificates;
    }

    /**
     * Sets a new certificates
     *
     * Содержит данные сертификатов
     *
     * @param \common\models\sbbolxml\response\CertificateType[] $certificates
     * @return static
     */
    public function setCertificates(array $certificates)
    {
        $this->certificates = $certificates;
        return $this;
    }

    /**
     * Gets as revocationCertificates
     *
     * Список отозванных сертификатов
     *
     * @return \common\models\sbbolxml\response\ResponseType\RevocationCertificatesAType
     */
    public function getRevocationCertificates()
    {
        return $this->revocationCertificates;
    }

    /**
     * Sets a new revocationCertificates
     *
     * Список отозванных сертификатов
     *
     * @param \common\models\sbbolxml\response\ResponseType\RevocationCertificatesAType $revocationCertificates
     * @return static
     */
    public function setRevocationCertificates(\common\models\sbbolxml\response\ResponseType\RevocationCertificatesAType $revocationCertificates)
    {
        $this->revocationCertificates = $revocationCertificates;
        return $this;
    }

    /**
     * Adds as orgInfo
     *
     * Ответ, содержащий персональные данные организаций для одной транспортной
     *  учетной записи
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrgInfoType $orgInfo
     */
    public function addToOrgsInfo(\common\models\sbbolxml\response\OrgInfoType $orgInfo)
    {
        $this->orgsInfo[] = $orgInfo;
        return $this;
    }

    /**
     * isset orgsInfo
     *
     * Ответ, содержащий персональные данные организаций для одной транспортной
     *  учетной записи
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOrgsInfo($index)
    {
        return isset($this->orgsInfo[$index]);
    }

    /**
     * unset orgsInfo
     *
     * Ответ, содержащий персональные данные организаций для одной транспортной
     *  учетной записи
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOrgsInfo($index)
    {
        unset($this->orgsInfo[$index]);
    }

    /**
     * Gets as orgsInfo
     *
     * Ответ, содержащий персональные данные организаций для одной транспортной
     *  учетной записи
     *
     * @return \common\models\sbbolxml\response\OrgInfoType[]
     */
    public function getOrgsInfo()
    {
        return $this->orgsInfo;
    }

    /**
     * Sets a new orgsInfo
     *
     * Ответ, содержащий персональные данные организаций для одной транспортной
     *  учетной записи
     *
     * @param \common\models\sbbolxml\response\OrgInfoType[] $orgsInfo
     * @return static
     */
    public function setOrgsInfo(array $orgsInfo)
    {
        $this->orgsInfo = $orgsInfo;
        return $this;
    }

    /**
     * Adds as organizationInfo
     *
     * Ответ, содержащий персональные данные организаций для одной транспортной учетной записи
     *
     * @return static
     * @param \common\models\sbbolxml\response\OrganizationInfoType $organizationInfo
     */
    public function addToOrganizationsInfo(\common\models\sbbolxml\response\OrganizationInfoType $organizationInfo)
    {
        $this->organizationsInfo[] = $organizationInfo;
        return $this;
    }

    /**
     * isset organizationsInfo
     *
     * Ответ, содержащий персональные данные организаций для одной транспортной учетной записи
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOrganizationsInfo($index)
    {
        return isset($this->organizationsInfo[$index]);
    }

    /**
     * unset organizationsInfo
     *
     * Ответ, содержащий персональные данные организаций для одной транспортной учетной записи
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOrganizationsInfo($index)
    {
        unset($this->organizationsInfo[$index]);
    }

    /**
     * Gets as organizationsInfo
     *
     * Ответ, содержащий персональные данные организаций для одной транспортной учетной записи
     *
     * @return \common\models\sbbolxml\response\OrganizationInfoType[]
     */
    public function getOrganizationsInfo()
    {
        return $this->organizationsInfo;
    }

    /**
     * Sets a new organizationsInfo
     *
     * Ответ, содержащий персональные данные организаций для одной транспортной учетной записи
     *
     * @param \common\models\sbbolxml\response\OrganizationInfoType[] $organizationsInfo
     * @return static
     */
    public function setOrganizationsInfo(array $organizationsInfo)
    {
        $this->organizationsInfo = $organizationsInfo;
        return $this;
    }

    /**
     * Gets as holdingInfo
     *
     * Ответ, содержащий список дочерних организаций с указанием счетов
     *
     * @return \common\models\sbbolxml\response\ResponseType\HoldingInfoAType
     */
    public function getHoldingInfo()
    {
        return $this->holdingInfo;
    }

    /**
     * Sets a new holdingInfo
     *
     * Ответ, содержащий список дочерних организаций с указанием счетов
     *
     * @param \common\models\sbbolxml\response\ResponseType\HoldingInfoAType $holdingInfo
     * @return static
     */
    public function setHoldingInfo(\common\models\sbbolxml\response\ResponseType\HoldingInfoAType $holdingInfo)
    {
        $this->holdingInfo = $holdingInfo;
        return $this;
    }

    /**
     * Gets as statusSMS
     *
     * Ошибка контроля ИЛИ отправили СМС
     *
     * @return \common\models\sbbolxml\response\ResponseType\StatusSMSAType
     */
    public function getStatusSMS()
    {
        return $this->statusSMS;
    }

    /**
     * Sets a new statusSMS
     *
     * Ошибка контроля ИЛИ отправили СМС
     *
     * @param \common\models\sbbolxml\response\ResponseType\StatusSMSAType $statusSMS
     * @return static
     */
    public function setStatusSMS(\common\models\sbbolxml\response\ResponseType\StatusSMSAType $statusSMS)
    {
        $this->statusSMS = $statusSMS;
        return $this;
    }

    /**
     * Adds as clientAppUpdates
     *
     * @return static
     * @param \common\models\sbbolxml\response\ClientAppUpdateType $clientAppUpdates
     */
    public function addToClientAppUpdates(\common\models\sbbolxml\response\ClientAppUpdateType $clientAppUpdates)
    {
        $this->clientAppUpdates[] = $clientAppUpdates;
        return $this;
    }

    /**
     * isset clientAppUpdates
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetClientAppUpdates($index)
    {
        return isset($this->clientAppUpdates[$index]);
    }

    /**
     * unset clientAppUpdates
     *
     * @param scalar $index
     * @return void
     */
    public function unsetClientAppUpdates($index)
    {
        unset($this->clientAppUpdates[$index]);
    }

    /**
     * Gets as clientAppUpdates
     *
     * @return \common\models\sbbolxml\response\ClientAppUpdateType[]
     */
    public function getClientAppUpdates()
    {
        return $this->clientAppUpdates;
    }

    /**
     * Sets a new clientAppUpdates
     *
     * @param \common\models\sbbolxml\response\ClientAppUpdateType[] $clientAppUpdates
     * @return static
     */
    public function setClientAppUpdates(array $clientAppUpdates)
    {
        $this->clientAppUpdates = $clientAppUpdates;
        return $this;
    }

    /**
     * Gets as firmwareUpdate
     *
     * Версия и ссылка на обновление прошивки
     *
     * @return \common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType
     */
    public function getFirmwareUpdate()
    {
        return $this->firmwareUpdate;
    }

    /**
     * Sets a new firmwareUpdate
     *
     * Версия и ссылка на обновление прошивки
     *
     * @param \common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType $firmwareUpdate
     * @return static
     */
    public function setFirmwareUpdate(\common\models\sbbolxml\response\ResponseType\FirmwareUpdateAType $firmwareUpdate)
    {
        $this->firmwareUpdate = $firmwareUpdate;
        return $this;
    }

    /**
     * Gets as offlineVersion
     *
     * Версия и ссылка на текущую версию оффлайн-клиента в соотвествии с ОС и типом
     *  оффлайна, переданных в запросе
     *
     * @return \common\models\sbbolxml\response\ResponseType\OfflineVersionAType
     */
    public function getOfflineVersion()
    {
        return $this->offlineVersion;
    }

    /**
     * Sets a new offlineVersion
     *
     * Версия и ссылка на текущую версию оффлайн-клиента в соотвествии с ОС и типом
     *  оффлайна, переданных в запросе
     *
     * @param \common\models\sbbolxml\response\ResponseType\OfflineVersionAType $offlineVersion
     * @return static
     */
    public function setOfflineVersion(\common\models\sbbolxml\response\ResponseType\OfflineVersionAType $offlineVersion)
    {
        $this->offlineVersion = $offlineVersion;
        return $this;
    }

    /**
     * Adds as dict
     *
     * Репликация справочников
     *
     * @return static
     * @param \common\models\sbbolxml\response\DictType $dict
     */
    public function addToDict(\common\models\sbbolxml\response\DictType $dict)
    {
        $this->dict[] = $dict;
        return $this;
    }

    /**
     * isset dict
     *
     * Репликация справочников
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDict($index)
    {
        return isset($this->dict[$index]);
    }

    /**
     * unset dict
     *
     * Репликация справочников
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDict($index)
    {
        unset($this->dict[$index]);
    }

    /**
     * Gets as dict
     *
     * Репликация справочников
     *
     * @return \common\models\sbbolxml\response\DictType[]
     */
    public function getDict()
    {
        return $this->dict;
    }

    /**
     * Sets a new dict
     *
     * Репликация справочников
     *
     * @param \common\models\sbbolxml\response\DictType[] $dict
     * @return static
     */
    public function setDict(array $dict)
    {
        $this->dict = $dict;
        return $this;
    }

    /**
     * Gets as orgSettings
     *
     * @return \common\models\sbbolxml\response\ResponseType\OrgSettingsAType
     */
    public function getOrgSettings()
    {
        return $this->orgSettings;
    }

    /**
     * Sets a new orgSettings
     *
     * @param \common\models\sbbolxml\response\ResponseType\OrgSettingsAType $orgSettings
     * @return static
     */
    public function setOrgSettings(\common\models\sbbolxml\response\ResponseType\OrgSettingsAType $orgSettings)
    {
        $this->orgSettings = $orgSettings;
        return $this;
    }

    /**
     * Adds as new
     *
     * @return static
     * @param \common\models\sbbolxml\response\ResponseType\NewsAType\NewAType $new
     */
    public function addToNews(\common\models\sbbolxml\response\ResponseType\NewsAType\NewAType $new)
    {
        $this->news[] = $new;
        return $this;
    }

    /**
     * isset news
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetNews($index)
    {
        return isset($this->news[$index]);
    }

    /**
     * unset news
     *
     * @param scalar $index
     * @return void
     */
    public function unsetNews($index)
    {
        unset($this->news[$index]);
    }

    /**
     * Gets as news
     *
     * @return \common\models\sbbolxml\response\ResponseType\NewsAType\NewAType[]
     */
    public function getNews()
    {
        return $this->news;
    }

    /**
     * Sets a new news
     *
     * @param \common\models\sbbolxml\response\ResponseType\NewsAType\NewAType[] $news
     * @return static
     */
    public function setNews(array $news)
    {
        $this->news = $news;
        return $this;
    }

    /**
     * Gets as correspondent
     *
     * Корреспондент
     *
     * @return \common\models\sbbolxml\response\ResponseType\CorrespondentAType
     */
    public function getCorrespondent()
    {
        return $this->correspondent;
    }

    /**
     * Sets a new correspondent
     *
     * Корреспондент
     *
     * @param \common\models\sbbolxml\response\ResponseType\CorrespondentAType $correspondent
     * @return static
     */
    public function setCorrespondent(\common\models\sbbolxml\response\ResponseType\CorrespondentAType $correspondent)
    {
        $this->correspondent = $correspondent;
        return $this;
    }

    /**
     * Gets as smsCryptoProfile
     *
     * Идентификатор sms-криптопрофиля
     *
     * @return string
     */
    public function getSmsCryptoProfile()
    {
        return $this->smsCryptoProfile;
    }

    /**
     * Sets a new smsCryptoProfile
     *
     * Идентификатор sms-криптопрофиля
     *
     * @param string $smsCryptoProfile
     * @return static
     */
    public function setSmsCryptoProfile($smsCryptoProfile)
    {
        $this->smsCryptoProfile = $smsCryptoProfile;
        return $this;
    }

    /**
     * Adds as payRequest
     *
     * Входящие платежные требования
     *
     * @return static
     * @param \common\models\sbbolxml\response\PayRequestType $payRequest
     */
    public function addToPayRequests(\common\models\sbbolxml\response\PayRequestType $payRequest)
    {
        $this->payRequests[] = $payRequest;
        return $this;
    }

    /**
     * isset payRequests
     *
     * Входящие платежные требования
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPayRequests($index)
    {
        return isset($this->payRequests[$index]);
    }

    /**
     * unset payRequests
     *
     * Входящие платежные требования
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPayRequests($index)
    {
        unset($this->payRequests[$index]);
    }

    /**
     * Gets as payRequests
     *
     * Входящие платежные требования
     *
     * @return \common\models\sbbolxml\response\PayRequestType[]
     */
    public function getPayRequests()
    {
        return $this->payRequests;
    }

    /**
     * Sets a new payRequests
     *
     * Входящие платежные требования
     *
     * @param \common\models\sbbolxml\response\PayRequestType[] $payRequests
     * @return static
     */
    public function setPayRequests(array $payRequests)
    {
        $this->payRequests = $payRequests;
        return $this;
    }

    /**
     * Gets as changedDocs
     *
     * Измененные документы
     *
     * @return \common\models\sbbolxml\response\ResponseType\ChangedDocsAType
     */
    public function getChangedDocs()
    {
        return $this->changedDocs;
    }

    /**
     * Sets a new changedDocs
     *
     * Измененные документы
     *
     * @param \common\models\sbbolxml\response\ResponseType\ChangedDocsAType $changedDocs
     * @return static
     */
    public function setChangedDocs(\common\models\sbbolxml\response\ResponseType\ChangedDocsAType $changedDocs)
    {
        $this->changedDocs = $changedDocs;
        return $this;
    }

    /**
     * Adds as payDocRu
     *
     * Платежные поручения
     *
     * @return static
     * @param \common\models\sbbolxml\response\RZKPayDocRuType $payDocRu
     */
    public function addToPayDocsRu(\common\models\sbbolxml\response\RZKPayDocRuType $payDocRu)
    {
        $this->payDocsRu[] = $payDocRu;
        return $this;
    }

    /**
     * isset payDocsRu
     *
     * Платежные поручения
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPayDocsRu($index)
    {
        return isset($this->payDocsRu[$index]);
    }

    /**
     * unset payDocsRu
     *
     * Платежные поручения
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPayDocsRu($index)
    {
        unset($this->payDocsRu[$index]);
    }

    /**
     * Gets as payDocsRu
     *
     * Платежные поручения
     *
     * @return \common\models\sbbolxml\response\RZKPayDocRuType[]
     */
    public function getPayDocsRu()
    {
        return $this->payDocsRu;
    }

    /**
     * Sets a new payDocsRu
     *
     * Платежные поручения
     *
     * @param \common\models\sbbolxml\response\RZKPayDocRuType[] $payDocsRu
     * @return static
     */
    public function setPayDocsRu(array $payDocsRu)
    {
        $this->payDocsRu = $payDocsRu;
        return $this;
    }

    /**
     * Adds as payDocCur
     *
     * Поручения на перевод валюты
     *
     * @return static
     * @param \common\models\sbbolxml\response\RZKPayDocCurType $payDocCur
     */
    public function addToPayDocsCur(\common\models\sbbolxml\response\RZKPayDocCurType $payDocCur)
    {
        $this->payDocsCur[] = $payDocCur;
        return $this;
    }

    /**
     * isset payDocsCur
     *
     * Поручения на перевод валюты
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetPayDocsCur($index)
    {
        return isset($this->payDocsCur[$index]);
    }

    /**
     * unset payDocsCur
     *
     * Поручения на перевод валюты
     *
     * @param scalar $index
     * @return void
     */
    public function unsetPayDocsCur($index)
    {
        unset($this->payDocsCur[$index]);
    }

    /**
     * Gets as payDocsCur
     *
     * Поручения на перевод валюты
     *
     * @return \common\models\sbbolxml\response\RZKPayDocCurType[]
     */
    public function getPayDocsCur()
    {
        return $this->payDocsCur;
    }

    /**
     * Sets a new payDocsCur
     *
     * Поручения на перевод валюты
     *
     * @param \common\models\sbbolxml\response\RZKPayDocCurType[] $payDocsCur
     * @return static
     */
    public function setPayDocsCur(array $payDocsCur)
    {
        $this->payDocsCur = $payDocsCur;
        return $this;
    }

    /**
     * Adds as salaryDoc
     *
     * Электронные реестры (Зарплатные ведомости)
     *
     * @return static
     * @param \common\models\sbbolxml\response\RZKSalaryDocType $salaryDoc
     */
    public function addToSalaryDocs(\common\models\sbbolxml\response\RZKSalaryDocType $salaryDoc)
    {
        $this->salaryDocs[] = $salaryDoc;
        return $this;
    }

    /**
     * isset salaryDocs
     *
     * Электронные реестры (Зарплатные ведомости)
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSalaryDocs($index)
    {
        return isset($this->salaryDocs[$index]);
    }

    /**
     * unset salaryDocs
     *
     * Электронные реестры (Зарплатные ведомости)
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSalaryDocs($index)
    {
        unset($this->salaryDocs[$index]);
    }

    /**
     * Gets as salaryDocs
     *
     * Электронные реестры (Зарплатные ведомости)
     *
     * @return \common\models\sbbolxml\response\RZKSalaryDocType[]
     */
    public function getSalaryDocs()
    {
        return $this->salaryDocs;
    }

    /**
     * Sets a new salaryDocs
     *
     * Электронные реестры (Зарплатные ведомости)
     *
     * @param \common\models\sbbolxml\response\RZKSalaryDocType[] $salaryDocs
     * @return static
     */
    public function setSalaryDocs(array $salaryDocs)
    {
        $this->salaryDocs = $salaryDocs;
        return $this;
    }

    /**
     * Gets as listOfEmployees
     *
     * @return \common\models\sbbolxml\response\ListOfEmployeesType
     */
    public function getListOfEmployees()
    {
        return $this->listOfEmployees;
    }

    /**
     * Sets a new listOfEmployees
     *
     * @param \common\models\sbbolxml\response\ListOfEmployeesType $listOfEmployees
     * @return static
     */
    public function setListOfEmployees(\common\models\sbbolxml\response\ListOfEmployeesType $listOfEmployees)
    {
        $this->listOfEmployees = $listOfEmployees;
        return $this;
    }

    /**
     * Adds as docTypeConfig
     *
     * @return static
     * @param \common\models\sbbolxml\response\DocTypeConfigType $docTypeConfig
     */
    public function addToDocTypeConfigs(\common\models\sbbolxml\response\DocTypeConfigType $docTypeConfig)
    {
        $this->docTypeConfigs[] = $docTypeConfig;
        return $this;
    }

    /**
     * isset docTypeConfigs
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDocTypeConfigs($index)
    {
        return isset($this->docTypeConfigs[$index]);
    }

    /**
     * unset docTypeConfigs
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDocTypeConfigs($index)
    {
        unset($this->docTypeConfigs[$index]);
    }

    /**
     * Gets as docTypeConfigs
     *
     * @return \common\models\sbbolxml\response\DocTypeConfigType[]
     */
    public function getDocTypeConfigs()
    {
        return $this->docTypeConfigs;
    }

    /**
     * Sets a new docTypeConfigs
     *
     * @param \common\models\sbbolxml\response\DocTypeConfigType[] $docTypeConfigs
     * @return static
     */
    public function setDocTypeConfigs(array $docTypeConfigs)
    {
        $this->docTypeConfigs = $docTypeConfigs;
        return $this;
    }

    /**
     * Adds as intCtrlStatement181I
     *
     * Список ведомостей банковского контроля
     *
     * @return static
     * @param \common\models\sbbolxml\response\IntCtrlStatement181IType $intCtrlStatement181I
     */
    public function addToListIntCtrlStatement181I(\common\models\sbbolxml\response\IntCtrlStatement181IType $intCtrlStatement181I)
    {
        $this->listIntCtrlStatement181I[] = $intCtrlStatement181I;
        return $this;
    }

    /**
     * isset listIntCtrlStatement181I
     *
     * Список ведомостей банковского контроля
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetListIntCtrlStatement181I($index)
    {
        return isset($this->listIntCtrlStatement181I[$index]);
    }

    /**
     * unset listIntCtrlStatement181I
     *
     * Список ведомостей банковского контроля
     *
     * @param scalar $index
     * @return void
     */
    public function unsetListIntCtrlStatement181I($index)
    {
        unset($this->listIntCtrlStatement181I[$index]);
    }

    /**
     * Gets as listIntCtrlStatement181I
     *
     * Список ведомостей банковского контроля
     *
     * @return \common\models\sbbolxml\response\IntCtrlStatement181IType[]
     */
    public function getListIntCtrlStatement181I()
    {
        return $this->listIntCtrlStatement181I;
    }

    /**
     * Sets a new listIntCtrlStatement181I
     *
     * Список ведомостей банковского контроля
     *
     * @param \common\models\sbbolxml\response\IntCtrlStatement181IType[] $listIntCtrlStatement181I
     * @return static
     */
    public function setListIntCtrlStatement181I(array $listIntCtrlStatement181I)
    {
        $this->listIntCtrlStatement181I = $listIntCtrlStatement181I;
        return $this;
    }

    /**
     * Adds as dealPassCon138I
     *
     * Список паспортов сделки по контракту
     *
     * @return static
     * @param \common\models\sbbolxml\response\DealPassCon138IType $dealPassCon138I
     */
    public function addToListDealPassCon138I(\common\models\sbbolxml\response\DealPassCon138IType $dealPassCon138I)
    {
        $this->listDealPassCon138I[] = $dealPassCon138I;
        return $this;
    }

    /**
     * isset listDealPassCon138I
     *
     * Список паспортов сделки по контракту
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetListDealPassCon138I($index)
    {
        return isset($this->listDealPassCon138I[$index]);
    }

    /**
     * unset listDealPassCon138I
     *
     * Список паспортов сделки по контракту
     *
     * @param scalar $index
     * @return void
     */
    public function unsetListDealPassCon138I($index)
    {
        unset($this->listDealPassCon138I[$index]);
    }

    /**
     * Gets as listDealPassCon138I
     *
     * Список паспортов сделки по контракту
     *
     * @return \common\models\sbbolxml\response\DealPassCon138IType[]
     */
    public function getListDealPassCon138I()
    {
        return $this->listDealPassCon138I;
    }

    /**
     * Sets a new listDealPassCon138I
     *
     * Список паспортов сделки по контракту
     *
     * @param \common\models\sbbolxml\response\DealPassCon138IType[] $listDealPassCon138I
     * @return static
     */
    public function setListDealPassCon138I(array $listDealPassCon138I)
    {
        $this->listDealPassCon138I = $listDealPassCon138I;
        return $this;
    }

    /**
     * Adds as dealPassCred138I
     *
     * Список паспортов сделки по кредитному договору
     *
     * @return static
     * @param \common\models\sbbolxml\response\DealPassCred138IType $dealPassCred138I
     */
    public function addToListDealPassCred138I(\common\models\sbbolxml\response\DealPassCred138IType $dealPassCred138I)
    {
        $this->listDealPassCred138I[] = $dealPassCred138I;
        return $this;
    }

    /**
     * isset listDealPassCred138I
     *
     * Список паспортов сделки по кредитному договору
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetListDealPassCred138I($index)
    {
        return isset($this->listDealPassCred138I[$index]);
    }

    /**
     * unset listDealPassCred138I
     *
     * Список паспортов сделки по кредитному договору
     *
     * @param scalar $index
     * @return void
     */
    public function unsetListDealPassCred138I($index)
    {
        unset($this->listDealPassCred138I[$index]);
    }

    /**
     * Gets as listDealPassCred138I
     *
     * Список паспортов сделки по кредитному договору
     *
     * @return \common\models\sbbolxml\response\DealPassCred138IType[]
     */
    public function getListDealPassCred138I()
    {
        return $this->listDealPassCred138I;
    }

    /**
     * Sets a new listDealPassCred138I
     *
     * Список паспортов сделки по кредитному договору
     *
     * @param \common\models\sbbolxml\response\DealPassCred138IType[] $listDealPassCred138I
     * @return static
     */
    public function setListDealPassCred138I(array $listDealPassCred138I)
    {
        $this->listDealPassCred138I = $listDealPassCred138I;
        return $this;
    }

    /**
     * Gets as iSKRequest
     *
     * Запрос сведений о клиенте
     *
     * @return \common\models\sbbolxml\response\ISKRequestType
     */
    public function getISKRequest()
    {
        return $this->iSKRequest;
    }

    /**
     * Sets a new iSKRequest
     *
     * Запрос сведений о клиенте
     *
     * @param \common\models\sbbolxml\response\ISKRequestType $iSKRequest
     * @return static
     */
    public function setISKRequest(\common\models\sbbolxml\response\ISKRequestType $iSKRequest)
    {
        $this->iSKRequest = $iSKRequest;
        return $this;
    }

    /**
     * Adds as currencyNotice
     *
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @return static
     * @param \common\models\sbbolxml\response\CurrencyNoticeType $currencyNotice
     */
    public function addToCurrencyNotices(\common\models\sbbolxml\response\CurrencyNoticeType $currencyNotice)
    {
        $this->currencyNotices[] = $currencyNotice;
        return $this;
    }

    /**
     * isset currencyNotices
     *
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCurrencyNotices($index)
    {
        return isset($this->currencyNotices[$index]);
    }

    /**
     * unset currencyNotices
     *
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCurrencyNotices($index)
    {
        unset($this->currencyNotices[$index]);
    }

    /**
     * Gets as currencyNotices
     *
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @return \common\models\sbbolxml\response\CurrencyNoticeType[]
     */
    public function getCurrencyNotices()
    {
        return $this->currencyNotices;
    }

    /**
     * Sets a new currencyNotices
     *
     * Уведомления о поступлении денежных средств на транзитный валютный счет
     *
     * @param \common\models\sbbolxml\response\CurrencyNoticeType[] $currencyNotices
     * @return static
     */
    public function setCurrencyNotices(array $currencyNotices)
    {
        $this->currencyNotices = $currencyNotices;
        return $this;
    }

    /**
     * Gets as responsePart
     *
     * @return \common\models\sbbolxml\response\ResponsePartType
     */
    public function getResponsePart()
    {
        return $this->responsePart;
    }

    /**
     * Sets a new responsePart
     *
     * @param \common\models\sbbolxml\response\ResponsePartType $responsePart
     * @return static
     */
    public function setResponsePart(\common\models\sbbolxml\response\ResponsePartType $responsePart)
    {
        $this->responsePart = $responsePart;
        return $this;
    }

    /**
     * Adds as chatWithBankMsg
     *
     * Чаты с банком
     *
     * @return static
     * @param \common\models\sbbolxml\response\ChatWithBankMsgType $chatWithBankMsg
     */
    public function addToChatWithBankMsgs(\common\models\sbbolxml\response\ChatWithBankMsgType $chatWithBankMsg)
    {
        $this->chatWithBankMsgs[] = $chatWithBankMsg;
        return $this;
    }

    /**
     * isset chatWithBankMsgs
     *
     * Чаты с банком
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetChatWithBankMsgs($index)
    {
        return isset($this->chatWithBankMsgs[$index]);
    }

    /**
     * unset chatWithBankMsgs
     *
     * Чаты с банком
     *
     * @param scalar $index
     * @return void
     */
    public function unsetChatWithBankMsgs($index)
    {
        unset($this->chatWithBankMsgs[$index]);
    }

    /**
     * Gets as chatWithBankMsgs
     *
     * Чаты с банком
     *
     * @return \common\models\sbbolxml\response\ChatWithBankMsgType[]
     */
    public function getChatWithBankMsgs()
    {
        return $this->chatWithBankMsgs;
    }

    /**
     * Sets a new chatWithBankMsgs
     *
     * Чаты с банком
     *
     * @param \common\models\sbbolxml\response\ChatWithBankMsgType[] $chatWithBankMsgs
     * @return static
     */
    public function setChatWithBankMsgs(array $chatWithBankMsgs)
    {
        $this->chatWithBankMsgs = $chatWithBankMsgs;
        return $this;
    }

    /**
     * Adds as exRateDetails
     *
     * Справочник курсов валют
     *
     * @return static
     * @param \common\models\sbbolxml\response\ExRateDetailType $exRateDetails
     */
    public function addToCurrCourseEntry(\common\models\sbbolxml\response\ExRateDetailType $exRateDetails)
    {
        $this->currCourseEntry[] = $exRateDetails;
        return $this;
    }

    /**
     * isset currCourseEntry
     *
     * Справочник курсов валют
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCurrCourseEntry($index)
    {
        return isset($this->currCourseEntry[$index]);
    }

    /**
     * unset currCourseEntry
     *
     * Справочник курсов валют
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCurrCourseEntry($index)
    {
        unset($this->currCourseEntry[$index]);
    }

    /**
     * Gets as currCourseEntry
     *
     * Справочник курсов валют
     *
     * @return \common\models\sbbolxml\response\ExRateDetailType[]
     */
    public function getCurrCourseEntry()
    {
        return $this->currCourseEntry;
    }

    /**
     * Sets a new currCourseEntry
     *
     * Справочник курсов валют
     *
     * @param \common\models\sbbolxml\response\ExRateDetailType[] $currCourseEntry
     * @return static
     */
    public function setCurrCourseEntry(array $currCourseEntry)
    {
        $this->currCourseEntry = $currCourseEntry;
        return $this;
    }

    /**
     * Adds as essence
     *
     * @return static
     * @param \common\models\sbbolxml\response\EssenceType $essence
     */
    public function addToLinksToBigFiles(\common\models\sbbolxml\response\EssenceType $essence)
    {
        $this->linksToBigFiles[] = $essence;
        return $this;
    }

    /**
     * isset linksToBigFiles
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetLinksToBigFiles($index)
    {
        return isset($this->linksToBigFiles[$index]);
    }

    /**
     * unset linksToBigFiles
     *
     * @param scalar $index
     * @return void
     */
    public function unsetLinksToBigFiles($index)
    {
        unset($this->linksToBigFiles[$index]);
    }

    /**
     * Gets as linksToBigFiles
     *
     * @return \common\models\sbbolxml\response\EssenceType[]
     */
    public function getLinksToBigFiles()
    {
        return $this->linksToBigFiles;
    }

    /**
     * Sets a new linksToBigFiles
     *
     * @param \common\models\sbbolxml\response\EssenceType[] $linksToBigFiles
     * @return static
     */
    public function setLinksToBigFiles(array $linksToBigFiles)
    {
        $this->linksToBigFiles = $linksToBigFiles;
        return $this;
    }

    /**
     * Adds as bigFilesStatus
     *
     * @return static
     * @param \common\models\sbbolxml\response\BigFilesStatusType $bigFilesStatus
     */
    public function addToBigFilesStatus(\common\models\sbbolxml\response\BigFilesStatusType $bigFilesStatus)
    {
        $this->bigFilesStatus[] = $bigFilesStatus;
        return $this;
    }

    /**
     * isset bigFilesStatus
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBigFilesStatus($index)
    {
        return isset($this->bigFilesStatus[$index]);
    }

    /**
     * unset bigFilesStatus
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBigFilesStatus($index)
    {
        unset($this->bigFilesStatus[$index]);
    }

    /**
     * Gets as bigFilesStatus
     *
     * @return \common\models\sbbolxml\response\BigFilesStatusType[]
     */
    public function getBigFilesStatus()
    {
        return $this->bigFilesStatus;
    }

    /**
     * Sets a new bigFilesStatus
     *
     * @param \common\models\sbbolxml\response\BigFilesStatusType[] $bigFilesStatus
     * @return static
     */
    public function setBigFilesStatus(array $bigFilesStatus)
    {
        $this->bigFilesStatus = $bigFilesStatus;
        return $this;
    }

    /**
     * Adds as dealConf
     *
     * Сообщения о подтверждении сделок
     *
     * @return static
     * @param \common\models\sbbolxml\response\DealConfType $dealConf
     */
    public function addToDealConfs(\common\models\sbbolxml\response\DealConfType $dealConf)
    {
        $this->dealConfs[] = $dealConf;
        return $this;
    }

    /**
     * isset dealConfs
     *
     * Сообщения о подтверждении сделок
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetDealConfs($index)
    {
        return isset($this->dealConfs[$index]);
    }

    /**
     * unset dealConfs
     *
     * Сообщения о подтверждении сделок
     *
     * @param scalar $index
     * @return void
     */
    public function unsetDealConfs($index)
    {
        unset($this->dealConfs[$index]);
    }

    /**
     * Gets as dealConfs
     *
     * Сообщения о подтверждении сделок
     *
     * @return \common\models\sbbolxml\response\DealConfType[]
     */
    public function getDealConfs()
    {
        return $this->dealConfs;
    }

    /**
     * Sets a new dealConfs
     *
     * Сообщения о подтверждении сделок
     *
     * @param \common\models\sbbolxml\response\DealConfType[] $dealConfs
     * @return static
     */
    public function setDealConfs(array $dealConfs)
    {
        $this->dealConfs = $dealConfs;
        return $this;
    }

    /**
     * Gets as smsTimeouts
     *
     * @return \common\models\sbbolxml\response\SmsTimeoutsType
     */
    public function getSmsTimeouts()
    {
        return $this->smsTimeouts;
    }

    /**
     * Sets a new smsTimeouts
     *
     * @param \common\models\sbbolxml\response\SmsTimeoutsType $smsTimeouts
     * @return static
     */
    public function setSmsTimeouts(\common\models\sbbolxml\response\SmsTimeoutsType $smsTimeouts)
    {
        $this->smsTimeouts = $smsTimeouts;
        return $this;
    }

    /**
     * Gets as incomingRoles
     *
     * Роли
     *
     * @return \common\models\sbbolxml\response\IncomingRolesType
     */
    public function getIncomingRoles()
    {
        return $this->incomingRoles;
    }

    /**
     * Sets a new incomingRoles
     *
     * Роли
     *
     * @param \common\models\sbbolxml\response\IncomingRolesType $incomingRoles
     * @return static
     */
    public function setIncomingRoles(\common\models\sbbolxml\response\IncomingRolesType $incomingRoles)
    {
        $this->incomingRoles = $incomingRoles;
        return $this;
    }

    /**
     * Gets as userRoles
     *
     * Роли пользователей
     *
     * @return \common\models\sbbolxml\response\UserRolesType
     */
    public function getUserRoles()
    {
        return $this->userRoles;
    }

    /**
     * Sets a new userRoles
     *
     * Роли пользователей
     *
     * @param \common\models\sbbolxml\response\UserRolesType $userRoles
     * @return static
     */
    public function setUserRoles(\common\models\sbbolxml\response\UserRolesType $userRoles)
    {
        $this->userRoles = $userRoles;
        return $this;
    }

    /**
     * Gets as offers
     *
     * Предложения
     *
     * @return \common\models\sbbolxml\response\OffersType
     */
    public function getOffers()
    {
        return $this->offers;
    }

    /**
     * Sets a new offers
     *
     * Предложения
     *
     * @param \common\models\sbbolxml\response\OffersType $offers
     * @return static
     */
    public function setOffers(\common\models\sbbolxml\response\OffersType $offers)
    {
        $this->offers = $offers;
        return $this;
    }

    /**
     * Gets as admPayTemplates
     *
     * Шаблоны внесения средств
     *
     * @return \common\models\sbbolxml\response\AdmPaymentTemplatesType
     */
    public function getAdmPayTemplates()
    {
        return $this->admPayTemplates;
    }

    /**
     * Sets a new admPayTemplates
     *
     * Шаблоны внесения средств
     *
     * @param \common\models\sbbolxml\response\AdmPaymentTemplatesType $admPayTemplates
     * @return static
     */
    public function setAdmPayTemplates(\common\models\sbbolxml\response\AdmPaymentTemplatesType $admPayTemplates)
    {
        $this->admPayTemplates = $admPayTemplates;
        return $this;
    }

    /**
     * Gets as admCashiers
     *
     * Список вносителей средств
     *
     * @return \common\models\sbbolxml\response\AdmCashiersType
     */
    public function getAdmCashiers()
    {
        return $this->admCashiers;
    }

    /**
     * Sets a new admCashiers
     *
     * Список вносителей средств
     *
     * @param \common\models\sbbolxml\response\AdmCashiersType $admCashiers
     * @return static
     */
    public function setAdmCashiers(\common\models\sbbolxml\response\AdmCashiersType $admCashiers)
    {
        $this->admCashiers = $admCashiers;
        return $this;
    }

    /**
     * Gets as admOperations
     *
     * Операции внесения средств
     *
     * @return \common\models\sbbolxml\response\AdmOperationsType
     */
    public function getAdmOperations()
    {
        return $this->admOperations;
    }

    /**
     * Sets a new admOperations
     *
     * Операции внесения средств
     *
     * @param \common\models\sbbolxml\response\AdmOperationsType $admOperations
     * @return static
     */
    public function setAdmOperations(\common\models\sbbolxml\response\AdmOperationsType $admOperations)
    {
        $this->admOperations = $admOperations;
        return $this;
    }

    /**
     * Gets as admCashierLogin
     *
     * Передача логина вносителя средств в УС
     *
     * @return \common\models\sbbolxml\response\AdmCashierLoginType
     */
    public function getAdmCashierLogin()
    {
        return $this->admCashierLogin;
    }

    /**
     * Sets a new admCashierLogin
     *
     * Передача логина вносителя средств в УС
     *
     * @param \common\models\sbbolxml\response\AdmCashierLoginType $admCashierLogin
     * @return static
     */
    public function setAdmCashierLogin(\common\models\sbbolxml\response\AdmCashierLoginType $admCashierLogin)
    {
        $this->admCashierLogin = $admCashierLogin;
        return $this;
    }

    /**
     * Gets as cardDeposits
     *
     * Список карточек депозита
     *
     * @return \common\models\sbbolxml\response\CardDepositsType
     */
    public function getCardDeposits()
    {
        return $this->cardDeposits;
    }

    /**
     * Sets a new cardDeposits
     *
     * Список карточек депозита
     *
     * @param \common\models\sbbolxml\response\CardDepositsType $cardDeposits
     * @return static
     */
    public function setCardDeposits(\common\models\sbbolxml\response\CardDepositsType $cardDeposits)
    {
        $this->cardDeposits = $cardDeposits;
        return $this;
    }

    /**
     * Gets as cardPermBalanceList
     *
     * Список договоров НСО
     *
     * @return \common\models\sbbolxml\response\CardPermBalanceListType
     */
    public function getCardPermBalanceList()
    {
        return $this->cardPermBalanceList;
    }

    /**
     * Sets a new cardPermBalanceList
     *
     * Список договоров НСО
     *
     * @param \common\models\sbbolxml\response\CardPermBalanceListType $cardPermBalanceList
     * @return static
     */
    public function setCardPermBalanceList(\common\models\sbbolxml\response\CardPermBalanceListType $cardPermBalanceList)
    {
        $this->cardPermBalanceList = $cardPermBalanceList;
        return $this;
    }

    /**
     * Gets as feesRegistries
     *
     * Реестры платежей
     *
     * @return \common\models\sbbolxml\response\FeesRegistriesType
     */
    public function getFeesRegistries()
    {
        return $this->feesRegistries;
    }

    /**
     * Sets a new feesRegistries
     *
     * Реестры платежей
     *
     * @param \common\models\sbbolxml\response\FeesRegistriesType $feesRegistries
     * @return static
     */
    public function setFeesRegistries(\common\models\sbbolxml\response\FeesRegistriesType $feesRegistries)
    {
        $this->feesRegistries = $feesRegistries;
        return $this;
    }

    /**
     * Gets as downloadLinks
     *
     * Ссылки на скачивание
     *
     * @return \common\models\sbbolxml\response\DownloadLinksType
     */
    public function getDownloadLinks()
    {
        return $this->downloadLinks;
    }

    /**
     * Sets a new downloadLinks
     *
     * Ссылки на скачивание
     *
     * @param \common\models\sbbolxml\response\DownloadLinksType $downloadLinks
     * @return static
     */
    public function setDownloadLinks(\common\models\sbbolxml\response\DownloadLinksType $downloadLinks)
    {
        $this->downloadLinks = $downloadLinks;
        return $this;
    }

    /**
     * Gets as uploadLinks
     *
     * Ссылки на загрузку файлов
     *
     * @return \common\models\sbbolxml\response\UploadLinksType
     */
    public function getUploadLinks()
    {
        return $this->uploadLinks;
    }

    /**
     * Sets a new uploadLinks
     *
     * Ссылки на загрузку файлов
     *
     * @param \common\models\sbbolxml\response\UploadLinksType $uploadLinks
     * @return static
     */
    public function setUploadLinks(\common\models\sbbolxml\response\UploadLinksType $uploadLinks)
    {
        $this->uploadLinks = $uploadLinks;
        return $this;
    }

    /**
     * Gets as intCtrlStatementXML181I
     *
     * Информация о ведомости банковского контроля
     *
     * @return \common\models\sbbolxml\response\IntCtrlStatementXML181IType
     */
    public function getIntCtrlStatementXML181I()
    {
        return $this->intCtrlStatementXML181I;
    }

    /**
     * Sets a new intCtrlStatementXML181I
     *
     * Информация о ведомости банковского контроля
     *
     * @param \common\models\sbbolxml\response\IntCtrlStatementXML181IType $intCtrlStatementXML181I
     * @return static
     */
    public function setIntCtrlStatementXML181I(\common\models\sbbolxml\response\IntCtrlStatementXML181IType $intCtrlStatementXML181I)
    {
        $this->intCtrlStatementXML181I = $intCtrlStatementXML181I;
        return $this;
    }

    /**
     * Adds as genericLetterFromBank
     *
     * Письма свободного формата из банка
     *
     * @return static
     * @param \common\models\sbbolxml\response\GenericLetterFromBankType $genericLetterFromBank
     */
    public function addToGenericLettersFromBank(\common\models\sbbolxml\response\GenericLetterFromBankType $genericLetterFromBank)
    {
        $this->genericLettersFromBank[] = $genericLetterFromBank;
        return $this;
    }

    /**
     * isset genericLettersFromBank
     *
     * Письма свободного формата из банка
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetGenericLettersFromBank($index)
    {
        return isset($this->genericLettersFromBank[$index]);
    }

    /**
     * unset genericLettersFromBank
     *
     * Письма свободного формата из банка
     *
     * @param scalar $index
     * @return void
     */
    public function unsetGenericLettersFromBank($index)
    {
        unset($this->genericLettersFromBank[$index]);
    }

    /**
     * Gets as genericLettersFromBank
     *
     * Письма свободного формата из банка
     *
     * @return \common\models\sbbolxml\response\GenericLetterFromBankType[]
     */
    public function getGenericLettersFromBank()
    {
        return $this->genericLettersFromBank;
    }

    /**
     * Sets a new genericLettersFromBank
     *
     * Письма свободного формата из банка
     *
     * @param \common\models\sbbolxml\response\GenericLetterFromBankType[] $genericLettersFromBank
     * @return static
     */
    public function setGenericLettersFromBank(array $genericLettersFromBank)
    {
        $this->genericLettersFromBank = $genericLettersFromBank;
        return $this;
    }

    /**
     * Gets as digest
     *
     * Дайджест документа
     *
     * @return string
     */
    public function getDigest()
    {
        return $this->digest;
    }

    /**
     * Sets a new digest
     *
     * Дайджест документа
     *
     * @param string $digest
     * @return static
     */
    public function setDigest($digest)
    {
        $this->digest = $digest;
        return $this;
    }


}

