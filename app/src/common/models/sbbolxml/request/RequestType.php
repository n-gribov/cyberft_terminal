<?php

namespace common\models\sbbolxml\request;

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
     * Идентификатор организации в ДБО (может быть использован сторонней системой для
     *  дополнительной идентификации)
     *
     * @property string $orgId
     */
    private $orgId = null;

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
     *  Если система получатель СББОЛ, то SBBOL_DBO
     *
     * @property string $receiver
     */
    private $receiver = null;

    /**
     * Ключ системы-отправителя
     *
     * @property string $senderKey
     */
    private $senderKey = null;

    /**
     * Флаг сообщения для банка
     *
     * @property boolean $bankOnly
     */
    private $bankOnly = null;

    /**
     * Версия протокола
     *
     * @property string $protocolVersion
     */
    private $protocolVersion = null;

    /**
     * Запрос информации о результатах обработки документов (подпись будем проверять
     *  в соответствии с порядком
     *  в строк е)
     *
     * @property \common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType[] $docIds
     */
    private $docIds = null;

    /**
     * Платёжное поручение рублёвое
     *
     * @property \common\models\sbbolxml\request\PayDocRuType $payDocRu
     */
    private $payDocRu = null;

    /**
     * @property string $holdingInfoRequest
     */
    private $holdingInfoRequest = null;

    /**
     * Квитанция о получении документа на стороне УС
     *
     * @property \common\models\sbbolxml\request\TicketClType $ticket
     */
    private $ticket = null;

    /**
     * Запрос о получении данных, подготовленных для УС ("ночных выписок", выписок,
     *  тикетов после отказа в РС, новостей)
     *
     * @property \common\models\sbbolxml\request\RequestType\IncomingAType $incoming
     */
    private $incoming = null;

    /**
     * Запрос криптографической информации (криптопрофилей, сертификатов)
     *
     * @property \common\models\sbbolxml\request\RequestType\CryptoIncomingAType $cryptoIncoming
     */
    private $cryptoIncoming = null;

    /**
     * @property \common\models\sbbolxml\request\ActivateCertType $activateCert
     */
    private $activateCert = null;

    /**
     * Запрос на получение информации о движении ден. средств
     *
     * @property \common\models\sbbolxml\request\StmtReqType $stmtReq
     */
    private $stmtReq = null;

    /**
     * Запрос СМС-пароля
     *
     * @property \common\models\sbbolxml\request\RequestType\PayDocRuGetSMSAType $payDocRuGetSMS
     */
    private $payDocRuGetSMS = null;

    /**
     * Отправка платежки с паролем
     *
     * @property \common\models\sbbolxml\request\RequestType\PayDocRuSMSAType $payDocRuSMS
     */
    private $payDocRuSMS = null;

    /**
     * Зарплатная ведомость
     *
     * @property \common\models\sbbolxml\request\SalaryDocType $salaryDoc
     */
    private $salaryDoc = null;

    /**
     * Заявление об акцепте/отказ от акцепта
     *
     * @property \common\models\sbbolxml\request\AcceptType $accept
     */
    private $accept = null;

    /**
     * Письмо в банк
     *
     * @property \common\models\sbbolxml\request\LetterInBankType $letterInBank
     */
    private $letterInBank = null;

    /**
     * Справка о валютных операциях 138
     *
     * @property \common\models\sbbolxml\request\CurrDealCertificate138IType $currDealCertificate138I
     */
    private $currDealCertificate138I = null;

    /**
     * Справка о подтверждающих документах 138И
     *
     * @property \common\models\sbbolxml\request\ConfDocCertificate138IType $confDocCertificate138I
     */
    private $confDocCertificate138I = null;

    /**
     * Справка о подтверждающих документах 181И
     *
     * @property \common\models\sbbolxml\request\ConfDocCertificate181IType $confDocCertificate181I
     */
    private $confDocCertificate181I = null;

    /**
     * Заявление о переоформлении ПС
     *
     * @property \common\models\sbbolxml\request\IcsRestruct181IType $icsRestruct181I
     */
    private $icsRestruct181I = null;

    /**
     * Прикрепление счетов дочерней организации к головной компании холдинга
     *
     * @property \common\models\sbbolxml\request\DzoAccAttachType $dzoAccAttach
     */
    private $dzoAccAttach = null;

    /**
     * Ведомость банковского контроля
     *
     * @property \common\models\sbbolxml\request\DealPassICSType $dealPassICS
     */
    private $dealPassICS = null;

    /**
     * Паспорт сделки по контракту 138И
     *
     * @property \common\models\sbbolxml\request\DealPassCon138IType $dealPassCon138I
     */
    private $dealPassCon138I = null;

    /**
     * Паспорт сделки по кредитному договору 138И
     *
     * @property \common\models\sbbolxml\request\DealPassCred138IType $dealPassCred138I
     */
    private $dealPassCred138I = null;

    /**
     * Заявление о переоформлении паспорта сделки 138И
     *
     * @property \common\models\sbbolxml\request\DealPassRestructType $dealPassRestruct
     */
    private $dealPassRestruct = null;

    /**
     * Заявление о закрытии паспорта сделки 138И
     *
     * @property \common\models\sbbolxml\request\DealPassCloseType $dealPassClose
     */
    private $dealPassClose = null;

    /**
     * Запрос на выпуск нового сертификата
     *
     * @property \common\models\sbbolxml\request\CertifRequestQualifiedType $certifRequestQualified
     */
    private $certifRequestQualified = null;

    /**
     * Запрос на выпуск нового сертификата
     *
     * @property \common\models\sbbolxml\request\CertifRequestType $certifRequest
     */
    private $certifRequest = null;

    /**
     * Запрос на отзыв сертификата
     *
     * @property \common\models\sbbolxml\request\RevocationCertifRequestType $revocationCertifRequest
     */
    private $revocationCertifRequest = null;

    /**
     * Поручение на перевод валюты
     *
     * @property \common\models\sbbolxml\request\PayDocCurType $payDocCur
     */
    private $payDocCur = null;

    /**
     * Поручение на покупку валюты
     *
     * @property \common\models\sbbolxml\request\CurrBuyType $currBuy
     */
    private $currBuy = null;

    /**
     * Поручение на продажу валюты
     *
     * @property \common\models\sbbolxml\request\CurrSellType $currSell
     */
    private $currSell = null;

    /**
     * Распоряжение об осуществлении обязательной продажи
     *
     * @property \common\models\sbbolxml\request\MandatorySaleType $mandatorySale
     */
    private $mandatorySale = null;

    /**
     * Реестр на пополнение средств по корпоративным картам
     *
     * @property \common\models\sbbolxml\request\RegOfCorpCardsType $regOfCorpCards
     */
    private $regOfCorpCards = null;

    /**
     * Заявка на зарплатный договор
     *
     * @property \common\models\sbbolxml\request\ApplForContractType $applForContract
     */
    private $applForContract = null;

    /**
     * Реестр на открытие счетов и выпуск карт
     *
     * @property \common\models\sbbolxml\request\RegOfIssCardsType $regOfIssCards
     */
    private $regOfIssCards = null;

    /**
     * Реестр на увольнение сотрудников
     *
     * @property \common\models\sbbolxml\request\RegOfFiredEmployeesType $regOfFiredEmployees
     */
    private $regOfFiredEmployees = null;

    /**
     * Репликация справочников
     *
     * @property \common\models\sbbolxml\request\DictType[] $dict
     */
    private $dict = array(
        
    );

    /**
     * Запрос репликации справочника СБК
     *
     * @property \common\models\sbbolxml\request\RzkDictUpdateType[] $rzkDictUpdate
     */
    private $rzkDictUpdate = array(
        
    );

    /**
     * Запрос персональных данных
     *
     * @property \common\models\sbbolxml\request\PersonalInfoType $personalInfo
     */
    private $personalInfo = null;

    /**
     * Запрос данных о типах документов
     *
     * @property \common\models\sbbolxml\request\DocTypeConfigInfoType $docTypeConfigInfo
     */
    private $docTypeConfigInfo = null;

    /**
     * Запрос обновлений для Offline-клиента
     *
     * @property \common\models\sbbolxml\request\UpdateRequestType $clientAppUpdateRequest
     */
    private $clientAppUpdateRequest = null;

    /**
     * Платежное требование и Инкассовое поручение
     *
     * @property \common\models\sbbolxml\request\PayRequestType $payRequest
     */
    private $payRequest = null;

    /**
     * Распоряжение на перевод кредитных средств
     *
     * @property \common\models\sbbolxml\request\CreditOrderType $creditOrder
     */
    private $creditOrder = null;

    /**
     * Запрос на отзыв документов
     *
     * @property \common\models\sbbolxml\request\RevocationRequestType $revocationRequest
     */
    private $revocationRequest = null;

    /**
     * Запрос информации об остатках по счетам
     *
     * @property \common\models\sbbolxml\request\OverdraftRequestType $remainRequest
     */
    private $remainRequest = null;

    /**
     * Запрос обновления прошивки токена
     *
     * @property \common\models\sbbolxml\request\FirmwareUpdateRequestType $firmwareUpdateRequest
     */
    private $firmwareUpdateRequest = null;

    /**
     * ЭД на добавление записи с подтвержденным контрагентом
     *
     * @property \common\models\sbbolxml\request\CorrAddType $corrAdd
     */
    private $corrAdd = null;

    /**
     * Добавление подтверждающего документа
     *
     * @property \common\models\sbbolxml\request\SubstDocAddType $substDocAdd
     */
    private $substDocAdd = null;

    /**
     * ЭД удаление записи подтвержденного контрагента
     *
     * @property \common\models\sbbolxml\request\CorrDelType $corrDel
     */
    private $corrDel = null;

    /**
     * Запрос на генерацию SMS с кодом для подписи
     *
     * @property \common\models\sbbolxml\request\SMSSignReqParamsType $genSMSSign
     */
    private $genSMSSign = null;

    /**
     * Получение SMS с кодом для подписи
     *
     * @property \common\models\sbbolxml\request\VerifySMSSignType $verifySMSSign
     */
    private $verifySMSSign = null;

    /**
     * ЭД на добавление записи бенефициара
     *
     * @property \common\models\sbbolxml\request\BenefAddType $benefAdd
     */
    private $benefAdd = null;

    /**
     * ЭД удаление записи бенефициара
     *
     * @property \common\models\sbbolxml\request\BenefDelType $benefDel
     */
    private $benefDel = null;

    /**
     * Передача признака «Использовать подтвержденный справочник контрагентов»
     *
     * @property \common\models\sbbolxml\request\EnableUseSignCorrDictType $enableUseSignCorrDict
     */
    private $enableUseSignCorrDict = null;

    /**
     * Заявление на выпуск КК
     *
     * @property \common\models\sbbolxml\request\CorpCardExtIssueRequestType $corpCardExtIssueRequest
     */
    private $corpCardExtIssueRequest = null;

    /**
     * Запрос на получение (обновление) списка сотрудников
     *
     * @property \common\models\sbbolxml\request\UpdateListOfEmployeesType $updateListOfEmployees
     */
    private $updateListOfEmployees = null;

    /**
     * Запрос ТК к СББОЛ
     *
     * @property \common\models\sbbolxml\request\ExchangeMessagesWithBankType $exchangeMessagesWithBank
     */
    private $exchangeMessagesWithBank = null;

    /**
     * Запрос информации ВК
     *
     * @property \common\models\sbbolxml\request\CurrControlInfoRequestType $currControlInfoRequest
     */
    private $currControlInfoRequest = null;

    /**
     * Информационные сведения юридического лица
     *
     * @property \common\models\sbbolxml\request\ISKForULType $iskForUL
     */
    private $iskForUL = null;

    /**
     * Информационные сведения индивидуального предпринимателя
     *
     * @property \common\models\sbbolxml\request\ISKForIPType $iskForIP
     */
    private $iskForIP = null;

    /**
     * Проверка наличия запроса на получение сведений об организации
     *
     * @property \common\models\sbbolxml\request\IncomingRequestISKType $incomingRequestISK
     */
    private $incomingRequestISK = null;

    /**
     * Паспорт сделки из другого банка
     *
     * @property \common\models\sbbolxml\request\DealPassFromAnotherBankType $dealPassFromAnotherBank
     */
    private $dealPassFromAnotherBank = null;

    /**
     * Сообщение чата с банком (от клиента)
     *
     * @property \common\models\sbbolxml\request\ChatWithBankMsgType $chatWithBank
     */
    private $chatWithBank = null;

    /**
     * Запрос сообщений чата с банком (из банка)
     *
     * @property \common\models\sbbolxml\request\RequestChatFromBankMsgsType $requestChatFromBankMsgs
     */
    private $requestChatFromBankMsgs = null;

    /**
     * Сообщение чата с банком (от клиента)
     *
     * @property \common\models\sbbolxml\request\ChatWithBankMsgStatusType $chatWithBankMsgStatus
     */
    private $chatWithBankMsgStatus = null;

    /**
     * Запрос справки
     *
     * @property \common\models\sbbolxml\request\InquiryOrderType $inquiryOrder
     */
    private $inquiryOrder = null;

    /**
     * Запрос сформированных выписок по счетам
     *
     * @property \common\models\sbbolxml\request\AccountStatementType $accountStatement
     */
    private $accountStatement = null;

    /**
     * Запрос рублевых платежных поручений
     *
     * @property \common\models\sbbolxml\request\RzkPayDocsRuType $rzkPayDocsRu
     */
    private $rzkPayDocsRu = null;

    /**
     * Запрос справочника валют
     *
     * @property \common\models\sbbolxml\request\CurrCourseEntryType $currCourseEntry
     */
    private $currCourseEntry = null;

    /**
     * Запрос списка документов по госконтракту
     *
     * @property \common\models\sbbolxml\request\DictType[] $gozDocUpdate
     */
    private $gozDocUpdate = array(
        
    );

    /**
     * Запрос репликации справочников самоинкассации
     *
     * @property \common\models\sbbolxml\request\DictType[] $admDictUpdate
     */
    private $admDictUpdate = array(
        
    );

    /**
     * Досылаемые документы
     *
     * @property \common\models\sbbolxml\request\SupplyDocType $supplyDoc
     */
    private $supplyDoc = null;

    /**
     * Запрос ссылок на загрузку в систему БФ
     *
     * @property \common\models\sbbolxml\request\BigFilesUploadLinksRequestType\BigFilesUploadLinkRequestAType[] $bigFilesUploadLinksRequest
     */
    private $bigFilesUploadLinksRequest = null;

    /**
     * Запрос статуса загруженных файлов
     *
     * @property \common\models\sbbolxml\request\BigFilesStatusRequestType $bigFilesStatusRequest
     */
    private $bigFilesStatusRequest = null;

    /**
     * Запрос сообщений о подтверждении сделок
     *
     * @property \common\models\sbbolxml\request\IncomingDealConfType $incomingDealConf
     */
    private $incomingDealConf = null;

    /**
     * Ответ о подтверждении сделки
     *
     * @property \common\models\sbbolxml\request\DealAnsType $dealAns
     */
    private $dealAns = null;

    /**
     * Запрос на получение таймаутов действия смс-пароля
     *
     * @property string $smsTimeouts
     */
    private $smsTimeouts = null;

    /**
     * Заявка на оплату документа таможни
     *
     * @property \common\models\sbbolxml\request\PayCustDocType $payCustDoc
     */
    private $payCustDoc = null;

    /**
     * Добавление контракта ГОЗ
     *
     * @property \common\models\sbbolxml\request\ContractAddType $contractAdd
     */
    private $contractAdd = null;

    /**
     * Добавление акта ГОЗ
     *
     * @property \common\models\sbbolxml\request\ActAddType $actAdd
     */
    private $actAdd = null;

    /**
     * Запрос ролей
     *
     * @property string[] $incomingRoles
     */
    private $incomingRoles = null;

    /**
     * Запрос ролей по пользователям
     *
     * @property string[] $incomingUserRoles
     */
    private $incomingUserRoles = null;

    /**
     * Запрос предложений
     *
     * @property string[] $incomingOffers
     */
    private $incomingOffers = null;

    /**
     * Отклики на персональное предложение
     *
     * @property \common\models\sbbolxml\request\OfferResponseType[] $offerResponses
     */
    private $offerResponses = null;

    /**
     * Заявки в CRM
     *
     * @property \common\models\sbbolxml\request\CrmType[] $crms
     */
    private $crms = null;

    /**
     * Заявки на обратный звонок
     *
     * @property \common\models\sbbolxml\request\CallBackType[] $callBacks
     */
    private $callBacks = null;

    /**
     * Шаблоны внесения средств
     *
     * @property \common\models\sbbolxml\request\AdmPaymentTemplateType $admPayTemplate
     */
    private $admPayTemplate = null;

    /**
     * Блокировка\разблокировка Шаблона внесения средств
     *
     * @property \common\models\sbbolxml\request\AdmPaymentTemplateBlockType $admPayTemplateBlock
     */
    private $admPayTemplateBlock = null;

    /**
     * Запрос на удаление сущности «Вноситель средств»
     *
     * @property \common\models\sbbolxml\request\RequestType\AdmCashierDelAType $admCashierDel
     */
    private $admCashierDel = null;

    /**
     * Удаление документа вносители средств
     *
     * @property \common\models\sbbolxml\request\AdmPaymentTemplateDelType $admPayTemplateDel
     */
    private $admPayTemplateDel = null;

    /**
     * Вносители средств
     *
     * @property \common\models\sbbolxml\request\AdmCashierType $admCashierAdd
     */
    private $admCashierAdd = null;

    /**
     * Запрос списка документов вносители средств
     *
     * @property \common\models\sbbolxml\request\AdmPaymentTemplateListType $admPayTemplateList
     */
    private $admPayTemplateList = null;

    /**
     * Запрос списка документов вносители средств
     *
     * @property \common\models\sbbolxml\request\AdmCashierListType $admCashierList
     */
    private $admCashierList = null;

    /**
     * Запрос списка операций внесения средств
     *
     * @property \common\models\sbbolxml\request\AdmOperationListType $admOperationList
     */
    private $admOperationList = null;

    /**
     * Запрос на генерацию пароля «Вносителю средств»
     *
     * @property \common\models\sbbolxml\request\AdmGenSMSPassType $admGenSMSPass
     */
    private $admGenSMSPass = null;

    /**
     * Запрос логина «Вносителя средств»
     *
     * @property \common\models\sbbolxml\request\AdmCashierGetLoginType $admCashierGetLogin
     */
    private $admCashierGetLogin = null;

    /**
     * Письмо с данными аудита
     *
     * @property \common\models\sbbolxml\request\AuditLetterType $auditLetter
     */
    private $auditLetter = null;

    /**
     * Заявление на отзыв вклада (депозита)
     *
     * @property \common\models\sbbolxml\request\EarlyRecallDataType $earlyRecall
     */
    private $earlyRecall = null;

    /**
     * Заявление на изменение реквизитов расчетного счета
     *
     * @property \common\models\sbbolxml\request\ChangeAccDetailsDataType $changeAccDetails
     */
    private $changeAccDetails = null;

    /**
     * Запрос списка карточек депозитов организации клиента
     *
     * @property \common\models\sbbolxml\request\CardDepositsType $cardDeposits
     */
    private $cardDeposits = null;

    /**
     * Запрос отправки заявления на пролонгацию вклада (депозита)
     *
     * @property \common\models\sbbolxml\request\AppForDepositType $appForDepositNew
     */
    private $appForDepositNew = null;

    /**
     * Запрос отправки заявления на пролонгацию вклада (депозита)
     *
     * @property \common\models\sbbolxml\request\PermBalanceDataType $permBalanceNew
     */
    private $permBalanceNew = null;

    /**
     * Запрос списка договоров НСО
     *
     * @property \common\models\sbbolxml\request\CardPermBalanceType $cardPermBalance
     */
    private $cardPermBalance = null;

    /**
     * Запрос на генерацию SMS с кодом подтверждения сброса пароля Администратору СББ
     *
     * @property string $genAuthSMSSign
     */
    private $genAuthSMSSign = null;

    /**
     * Отправка кода подтверждения сброса пароля Администратору СББ, полученного по SMS, на проверку
     *
     * @property \common\models\sbbolxml\request\VerifyAuthSMSSignType $verifyAuthSMSSign
     */
    private $verifyAuthSMSSign = null;

    /**
     * Информация для фиксирования в журнале аудита СББОЛ
     *
     * @property \common\models\sbbolxml\request\AuditType $audit
     */
    private $audit = null;

    /**
     * Реестр задолженностей
     *
     * @property \common\models\sbbolxml\request\FeesRegistryType $feesRegistry
     */
    private $feesRegistry = null;

    /**
     * Запрос на скачивание реестра платежей
     *
     * @property \common\models\sbbolxml\request\FeesRegistryDownloadType $feesRegistryDownload
     */
    private $feesRegistryDownload = null;

    /**
     * Запрос изменения статуса Реестра платежей при выгрузке вложений
     *
     * @property \common\models\sbbolxml\request\FeesRegistryAcceptType $feesRegistryAccept
     */
    private $feesRegistryAccept = null;

    /**
     * Запрос на получение ссылок для загрузки файлов в систему 'Большие Файлы'
     *
     * @property \common\models\sbbolxml\request\EssenceLinkType[] $essenceLinks
     */
    private $essenceLinks = null;

    /**
     * Реестр задолженностей
     *
     * @property \common\models\sbbolxml\request\DebtRegistryType $debtRegistry
     */
    private $debtRegistry = null;

    /**
     * Заявка на получение наличных средств
     *
     * @property \common\models\sbbolxml\request\CashOrderType $cashOrder
     */
    private $cashOrder = null;

    /**
     * Отмена заявки на получение наличных средств
     *
     * @property \common\models\sbbolxml\request\RecallCashOrderType $recallCashOrder
     */
    private $recallCashOrder = null;

    /**
     * Справка о валютных операциях
     *
     * @property \common\models\sbbolxml\request\CurrDealCertificate181IType $currDealCertificate181I
     */
    private $currDealCertificate181I = null;

    /**
     * Запрос о получении данных по ведомости банковского контроля
     *
     * @property \common\models\sbbolxml\request\IntCtrlStatementXML181IType $intCtrlStatementXML181I
     */
    private $intCtrlStatementXML181I = null;

    /**
     * Запрос на создание печатной формы выписки на Банке
     *
     * @property \common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType $bigFilesDownloadPrintFormLink
     */
    private $bigFilesDownloadPrintFormLink = null;

    /**
     * Письмо свободного формата в банк
     *
     * @property \common\models\sbbolxml\request\GenericLetterToBankType $genericLetterToBank
     */
    private $genericLetterToBank = null;

    /**
     * Запрос на получение выписки с новыми операциями
     *
     * @property \common\models\sbbolxml\request\IncrementStatementsType $incrementStatements
     */
    private $incrementStatements = null;

    /**
     * Запрос на получение дайджеста по уже отправленному в банк документу.
     *
     * @property \common\models\sbbolxml\request\RequestType\DocDigestAType $docDigest
     */
    private $docDigest = null;

    /**
     * ЭП клиента
     *
     * @property \common\models\sbbolxml\request\DigitalSignType[] $sign
     */
    private $sign = array(
        
    );

    /**
     * Данные для fraud-мониторинга. Информация об отправившем документ в банк
     *
     * @property \common\models\sbbolxml\request\FraudType $fraud
     */
    private $fraud = null;

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
     * Gets as orgId
     *
     * Идентификатор организации в ДБО (может быть использован сторонней системой для
     *  дополнительной идентификации)
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
     * Идентификатор организации в ДБО (может быть использован сторонней системой для
     *  дополнительной идентификации)
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
     *  Если система получатель СББОЛ, то SBBOL_DBO
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
     *  Если система получатель СББОЛ, то SBBOL_DBO
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
     * Gets as senderKey
     *
     * Ключ системы-отправителя
     *
     * @return string
     */
    public function getSenderKey()
    {
        return $this->senderKey;
    }

    /**
     * Sets a new senderKey
     *
     * Ключ системы-отправителя
     *
     * @param string $senderKey
     * @return static
     */
    public function setSenderKey($senderKey)
    {
        $this->senderKey = $senderKey;
        return $this;
    }

    /**
     * Gets as bankOnly
     *
     * Флаг сообщения для банка
     *
     * @return boolean
     */
    public function getBankOnly()
    {
        return $this->bankOnly;
    }

    /**
     * Sets a new bankOnly
     *
     * Флаг сообщения для банка
     *
     * @param boolean $bankOnly
     * @return static
     */
    public function setBankOnly($bankOnly)
    {
        $this->bankOnly = $bankOnly;
        return $this;
    }

    /**
     * Gets as protocolVersion
     *
     * Версия протокола
     *
     * @return string
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * Sets a new protocolVersion
     *
     * Версия протокола
     *
     * @param string $protocolVersion
     * @return static
     */
    public function setProtocolVersion($protocolVersion)
    {
        $this->protocolVersion = $protocolVersion;
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
     * @param \common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType $docId
     */
    public function addToDocIds(\common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType $docId)
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
     * @param scalar $index
     * @return boolean
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
     * @param scalar $index
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
     * @return \common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType[]
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
     * @param \common\models\sbbolxml\request\RequestType\DocIdsAType\DocIdAType[] $docIds
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
     * @return \common\models\sbbolxml\request\PayDocRuType
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
     * @param \common\models\sbbolxml\request\PayDocRuType $payDocRu
     * @return static
     */
    public function setPayDocRu(\common\models\sbbolxml\request\PayDocRuType $payDocRu)
    {
        $this->payDocRu = $payDocRu;
        return $this;
    }

    /**
     * Gets as holdingInfoRequest
     *
     * @return string
     */
    public function getHoldingInfoRequest()
    {
        return $this->holdingInfoRequest;
    }

    /**
     * Sets a new holdingInfoRequest
     *
     * @param string $holdingInfoRequest
     * @return static
     */
    public function setHoldingInfoRequest($holdingInfoRequest)
    {
        $this->holdingInfoRequest = $holdingInfoRequest;
        return $this;
    }

    /**
     * Gets as ticket
     *
     * Квитанция о получении документа на стороне УС
     *
     * @return \common\models\sbbolxml\request\TicketClType
     */
    public function getTicket()
    {
        return $this->ticket;
    }

    /**
     * Sets a new ticket
     *
     * Квитанция о получении документа на стороне УС
     *
     * @param \common\models\sbbolxml\request\TicketClType $ticket
     * @return static
     */
    public function setTicket(\common\models\sbbolxml\request\TicketClType $ticket)
    {
        $this->ticket = $ticket;
        return $this;
    }

    /**
     * Gets as incoming
     *
     * Запрос о получении данных, подготовленных для УС ("ночных выписок", выписок,
     *  тикетов после отказа в РС, новостей)
     *
     * @return \common\models\sbbolxml\request\RequestType\IncomingAType
     */
    public function getIncoming()
    {
        return $this->incoming;
    }

    /**
     * Sets a new incoming
     *
     * Запрос о получении данных, подготовленных для УС ("ночных выписок", выписок,
     *  тикетов после отказа в РС, новостей)
     *
     * @param \common\models\sbbolxml\request\RequestType\IncomingAType $incoming
     * @return static
     */
    public function setIncoming(\common\models\sbbolxml\request\RequestType\IncomingAType $incoming)
    {
        $this->incoming = $incoming;
        return $this;
    }

    /**
     * Gets as cryptoIncoming
     *
     * Запрос криптографической информации (криптопрофилей, сертификатов)
     *
     * @return \common\models\sbbolxml\request\RequestType\CryptoIncomingAType
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
     * @param \common\models\sbbolxml\request\RequestType\CryptoIncomingAType $cryptoIncoming
     * @return static
     */
    public function setCryptoIncoming(\common\models\sbbolxml\request\RequestType\CryptoIncomingAType $cryptoIncoming)
    {
        $this->cryptoIncoming = $cryptoIncoming;
        return $this;
    }

    /**
     * Gets as activateCert
     *
     * @return \common\models\sbbolxml\request\ActivateCertType
     */
    public function getActivateCert()
    {
        return $this->activateCert;
    }

    /**
     * Sets a new activateCert
     *
     * @param \common\models\sbbolxml\request\ActivateCertType $activateCert
     * @return static
     */
    public function setActivateCert(\common\models\sbbolxml\request\ActivateCertType $activateCert)
    {
        $this->activateCert = $activateCert;
        return $this;
    }

    /**
     * Gets as stmtReq
     *
     * Запрос на получение информации о движении ден. средств
     *
     * @return \common\models\sbbolxml\request\StmtReqType
     */
    public function getStmtReq()
    {
        return $this->stmtReq;
    }

    /**
     * Sets a new stmtReq
     *
     * Запрос на получение информации о движении ден. средств
     *
     * @param \common\models\sbbolxml\request\StmtReqType $stmtReq
     * @return static
     */
    public function setStmtReq(\common\models\sbbolxml\request\StmtReqType $stmtReq)
    {
        $this->stmtReq = $stmtReq;
        return $this;
    }

    /**
     * Gets as payDocRuGetSMS
     *
     * Запрос СМС-пароля
     *
     * @return \common\models\sbbolxml\request\RequestType\PayDocRuGetSMSAType
     */
    public function getPayDocRuGetSMS()
    {
        return $this->payDocRuGetSMS;
    }

    /**
     * Sets a new payDocRuGetSMS
     *
     * Запрос СМС-пароля
     *
     * @param \common\models\sbbolxml\request\RequestType\PayDocRuGetSMSAType $payDocRuGetSMS
     * @return static
     */
    public function setPayDocRuGetSMS(\common\models\sbbolxml\request\RequestType\PayDocRuGetSMSAType $payDocRuGetSMS)
    {
        $this->payDocRuGetSMS = $payDocRuGetSMS;
        return $this;
    }

    /**
     * Gets as payDocRuSMS
     *
     * Отправка платежки с паролем
     *
     * @return \common\models\sbbolxml\request\RequestType\PayDocRuSMSAType
     */
    public function getPayDocRuSMS()
    {
        return $this->payDocRuSMS;
    }

    /**
     * Sets a new payDocRuSMS
     *
     * Отправка платежки с паролем
     *
     * @param \common\models\sbbolxml\request\RequestType\PayDocRuSMSAType $payDocRuSMS
     * @return static
     */
    public function setPayDocRuSMS(\common\models\sbbolxml\request\RequestType\PayDocRuSMSAType $payDocRuSMS)
    {
        $this->payDocRuSMS = $payDocRuSMS;
        return $this;
    }

    /**
     * Gets as salaryDoc
     *
     * Зарплатная ведомость
     *
     * @return \common\models\sbbolxml\request\SalaryDocType
     */
    public function getSalaryDoc()
    {
        return $this->salaryDoc;
    }

    /**
     * Sets a new salaryDoc
     *
     * Зарплатная ведомость
     *
     * @param \common\models\sbbolxml\request\SalaryDocType $salaryDoc
     * @return static
     */
    public function setSalaryDoc(\common\models\sbbolxml\request\SalaryDocType $salaryDoc)
    {
        $this->salaryDoc = $salaryDoc;
        return $this;
    }

    /**
     * Gets as accept
     *
     * Заявление об акцепте/отказ от акцепта
     *
     * @return \common\models\sbbolxml\request\AcceptType
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Sets a new accept
     *
     * Заявление об акцепте/отказ от акцепта
     *
     * @param \common\models\sbbolxml\request\AcceptType $accept
     * @return static
     */
    public function setAccept(\common\models\sbbolxml\request\AcceptType $accept)
    {
        $this->accept = $accept;
        return $this;
    }

    /**
     * Gets as letterInBank
     *
     * Письмо в банк
     *
     * @return \common\models\sbbolxml\request\LetterInBankType
     */
    public function getLetterInBank()
    {
        return $this->letterInBank;
    }

    /**
     * Sets a new letterInBank
     *
     * Письмо в банк
     *
     * @param \common\models\sbbolxml\request\LetterInBankType $letterInBank
     * @return static
     */
    public function setLetterInBank(\common\models\sbbolxml\request\LetterInBankType $letterInBank)
    {
        $this->letterInBank = $letterInBank;
        return $this;
    }

    /**
     * Gets as currDealCertificate138I
     *
     * Справка о валютных операциях 138
     *
     * @return \common\models\sbbolxml\request\CurrDealCertificate138IType
     */
    public function getCurrDealCertificate138I()
    {
        return $this->currDealCertificate138I;
    }

    /**
     * Sets a new currDealCertificate138I
     *
     * Справка о валютных операциях 138
     *
     * @param \common\models\sbbolxml\request\CurrDealCertificate138IType $currDealCertificate138I
     * @return static
     */
    public function setCurrDealCertificate138I(\common\models\sbbolxml\request\CurrDealCertificate138IType $currDealCertificate138I)
    {
        $this->currDealCertificate138I = $currDealCertificate138I;
        return $this;
    }

    /**
     * Gets as confDocCertificate138I
     *
     * Справка о подтверждающих документах 138И
     *
     * @return \common\models\sbbolxml\request\ConfDocCertificate138IType
     */
    public function getConfDocCertificate138I()
    {
        return $this->confDocCertificate138I;
    }

    /**
     * Sets a new confDocCertificate138I
     *
     * Справка о подтверждающих документах 138И
     *
     * @param \common\models\sbbolxml\request\ConfDocCertificate138IType $confDocCertificate138I
     * @return static
     */
    public function setConfDocCertificate138I(\common\models\sbbolxml\request\ConfDocCertificate138IType $confDocCertificate138I)
    {
        $this->confDocCertificate138I = $confDocCertificate138I;
        return $this;
    }

    /**
     * Gets as confDocCertificate181I
     *
     * Справка о подтверждающих документах 181И
     *
     * @return \common\models\sbbolxml\request\ConfDocCertificate181IType
     */
    public function getConfDocCertificate181I()
    {
        return $this->confDocCertificate181I;
    }

    /**
     * Sets a new confDocCertificate181I
     *
     * Справка о подтверждающих документах 181И
     *
     * @param \common\models\sbbolxml\request\ConfDocCertificate181IType $confDocCertificate181I
     * @return static
     */
    public function setConfDocCertificate181I(\common\models\sbbolxml\request\ConfDocCertificate181IType $confDocCertificate181I)
    {
        $this->confDocCertificate181I = $confDocCertificate181I;
        return $this;
    }

    /**
     * Gets as icsRestruct181I
     *
     * Заявление о переоформлении ПС
     *
     * @return \common\models\sbbolxml\request\IcsRestruct181IType
     */
    public function getIcsRestruct181I()
    {
        return $this->icsRestruct181I;
    }

    /**
     * Sets a new icsRestruct181I
     *
     * Заявление о переоформлении ПС
     *
     * @param \common\models\sbbolxml\request\IcsRestruct181IType $icsRestruct181I
     * @return static
     */
    public function setIcsRestruct181I(\common\models\sbbolxml\request\IcsRestruct181IType $icsRestruct181I)
    {
        $this->icsRestruct181I = $icsRestruct181I;
        return $this;
    }

    /**
     * Gets as dzoAccAttach
     *
     * Прикрепление счетов дочерней организации к головной компании холдинга
     *
     * @return \common\models\sbbolxml\request\DzoAccAttachType
     */
    public function getDzoAccAttach()
    {
        return $this->dzoAccAttach;
    }

    /**
     * Sets a new dzoAccAttach
     *
     * Прикрепление счетов дочерней организации к головной компании холдинга
     *
     * @param \common\models\sbbolxml\request\DzoAccAttachType $dzoAccAttach
     * @return static
     */
    public function setDzoAccAttach(\common\models\sbbolxml\request\DzoAccAttachType $dzoAccAttach)
    {
        $this->dzoAccAttach = $dzoAccAttach;
        return $this;
    }

    /**
     * Gets as dealPassICS
     *
     * Ведомость банковского контроля
     *
     * @return \common\models\sbbolxml\request\DealPassICSType
     */
    public function getDealPassICS()
    {
        return $this->dealPassICS;
    }

    /**
     * Sets a new dealPassICS
     *
     * Ведомость банковского контроля
     *
     * @param \common\models\sbbolxml\request\DealPassICSType $dealPassICS
     * @return static
     */
    public function setDealPassICS(\common\models\sbbolxml\request\DealPassICSType $dealPassICS)
    {
        $this->dealPassICS = $dealPassICS;
        return $this;
    }

    /**
     * Gets as dealPassCon138I
     *
     * Паспорт сделки по контракту 138И
     *
     * @return \common\models\sbbolxml\request\DealPassCon138IType
     */
    public function getDealPassCon138I()
    {
        return $this->dealPassCon138I;
    }

    /**
     * Sets a new dealPassCon138I
     *
     * Паспорт сделки по контракту 138И
     *
     * @param \common\models\sbbolxml\request\DealPassCon138IType $dealPassCon138I
     * @return static
     */
    public function setDealPassCon138I(\common\models\sbbolxml\request\DealPassCon138IType $dealPassCon138I)
    {
        $this->dealPassCon138I = $dealPassCon138I;
        return $this;
    }

    /**
     * Gets as dealPassCred138I
     *
     * Паспорт сделки по кредитному договору 138И
     *
     * @return \common\models\sbbolxml\request\DealPassCred138IType
     */
    public function getDealPassCred138I()
    {
        return $this->dealPassCred138I;
    }

    /**
     * Sets a new dealPassCred138I
     *
     * Паспорт сделки по кредитному договору 138И
     *
     * @param \common\models\sbbolxml\request\DealPassCred138IType $dealPassCred138I
     * @return static
     */
    public function setDealPassCred138I(\common\models\sbbolxml\request\DealPassCred138IType $dealPassCred138I)
    {
        $this->dealPassCred138I = $dealPassCred138I;
        return $this;
    }

    /**
     * Gets as dealPassRestruct
     *
     * Заявление о переоформлении паспорта сделки 138И
     *
     * @return \common\models\sbbolxml\request\DealPassRestructType
     */
    public function getDealPassRestruct()
    {
        return $this->dealPassRestruct;
    }

    /**
     * Sets a new dealPassRestruct
     *
     * Заявление о переоформлении паспорта сделки 138И
     *
     * @param \common\models\sbbolxml\request\DealPassRestructType $dealPassRestruct
     * @return static
     */
    public function setDealPassRestruct(\common\models\sbbolxml\request\DealPassRestructType $dealPassRestruct)
    {
        $this->dealPassRestruct = $dealPassRestruct;
        return $this;
    }

    /**
     * Gets as dealPassClose
     *
     * Заявление о закрытии паспорта сделки 138И
     *
     * @return \common\models\sbbolxml\request\DealPassCloseType
     */
    public function getDealPassClose()
    {
        return $this->dealPassClose;
    }

    /**
     * Sets a new dealPassClose
     *
     * Заявление о закрытии паспорта сделки 138И
     *
     * @param \common\models\sbbolxml\request\DealPassCloseType $dealPassClose
     * @return static
     */
    public function setDealPassClose(\common\models\sbbolxml\request\DealPassCloseType $dealPassClose)
    {
        $this->dealPassClose = $dealPassClose;
        return $this;
    }

    /**
     * Gets as certifRequestQualified
     *
     * Запрос на выпуск нового сертификата
     *
     * @return \common\models\sbbolxml\request\CertifRequestQualifiedType
     */
    public function getCertifRequestQualified()
    {
        return $this->certifRequestQualified;
    }

    /**
     * Sets a new certifRequestQualified
     *
     * Запрос на выпуск нового сертификата
     *
     * @param \common\models\sbbolxml\request\CertifRequestQualifiedType $certifRequestQualified
     * @return static
     */
    public function setCertifRequestQualified(\common\models\sbbolxml\request\CertifRequestQualifiedType $certifRequestQualified)
    {
        $this->certifRequestQualified = $certifRequestQualified;
        return $this;
    }

    /**
     * Gets as certifRequest
     *
     * Запрос на выпуск нового сертификата
     *
     * @return \common\models\sbbolxml\request\CertifRequestType
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
     * @param \common\models\sbbolxml\request\CertifRequestType $certifRequest
     * @return static
     */
    public function setCertifRequest(\common\models\sbbolxml\request\CertifRequestType $certifRequest)
    {
        $this->certifRequest = $certifRequest;
        return $this;
    }

    /**
     * Gets as revocationCertifRequest
     *
     * Запрос на отзыв сертификата
     *
     * @return \common\models\sbbolxml\request\RevocationCertifRequestType
     */
    public function getRevocationCertifRequest()
    {
        return $this->revocationCertifRequest;
    }

    /**
     * Sets a new revocationCertifRequest
     *
     * Запрос на отзыв сертификата
     *
     * @param \common\models\sbbolxml\request\RevocationCertifRequestType $revocationCertifRequest
     * @return static
     */
    public function setRevocationCertifRequest(\common\models\sbbolxml\request\RevocationCertifRequestType $revocationCertifRequest)
    {
        $this->revocationCertifRequest = $revocationCertifRequest;
        return $this;
    }

    /**
     * Gets as payDocCur
     *
     * Поручение на перевод валюты
     *
     * @return \common\models\sbbolxml\request\PayDocCurType
     */
    public function getPayDocCur()
    {
        return $this->payDocCur;
    }

    /**
     * Sets a new payDocCur
     *
     * Поручение на перевод валюты
     *
     * @param \common\models\sbbolxml\request\PayDocCurType $payDocCur
     * @return static
     */
    public function setPayDocCur(\common\models\sbbolxml\request\PayDocCurType $payDocCur)
    {
        $this->payDocCur = $payDocCur;
        return $this;
    }

    /**
     * Gets as currBuy
     *
     * Поручение на покупку валюты
     *
     * @return \common\models\sbbolxml\request\CurrBuyType
     */
    public function getCurrBuy()
    {
        return $this->currBuy;
    }

    /**
     * Sets a new currBuy
     *
     * Поручение на покупку валюты
     *
     * @param \common\models\sbbolxml\request\CurrBuyType $currBuy
     * @return static
     */
    public function setCurrBuy(\common\models\sbbolxml\request\CurrBuyType $currBuy)
    {
        $this->currBuy = $currBuy;
        return $this;
    }

    /**
     * Gets as currSell
     *
     * Поручение на продажу валюты
     *
     * @return \common\models\sbbolxml\request\CurrSellType
     */
    public function getCurrSell()
    {
        return $this->currSell;
    }

    /**
     * Sets a new currSell
     *
     * Поручение на продажу валюты
     *
     * @param \common\models\sbbolxml\request\CurrSellType $currSell
     * @return static
     */
    public function setCurrSell(\common\models\sbbolxml\request\CurrSellType $currSell)
    {
        $this->currSell = $currSell;
        return $this;
    }

    /**
     * Gets as mandatorySale
     *
     * Распоряжение об осуществлении обязательной продажи
     *
     * @return \common\models\sbbolxml\request\MandatorySaleType
     */
    public function getMandatorySale()
    {
        return $this->mandatorySale;
    }

    /**
     * Sets a new mandatorySale
     *
     * Распоряжение об осуществлении обязательной продажи
     *
     * @param \common\models\sbbolxml\request\MandatorySaleType $mandatorySale
     * @return static
     */
    public function setMandatorySale(\common\models\sbbolxml\request\MandatorySaleType $mandatorySale)
    {
        $this->mandatorySale = $mandatorySale;
        return $this;
    }

    /**
     * Gets as regOfCorpCards
     *
     * Реестр на пополнение средств по корпоративным картам
     *
     * @return \common\models\sbbolxml\request\RegOfCorpCardsType
     */
    public function getRegOfCorpCards()
    {
        return $this->regOfCorpCards;
    }

    /**
     * Sets a new regOfCorpCards
     *
     * Реестр на пополнение средств по корпоративным картам
     *
     * @param \common\models\sbbolxml\request\RegOfCorpCardsType $regOfCorpCards
     * @return static
     */
    public function setRegOfCorpCards(\common\models\sbbolxml\request\RegOfCorpCardsType $regOfCorpCards)
    {
        $this->regOfCorpCards = $regOfCorpCards;
        return $this;
    }

    /**
     * Gets as applForContract
     *
     * Заявка на зарплатный договор
     *
     * @return \common\models\sbbolxml\request\ApplForContractType
     */
    public function getApplForContract()
    {
        return $this->applForContract;
    }

    /**
     * Sets a new applForContract
     *
     * Заявка на зарплатный договор
     *
     * @param \common\models\sbbolxml\request\ApplForContractType $applForContract
     * @return static
     */
    public function setApplForContract(\common\models\sbbolxml\request\ApplForContractType $applForContract)
    {
        $this->applForContract = $applForContract;
        return $this;
    }

    /**
     * Gets as regOfIssCards
     *
     * Реестр на открытие счетов и выпуск карт
     *
     * @return \common\models\sbbolxml\request\RegOfIssCardsType
     */
    public function getRegOfIssCards()
    {
        return $this->regOfIssCards;
    }

    /**
     * Sets a new regOfIssCards
     *
     * Реестр на открытие счетов и выпуск карт
     *
     * @param \common\models\sbbolxml\request\RegOfIssCardsType $regOfIssCards
     * @return static
     */
    public function setRegOfIssCards(\common\models\sbbolxml\request\RegOfIssCardsType $regOfIssCards)
    {
        $this->regOfIssCards = $regOfIssCards;
        return $this;
    }

    /**
     * Gets as regOfFiredEmployees
     *
     * Реестр на увольнение сотрудников
     *
     * @return \common\models\sbbolxml\request\RegOfFiredEmployeesType
     */
    public function getRegOfFiredEmployees()
    {
        return $this->regOfFiredEmployees;
    }

    /**
     * Sets a new regOfFiredEmployees
     *
     * Реестр на увольнение сотрудников
     *
     * @param \common\models\sbbolxml\request\RegOfFiredEmployeesType $regOfFiredEmployees
     * @return static
     */
    public function setRegOfFiredEmployees(\common\models\sbbolxml\request\RegOfFiredEmployeesType $regOfFiredEmployees)
    {
        $this->regOfFiredEmployees = $regOfFiredEmployees;
        return $this;
    }

    /**
     * Adds as dict
     *
     * Репликация справочников
     *
     * @return static
     * @param \common\models\sbbolxml\request\DictType $dict
     */
    public function addToDict(\common\models\sbbolxml\request\DictType $dict)
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
     * @return \common\models\sbbolxml\request\DictType[]
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
     * @param \common\models\sbbolxml\request\DictType[] $dict
     * @return static
     */
    public function setDict(array $dict)
    {
        $this->dict = $dict;
        return $this;
    }

    /**
     * Adds as rzkDictUpdate
     *
     * Запрос репликации справочника СБК
     *
     * @return static
     * @param \common\models\sbbolxml\request\RzkDictUpdateType $rzkDictUpdate
     */
    public function addToRzkDictUpdate(\common\models\sbbolxml\request\RzkDictUpdateType $rzkDictUpdate)
    {
        $this->rzkDictUpdate[] = $rzkDictUpdate;
        return $this;
    }

    /**
     * isset rzkDictUpdate
     *
     * Запрос репликации справочника СБК
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetRzkDictUpdate($index)
    {
        return isset($this->rzkDictUpdate[$index]);
    }

    /**
     * unset rzkDictUpdate
     *
     * Запрос репликации справочника СБК
     *
     * @param scalar $index
     * @return void
     */
    public function unsetRzkDictUpdate($index)
    {
        unset($this->rzkDictUpdate[$index]);
    }

    /**
     * Gets as rzkDictUpdate
     *
     * Запрос репликации справочника СБК
     *
     * @return \common\models\sbbolxml\request\RzkDictUpdateType[]
     */
    public function getRzkDictUpdate()
    {
        return $this->rzkDictUpdate;
    }

    /**
     * Sets a new rzkDictUpdate
     *
     * Запрос репликации справочника СБК
     *
     * @param \common\models\sbbolxml\request\RzkDictUpdateType[] $rzkDictUpdate
     * @return static
     */
    public function setRzkDictUpdate(array $rzkDictUpdate)
    {
        $this->rzkDictUpdate = $rzkDictUpdate;
        return $this;
    }

    /**
     * Gets as personalInfo
     *
     * Запрос персональных данных
     *
     * @return \common\models\sbbolxml\request\PersonalInfoType
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
     * @param \common\models\sbbolxml\request\PersonalInfoType $personalInfo
     * @return static
     */
    public function setPersonalInfo(\common\models\sbbolxml\request\PersonalInfoType $personalInfo)
    {
        $this->personalInfo = $personalInfo;
        return $this;
    }

    /**
     * Gets as docTypeConfigInfo
     *
     * Запрос данных о типах документов
     *
     * @return \common\models\sbbolxml\request\DocTypeConfigInfoType
     */
    public function getDocTypeConfigInfo()
    {
        return $this->docTypeConfigInfo;
    }

    /**
     * Sets a new docTypeConfigInfo
     *
     * Запрос данных о типах документов
     *
     * @param \common\models\sbbolxml\request\DocTypeConfigInfoType $docTypeConfigInfo
     * @return static
     */
    public function setDocTypeConfigInfo(\common\models\sbbolxml\request\DocTypeConfigInfoType $docTypeConfigInfo)
    {
        $this->docTypeConfigInfo = $docTypeConfigInfo;
        return $this;
    }

    /**
     * Gets as clientAppUpdateRequest
     *
     * Запрос обновлений для Offline-клиента
     *
     * @return \common\models\sbbolxml\request\UpdateRequestType
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
     * @param \common\models\sbbolxml\request\UpdateRequestType $clientAppUpdateRequest
     * @return static
     */
    public function setClientAppUpdateRequest(\common\models\sbbolxml\request\UpdateRequestType $clientAppUpdateRequest)
    {
        $this->clientAppUpdateRequest = $clientAppUpdateRequest;
        return $this;
    }

    /**
     * Gets as payRequest
     *
     * Платежное требование и Инкассовое поручение
     *
     * @return \common\models\sbbolxml\request\PayRequestType
     */
    public function getPayRequest()
    {
        return $this->payRequest;
    }

    /**
     * Sets a new payRequest
     *
     * Платежное требование и Инкассовое поручение
     *
     * @param \common\models\sbbolxml\request\PayRequestType $payRequest
     * @return static
     */
    public function setPayRequest(\common\models\sbbolxml\request\PayRequestType $payRequest)
    {
        $this->payRequest = $payRequest;
        return $this;
    }

    /**
     * Gets as creditOrder
     *
     * Распоряжение на перевод кредитных средств
     *
     * @return \common\models\sbbolxml\request\CreditOrderType
     */
    public function getCreditOrder()
    {
        return $this->creditOrder;
    }

    /**
     * Sets a new creditOrder
     *
     * Распоряжение на перевод кредитных средств
     *
     * @param \common\models\sbbolxml\request\CreditOrderType $creditOrder
     * @return static
     */
    public function setCreditOrder(\common\models\sbbolxml\request\CreditOrderType $creditOrder)
    {
        $this->creditOrder = $creditOrder;
        return $this;
    }

    /**
     * Gets as revocationRequest
     *
     * Запрос на отзыв документов
     *
     * @return \common\models\sbbolxml\request\RevocationRequestType
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
     * @param \common\models\sbbolxml\request\RevocationRequestType $revocationRequest
     * @return static
     */
    public function setRevocationRequest(\common\models\sbbolxml\request\RevocationRequestType $revocationRequest)
    {
        $this->revocationRequest = $revocationRequest;
        return $this;
    }

    /**
     * Gets as remainRequest
     *
     * Запрос информации об остатках по счетам
     *
     * @return \common\models\sbbolxml\request\OverdraftRequestType
     */
    public function getRemainRequest()
    {
        return $this->remainRequest;
    }

    /**
     * Sets a new remainRequest
     *
     * Запрос информации об остатках по счетам
     *
     * @param \common\models\sbbolxml\request\OverdraftRequestType $remainRequest
     * @return static
     */
    public function setRemainRequest(\common\models\sbbolxml\request\OverdraftRequestType $remainRequest)
    {
        $this->remainRequest = $remainRequest;
        return $this;
    }

    /**
     * Gets as firmwareUpdateRequest
     *
     * Запрос обновления прошивки токена
     *
     * @return \common\models\sbbolxml\request\FirmwareUpdateRequestType
     */
    public function getFirmwareUpdateRequest()
    {
        return $this->firmwareUpdateRequest;
    }

    /**
     * Sets a new firmwareUpdateRequest
     *
     * Запрос обновления прошивки токена
     *
     * @param \common\models\sbbolxml\request\FirmwareUpdateRequestType $firmwareUpdateRequest
     * @return static
     */
    public function setFirmwareUpdateRequest(\common\models\sbbolxml\request\FirmwareUpdateRequestType $firmwareUpdateRequest)
    {
        $this->firmwareUpdateRequest = $firmwareUpdateRequest;
        return $this;
    }

    /**
     * Gets as corrAdd
     *
     * ЭД на добавление записи с подтвержденным контрагентом
     *
     * @return \common\models\sbbolxml\request\CorrAddType
     */
    public function getCorrAdd()
    {
        return $this->corrAdd;
    }

    /**
     * Sets a new corrAdd
     *
     * ЭД на добавление записи с подтвержденным контрагентом
     *
     * @param \common\models\sbbolxml\request\CorrAddType $corrAdd
     * @return static
     */
    public function setCorrAdd(\common\models\sbbolxml\request\CorrAddType $corrAdd)
    {
        $this->corrAdd = $corrAdd;
        return $this;
    }

    /**
     * Gets as substDocAdd
     *
     * Добавление подтверждающего документа
     *
     * @return \common\models\sbbolxml\request\SubstDocAddType
     */
    public function getSubstDocAdd()
    {
        return $this->substDocAdd;
    }

    /**
     * Sets a new substDocAdd
     *
     * Добавление подтверждающего документа
     *
     * @param \common\models\sbbolxml\request\SubstDocAddType $substDocAdd
     * @return static
     */
    public function setSubstDocAdd(\common\models\sbbolxml\request\SubstDocAddType $substDocAdd)
    {
        $this->substDocAdd = $substDocAdd;
        return $this;
    }

    /**
     * Gets as corrDel
     *
     * ЭД удаление записи подтвержденного контрагента
     *
     * @return \common\models\sbbolxml\request\CorrDelType
     */
    public function getCorrDel()
    {
        return $this->corrDel;
    }

    /**
     * Sets a new corrDel
     *
     * ЭД удаление записи подтвержденного контрагента
     *
     * @param \common\models\sbbolxml\request\CorrDelType $corrDel
     * @return static
     */
    public function setCorrDel(\common\models\sbbolxml\request\CorrDelType $corrDel)
    {
        $this->corrDel = $corrDel;
        return $this;
    }

    /**
     * Gets as genSMSSign
     *
     * Запрос на генерацию SMS с кодом для подписи
     *
     * @return \common\models\sbbolxml\request\SMSSignReqParamsType
     */
    public function getGenSMSSign()
    {
        return $this->genSMSSign;
    }

    /**
     * Sets a new genSMSSign
     *
     * Запрос на генерацию SMS с кодом для подписи
     *
     * @param \common\models\sbbolxml\request\SMSSignReqParamsType $genSMSSign
     * @return static
     */
    public function setGenSMSSign(\common\models\sbbolxml\request\SMSSignReqParamsType $genSMSSign)
    {
        $this->genSMSSign = $genSMSSign;
        return $this;
    }

    /**
     * Gets as verifySMSSign
     *
     * Получение SMS с кодом для подписи
     *
     * @return \common\models\sbbolxml\request\VerifySMSSignType
     */
    public function getVerifySMSSign()
    {
        return $this->verifySMSSign;
    }

    /**
     * Sets a new verifySMSSign
     *
     * Получение SMS с кодом для подписи
     *
     * @param \common\models\sbbolxml\request\VerifySMSSignType $verifySMSSign
     * @return static
     */
    public function setVerifySMSSign(\common\models\sbbolxml\request\VerifySMSSignType $verifySMSSign)
    {
        $this->verifySMSSign = $verifySMSSign;
        return $this;
    }

    /**
     * Gets as benefAdd
     *
     * ЭД на добавление записи бенефициара
     *
     * @return \common\models\sbbolxml\request\BenefAddType
     */
    public function getBenefAdd()
    {
        return $this->benefAdd;
    }

    /**
     * Sets a new benefAdd
     *
     * ЭД на добавление записи бенефициара
     *
     * @param \common\models\sbbolxml\request\BenefAddType $benefAdd
     * @return static
     */
    public function setBenefAdd(\common\models\sbbolxml\request\BenefAddType $benefAdd)
    {
        $this->benefAdd = $benefAdd;
        return $this;
    }

    /**
     * Gets as benefDel
     *
     * ЭД удаление записи бенефициара
     *
     * @return \common\models\sbbolxml\request\BenefDelType
     */
    public function getBenefDel()
    {
        return $this->benefDel;
    }

    /**
     * Sets a new benefDel
     *
     * ЭД удаление записи бенефициара
     *
     * @param \common\models\sbbolxml\request\BenefDelType $benefDel
     * @return static
     */
    public function setBenefDel(\common\models\sbbolxml\request\BenefDelType $benefDel)
    {
        $this->benefDel = $benefDel;
        return $this;
    }

    /**
     * Gets as enableUseSignCorrDict
     *
     * Передача признака «Использовать подтвержденный справочник контрагентов»
     *
     * @return \common\models\sbbolxml\request\EnableUseSignCorrDictType
     */
    public function getEnableUseSignCorrDict()
    {
        return $this->enableUseSignCorrDict;
    }

    /**
     * Sets a new enableUseSignCorrDict
     *
     * Передача признака «Использовать подтвержденный справочник контрагентов»
     *
     * @param \common\models\sbbolxml\request\EnableUseSignCorrDictType $enableUseSignCorrDict
     * @return static
     */
    public function setEnableUseSignCorrDict(\common\models\sbbolxml\request\EnableUseSignCorrDictType $enableUseSignCorrDict)
    {
        $this->enableUseSignCorrDict = $enableUseSignCorrDict;
        return $this;
    }

    /**
     * Gets as corpCardExtIssueRequest
     *
     * Заявление на выпуск КК
     *
     * @return \common\models\sbbolxml\request\CorpCardExtIssueRequestType
     */
    public function getCorpCardExtIssueRequest()
    {
        return $this->corpCardExtIssueRequest;
    }

    /**
     * Sets a new corpCardExtIssueRequest
     *
     * Заявление на выпуск КК
     *
     * @param \common\models\sbbolxml\request\CorpCardExtIssueRequestType $corpCardExtIssueRequest
     * @return static
     */
    public function setCorpCardExtIssueRequest(\common\models\sbbolxml\request\CorpCardExtIssueRequestType $corpCardExtIssueRequest)
    {
        $this->corpCardExtIssueRequest = $corpCardExtIssueRequest;
        return $this;
    }

    /**
     * Gets as updateListOfEmployees
     *
     * Запрос на получение (обновление) списка сотрудников
     *
     * @return \common\models\sbbolxml\request\UpdateListOfEmployeesType
     */
    public function getUpdateListOfEmployees()
    {
        return $this->updateListOfEmployees;
    }

    /**
     * Sets a new updateListOfEmployees
     *
     * Запрос на получение (обновление) списка сотрудников
     *
     * @param \common\models\sbbolxml\request\UpdateListOfEmployeesType $updateListOfEmployees
     * @return static
     */
    public function setUpdateListOfEmployees(\common\models\sbbolxml\request\UpdateListOfEmployeesType $updateListOfEmployees)
    {
        $this->updateListOfEmployees = $updateListOfEmployees;
        return $this;
    }

    /**
     * Gets as exchangeMessagesWithBank
     *
     * Запрос ТК к СББОЛ
     *
     * @return \common\models\sbbolxml\request\ExchangeMessagesWithBankType
     */
    public function getExchangeMessagesWithBank()
    {
        return $this->exchangeMessagesWithBank;
    }

    /**
     * Sets a new exchangeMessagesWithBank
     *
     * Запрос ТК к СББОЛ
     *
     * @param \common\models\sbbolxml\request\ExchangeMessagesWithBankType $exchangeMessagesWithBank
     * @return static
     */
    public function setExchangeMessagesWithBank(\common\models\sbbolxml\request\ExchangeMessagesWithBankType $exchangeMessagesWithBank)
    {
        $this->exchangeMessagesWithBank = $exchangeMessagesWithBank;
        return $this;
    }

    /**
     * Gets as currControlInfoRequest
     *
     * Запрос информации ВК
     *
     * @return \common\models\sbbolxml\request\CurrControlInfoRequestType
     */
    public function getCurrControlInfoRequest()
    {
        return $this->currControlInfoRequest;
    }

    /**
     * Sets a new currControlInfoRequest
     *
     * Запрос информации ВК
     *
     * @param \common\models\sbbolxml\request\CurrControlInfoRequestType $currControlInfoRequest
     * @return static
     */
    public function setCurrControlInfoRequest(\common\models\sbbolxml\request\CurrControlInfoRequestType $currControlInfoRequest)
    {
        $this->currControlInfoRequest = $currControlInfoRequest;
        return $this;
    }

    /**
     * Gets as iskForUL
     *
     * Информационные сведения юридического лица
     *
     * @return \common\models\sbbolxml\request\ISKForULType
     */
    public function getIskForUL()
    {
        return $this->iskForUL;
    }

    /**
     * Sets a new iskForUL
     *
     * Информационные сведения юридического лица
     *
     * @param \common\models\sbbolxml\request\ISKForULType $iskForUL
     * @return static
     */
    public function setIskForUL(\common\models\sbbolxml\request\ISKForULType $iskForUL)
    {
        $this->iskForUL = $iskForUL;
        return $this;
    }

    /**
     * Gets as iskForIP
     *
     * Информационные сведения индивидуального предпринимателя
     *
     * @return \common\models\sbbolxml\request\ISKForIPType
     */
    public function getIskForIP()
    {
        return $this->iskForIP;
    }

    /**
     * Sets a new iskForIP
     *
     * Информационные сведения индивидуального предпринимателя
     *
     * @param \common\models\sbbolxml\request\ISKForIPType $iskForIP
     * @return static
     */
    public function setIskForIP(\common\models\sbbolxml\request\ISKForIPType $iskForIP)
    {
        $this->iskForIP = $iskForIP;
        return $this;
    }

    /**
     * Gets as incomingRequestISK
     *
     * Проверка наличия запроса на получение сведений об организации
     *
     * @return \common\models\sbbolxml\request\IncomingRequestISKType
     */
    public function getIncomingRequestISK()
    {
        return $this->incomingRequestISK;
    }

    /**
     * Sets a new incomingRequestISK
     *
     * Проверка наличия запроса на получение сведений об организации
     *
     * @param \common\models\sbbolxml\request\IncomingRequestISKType $incomingRequestISK
     * @return static
     */
    public function setIncomingRequestISK(\common\models\sbbolxml\request\IncomingRequestISKType $incomingRequestISK)
    {
        $this->incomingRequestISK = $incomingRequestISK;
        return $this;
    }

    /**
     * Gets as dealPassFromAnotherBank
     *
     * Паспорт сделки из другого банка
     *
     * @return \common\models\sbbolxml\request\DealPassFromAnotherBankType
     */
    public function getDealPassFromAnotherBank()
    {
        return $this->dealPassFromAnotherBank;
    }

    /**
     * Sets a new dealPassFromAnotherBank
     *
     * Паспорт сделки из другого банка
     *
     * @param \common\models\sbbolxml\request\DealPassFromAnotherBankType $dealPassFromAnotherBank
     * @return static
     */
    public function setDealPassFromAnotherBank(\common\models\sbbolxml\request\DealPassFromAnotherBankType $dealPassFromAnotherBank)
    {
        $this->dealPassFromAnotherBank = $dealPassFromAnotherBank;
        return $this;
    }

    /**
     * Gets as chatWithBank
     *
     * Сообщение чата с банком (от клиента)
     *
     * @return \common\models\sbbolxml\request\ChatWithBankMsgType
     */
    public function getChatWithBank()
    {
        return $this->chatWithBank;
    }

    /**
     * Sets a new chatWithBank
     *
     * Сообщение чата с банком (от клиента)
     *
     * @param \common\models\sbbolxml\request\ChatWithBankMsgType $chatWithBank
     * @return static
     */
    public function setChatWithBank(\common\models\sbbolxml\request\ChatWithBankMsgType $chatWithBank)
    {
        $this->chatWithBank = $chatWithBank;
        return $this;
    }

    /**
     * Gets as requestChatFromBankMsgs
     *
     * Запрос сообщений чата с банком (из банка)
     *
     * @return \common\models\sbbolxml\request\RequestChatFromBankMsgsType
     */
    public function getRequestChatFromBankMsgs()
    {
        return $this->requestChatFromBankMsgs;
    }

    /**
     * Sets a new requestChatFromBankMsgs
     *
     * Запрос сообщений чата с банком (из банка)
     *
     * @param \common\models\sbbolxml\request\RequestChatFromBankMsgsType $requestChatFromBankMsgs
     * @return static
     */
    public function setRequestChatFromBankMsgs(\common\models\sbbolxml\request\RequestChatFromBankMsgsType $requestChatFromBankMsgs)
    {
        $this->requestChatFromBankMsgs = $requestChatFromBankMsgs;
        return $this;
    }

    /**
     * Gets as chatWithBankMsgStatus
     *
     * Сообщение чата с банком (от клиента)
     *
     * @return \common\models\sbbolxml\request\ChatWithBankMsgStatusType
     */
    public function getChatWithBankMsgStatus()
    {
        return $this->chatWithBankMsgStatus;
    }

    /**
     * Sets a new chatWithBankMsgStatus
     *
     * Сообщение чата с банком (от клиента)
     *
     * @param \common\models\sbbolxml\request\ChatWithBankMsgStatusType $chatWithBankMsgStatus
     * @return static
     */
    public function setChatWithBankMsgStatus(\common\models\sbbolxml\request\ChatWithBankMsgStatusType $chatWithBankMsgStatus)
    {
        $this->chatWithBankMsgStatus = $chatWithBankMsgStatus;
        return $this;
    }

    /**
     * Gets as inquiryOrder
     *
     * Запрос справки
     *
     * @return \common\models\sbbolxml\request\InquiryOrderType
     */
    public function getInquiryOrder()
    {
        return $this->inquiryOrder;
    }

    /**
     * Sets a new inquiryOrder
     *
     * Запрос справки
     *
     * @param \common\models\sbbolxml\request\InquiryOrderType $inquiryOrder
     * @return static
     */
    public function setInquiryOrder(\common\models\sbbolxml\request\InquiryOrderType $inquiryOrder)
    {
        $this->inquiryOrder = $inquiryOrder;
        return $this;
    }

    /**
     * Gets as accountStatement
     *
     * Запрос сформированных выписок по счетам
     *
     * @return \common\models\sbbolxml\request\AccountStatementType
     */
    public function getAccountStatement()
    {
        return $this->accountStatement;
    }

    /**
     * Sets a new accountStatement
     *
     * Запрос сформированных выписок по счетам
     *
     * @param \common\models\sbbolxml\request\AccountStatementType $accountStatement
     * @return static
     */
    public function setAccountStatement(\common\models\sbbolxml\request\AccountStatementType $accountStatement)
    {
        $this->accountStatement = $accountStatement;
        return $this;
    }

    /**
     * Gets as rzkPayDocsRu
     *
     * Запрос рублевых платежных поручений
     *
     * @return \common\models\sbbolxml\request\RzkPayDocsRuType
     */
    public function getRzkPayDocsRu()
    {
        return $this->rzkPayDocsRu;
    }

    /**
     * Sets a new rzkPayDocsRu
     *
     * Запрос рублевых платежных поручений
     *
     * @param \common\models\sbbolxml\request\RzkPayDocsRuType $rzkPayDocsRu
     * @return static
     */
    public function setRzkPayDocsRu(\common\models\sbbolxml\request\RzkPayDocsRuType $rzkPayDocsRu)
    {
        $this->rzkPayDocsRu = $rzkPayDocsRu;
        return $this;
    }

    /**
     * Gets as currCourseEntry
     *
     * Запрос справочника валют
     *
     * @return \common\models\sbbolxml\request\CurrCourseEntryType
     */
    public function getCurrCourseEntry()
    {
        return $this->currCourseEntry;
    }

    /**
     * Sets a new currCourseEntry
     *
     * Запрос справочника валют
     *
     * @param \common\models\sbbolxml\request\CurrCourseEntryType $currCourseEntry
     * @return static
     */
    public function setCurrCourseEntry(\common\models\sbbolxml\request\CurrCourseEntryType $currCourseEntry)
    {
        $this->currCourseEntry = $currCourseEntry;
        return $this;
    }

    /**
     * Adds as gozDocUpdate
     *
     * Запрос списка документов по госконтракту
     *
     * @return static
     * @param \common\models\sbbolxml\request\DictType $gozDocUpdate
     */
    public function addToGozDocUpdate(\common\models\sbbolxml\request\DictType $gozDocUpdate)
    {
        $this->gozDocUpdate[] = $gozDocUpdate;
        return $this;
    }

    /**
     * isset gozDocUpdate
     *
     * Запрос списка документов по госконтракту
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetGozDocUpdate($index)
    {
        return isset($this->gozDocUpdate[$index]);
    }

    /**
     * unset gozDocUpdate
     *
     * Запрос списка документов по госконтракту
     *
     * @param scalar $index
     * @return void
     */
    public function unsetGozDocUpdate($index)
    {
        unset($this->gozDocUpdate[$index]);
    }

    /**
     * Gets as gozDocUpdate
     *
     * Запрос списка документов по госконтракту
     *
     * @return \common\models\sbbolxml\request\DictType[]
     */
    public function getGozDocUpdate()
    {
        return $this->gozDocUpdate;
    }

    /**
     * Sets a new gozDocUpdate
     *
     * Запрос списка документов по госконтракту
     *
     * @param \common\models\sbbolxml\request\DictType[] $gozDocUpdate
     * @return static
     */
    public function setGozDocUpdate(array $gozDocUpdate)
    {
        $this->gozDocUpdate = $gozDocUpdate;
        return $this;
    }

    /**
     * Adds as admDictUpdate
     *
     * Запрос репликации справочников самоинкассации
     *
     * @return static
     * @param \common\models\sbbolxml\request\DictType $admDictUpdate
     */
    public function addToAdmDictUpdate(\common\models\sbbolxml\request\DictType $admDictUpdate)
    {
        $this->admDictUpdate[] = $admDictUpdate;
        return $this;
    }

    /**
     * isset admDictUpdate
     *
     * Запрос репликации справочников самоинкассации
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetAdmDictUpdate($index)
    {
        return isset($this->admDictUpdate[$index]);
    }

    /**
     * unset admDictUpdate
     *
     * Запрос репликации справочников самоинкассации
     *
     * @param scalar $index
     * @return void
     */
    public function unsetAdmDictUpdate($index)
    {
        unset($this->admDictUpdate[$index]);
    }

    /**
     * Gets as admDictUpdate
     *
     * Запрос репликации справочников самоинкассации
     *
     * @return \common\models\sbbolxml\request\DictType[]
     */
    public function getAdmDictUpdate()
    {
        return $this->admDictUpdate;
    }

    /**
     * Sets a new admDictUpdate
     *
     * Запрос репликации справочников самоинкассации
     *
     * @param \common\models\sbbolxml\request\DictType[] $admDictUpdate
     * @return static
     */
    public function setAdmDictUpdate(array $admDictUpdate)
    {
        $this->admDictUpdate = $admDictUpdate;
        return $this;
    }

    /**
     * Gets as supplyDoc
     *
     * Досылаемые документы
     *
     * @return \common\models\sbbolxml\request\SupplyDocType
     */
    public function getSupplyDoc()
    {
        return $this->supplyDoc;
    }

    /**
     * Sets a new supplyDoc
     *
     * Досылаемые документы
     *
     * @param \common\models\sbbolxml\request\SupplyDocType $supplyDoc
     * @return static
     */
    public function setSupplyDoc(\common\models\sbbolxml\request\SupplyDocType $supplyDoc)
    {
        $this->supplyDoc = $supplyDoc;
        return $this;
    }

    /**
     * Adds as bigFilesUploadLinkRequest
     *
     * Запрос ссылок на загрузку в систему БФ
     *
     * @return static
     * @param \common\models\sbbolxml\request\BigFilesUploadLinksRequestType\BigFilesUploadLinkRequestAType $bigFilesUploadLinkRequest
     */
    public function addToBigFilesUploadLinksRequest(\common\models\sbbolxml\request\BigFilesUploadLinksRequestType\BigFilesUploadLinkRequestAType $bigFilesUploadLinkRequest)
    {
        $this->bigFilesUploadLinksRequest[] = $bigFilesUploadLinkRequest;
        return $this;
    }

    /**
     * isset bigFilesUploadLinksRequest
     *
     * Запрос ссылок на загрузку в систему БФ
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetBigFilesUploadLinksRequest($index)
    {
        return isset($this->bigFilesUploadLinksRequest[$index]);
    }

    /**
     * unset bigFilesUploadLinksRequest
     *
     * Запрос ссылок на загрузку в систему БФ
     *
     * @param scalar $index
     * @return void
     */
    public function unsetBigFilesUploadLinksRequest($index)
    {
        unset($this->bigFilesUploadLinksRequest[$index]);
    }

    /**
     * Gets as bigFilesUploadLinksRequest
     *
     * Запрос ссылок на загрузку в систему БФ
     *
     * @return \common\models\sbbolxml\request\BigFilesUploadLinksRequestType\BigFilesUploadLinkRequestAType[]
     */
    public function getBigFilesUploadLinksRequest()
    {
        return $this->bigFilesUploadLinksRequest;
    }

    /**
     * Sets a new bigFilesUploadLinksRequest
     *
     * Запрос ссылок на загрузку в систему БФ
     *
     * @param \common\models\sbbolxml\request\BigFilesUploadLinksRequestType\BigFilesUploadLinkRequestAType[] $bigFilesUploadLinksRequest
     * @return static
     */
    public function setBigFilesUploadLinksRequest(array $bigFilesUploadLinksRequest)
    {
        $this->bigFilesUploadLinksRequest = $bigFilesUploadLinksRequest;
        return $this;
    }

    /**
     * Gets as bigFilesStatusRequest
     *
     * Запрос статуса загруженных файлов
     *
     * @return \common\models\sbbolxml\request\BigFilesStatusRequestType
     */
    public function getBigFilesStatusRequest()
    {
        return $this->bigFilesStatusRequest;
    }

    /**
     * Sets a new bigFilesStatusRequest
     *
     * Запрос статуса загруженных файлов
     *
     * @param \common\models\sbbolxml\request\BigFilesStatusRequestType $bigFilesStatusRequest
     * @return static
     */
    public function setBigFilesStatusRequest(\common\models\sbbolxml\request\BigFilesStatusRequestType $bigFilesStatusRequest)
    {
        $this->bigFilesStatusRequest = $bigFilesStatusRequest;
        return $this;
    }

    /**
     * Gets as incomingDealConf
     *
     * Запрос сообщений о подтверждении сделок
     *
     * @return \common\models\sbbolxml\request\IncomingDealConfType
     */
    public function getIncomingDealConf()
    {
        return $this->incomingDealConf;
    }

    /**
     * Sets a new incomingDealConf
     *
     * Запрос сообщений о подтверждении сделок
     *
     * @param \common\models\sbbolxml\request\IncomingDealConfType $incomingDealConf
     * @return static
     */
    public function setIncomingDealConf(\common\models\sbbolxml\request\IncomingDealConfType $incomingDealConf)
    {
        $this->incomingDealConf = $incomingDealConf;
        return $this;
    }

    /**
     * Gets as dealAns
     *
     * Ответ о подтверждении сделки
     *
     * @return \common\models\sbbolxml\request\DealAnsType
     */
    public function getDealAns()
    {
        return $this->dealAns;
    }

    /**
     * Sets a new dealAns
     *
     * Ответ о подтверждении сделки
     *
     * @param \common\models\sbbolxml\request\DealAnsType $dealAns
     * @return static
     */
    public function setDealAns(\common\models\sbbolxml\request\DealAnsType $dealAns)
    {
        $this->dealAns = $dealAns;
        return $this;
    }

    /**
     * Gets as smsTimeouts
     *
     * Запрос на получение таймаутов действия смс-пароля
     *
     * @return string
     */
    public function getSmsTimeouts()
    {
        return $this->smsTimeouts;
    }

    /**
     * Sets a new smsTimeouts
     *
     * Запрос на получение таймаутов действия смс-пароля
     *
     * @param string $smsTimeouts
     * @return static
     */
    public function setSmsTimeouts($smsTimeouts)
    {
        $this->smsTimeouts = $smsTimeouts;
        return $this;
    }

    /**
     * Gets as payCustDoc
     *
     * Заявка на оплату документа таможни
     *
     * @return \common\models\sbbolxml\request\PayCustDocType
     */
    public function getPayCustDoc()
    {
        return $this->payCustDoc;
    }

    /**
     * Sets a new payCustDoc
     *
     * Заявка на оплату документа таможни
     *
     * @param \common\models\sbbolxml\request\PayCustDocType $payCustDoc
     * @return static
     */
    public function setPayCustDoc(\common\models\sbbolxml\request\PayCustDocType $payCustDoc)
    {
        $this->payCustDoc = $payCustDoc;
        return $this;
    }

    /**
     * Gets as contractAdd
     *
     * Добавление контракта ГОЗ
     *
     * @return \common\models\sbbolxml\request\ContractAddType
     */
    public function getContractAdd()
    {
        return $this->contractAdd;
    }

    /**
     * Sets a new contractAdd
     *
     * Добавление контракта ГОЗ
     *
     * @param \common\models\sbbolxml\request\ContractAddType $contractAdd
     * @return static
     */
    public function setContractAdd(\common\models\sbbolxml\request\ContractAddType $contractAdd)
    {
        $this->contractAdd = $contractAdd;
        return $this;
    }

    /**
     * Gets as actAdd
     *
     * Добавление акта ГОЗ
     *
     * @return \common\models\sbbolxml\request\ActAddType
     */
    public function getActAdd()
    {
        return $this->actAdd;
    }

    /**
     * Sets a new actAdd
     *
     * Добавление акта ГОЗ
     *
     * @param \common\models\sbbolxml\request\ActAddType $actAdd
     * @return static
     */
    public function setActAdd(\common\models\sbbolxml\request\ActAddType $actAdd)
    {
        $this->actAdd = $actAdd;
        return $this;
    }

    /**
     * Adds as role
     *
     * Запрос ролей
     *
     * @return static
     * @param string $role
     */
    public function addToIncomingRoles($role)
    {
        $this->incomingRoles[] = $role;
        return $this;
    }

    /**
     * isset incomingRoles
     *
     * Запрос ролей
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetIncomingRoles($index)
    {
        return isset($this->incomingRoles[$index]);
    }

    /**
     * unset incomingRoles
     *
     * Запрос ролей
     *
     * @param scalar $index
     * @return void
     */
    public function unsetIncomingRoles($index)
    {
        unset($this->incomingRoles[$index]);
    }

    /**
     * Gets as incomingRoles
     *
     * Запрос ролей
     *
     * @return string[]
     */
    public function getIncomingRoles()
    {
        return $this->incomingRoles;
    }

    /**
     * Sets a new incomingRoles
     *
     * Запрос ролей
     *
     * @param string[] $incomingRoles
     * @return static
     */
    public function setIncomingRoles(array $incomingRoles)
    {
        $this->incomingRoles = $incomingRoles;
        return $this;
    }

    /**
     * Adds as userLogin
     *
     * Запрос ролей по пользователям
     *
     * @return static
     * @param string $userLogin
     */
    public function addToIncomingUserRoles($userLogin)
    {
        $this->incomingUserRoles[] = $userLogin;
        return $this;
    }

    /**
     * isset incomingUserRoles
     *
     * Запрос ролей по пользователям
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetIncomingUserRoles($index)
    {
        return isset($this->incomingUserRoles[$index]);
    }

    /**
     * unset incomingUserRoles
     *
     * Запрос ролей по пользователям
     *
     * @param scalar $index
     * @return void
     */
    public function unsetIncomingUserRoles($index)
    {
        unset($this->incomingUserRoles[$index]);
    }

    /**
     * Gets as incomingUserRoles
     *
     * Запрос ролей по пользователям
     *
     * @return string[]
     */
    public function getIncomingUserRoles()
    {
        return $this->incomingUserRoles;
    }

    /**
     * Sets a new incomingUserRoles
     *
     * Запрос ролей по пользователям
     *
     * @param string[] $incomingUserRoles
     * @return static
     */
    public function setIncomingUserRoles(array $incomingUserRoles)
    {
        $this->incomingUserRoles = $incomingUserRoles;
        return $this;
    }

    /**
     * Adds as offerId
     *
     * Запрос предложений
     *
     * @return static
     * @param string $offerId
     */
    public function addToIncomingOffers($offerId)
    {
        $this->incomingOffers[] = $offerId;
        return $this;
    }

    /**
     * isset incomingOffers
     *
     * Запрос предложений
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetIncomingOffers($index)
    {
        return isset($this->incomingOffers[$index]);
    }

    /**
     * unset incomingOffers
     *
     * Запрос предложений
     *
     * @param scalar $index
     * @return void
     */
    public function unsetIncomingOffers($index)
    {
        unset($this->incomingOffers[$index]);
    }

    /**
     * Gets as incomingOffers
     *
     * Запрос предложений
     *
     * @return string[]
     */
    public function getIncomingOffers()
    {
        return $this->incomingOffers;
    }

    /**
     * Sets a new incomingOffers
     *
     * Запрос предложений
     *
     * @param string $incomingOffers
     * @return static
     */
    public function setIncomingOffers(array $incomingOffers)
    {
        $this->incomingOffers = $incomingOffers;
        return $this;
    }

    /**
     * Adds as offerResponse
     *
     * Отклики на персональное предложение
     *
     * @return static
     * @param \common\models\sbbolxml\request\OfferResponseType $offerResponse
     */
    public function addToOfferResponses(\common\models\sbbolxml\request\OfferResponseType $offerResponse)
    {
        $this->offerResponses[] = $offerResponse;
        return $this;
    }

    /**
     * isset offerResponses
     *
     * Отклики на персональное предложение
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetOfferResponses($index)
    {
        return isset($this->offerResponses[$index]);
    }

    /**
     * unset offerResponses
     *
     * Отклики на персональное предложение
     *
     * @param scalar $index
     * @return void
     */
    public function unsetOfferResponses($index)
    {
        unset($this->offerResponses[$index]);
    }

    /**
     * Gets as offerResponses
     *
     * Отклики на персональное предложение
     *
     * @return \common\models\sbbolxml\request\OfferResponseType[]
     */
    public function getOfferResponses()
    {
        return $this->offerResponses;
    }

    /**
     * Sets a new offerResponses
     *
     * Отклики на персональное предложение
     *
     * @param \common\models\sbbolxml\request\OfferResponseType[] $offerResponses
     * @return static
     */
    public function setOfferResponses(array $offerResponses)
    {
        $this->offerResponses = $offerResponses;
        return $this;
    }

    /**
     * Adds as crm
     *
     * Заявки в CRM
     *
     * @return static
     * @param \common\models\sbbolxml\request\CrmType $crm
     */
    public function addToCrms(\common\models\sbbolxml\request\CrmType $crm)
    {
        $this->crms[] = $crm;
        return $this;
    }

    /**
     * isset crms
     *
     * Заявки в CRM
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCrms($index)
    {
        return isset($this->crms[$index]);
    }

    /**
     * unset crms
     *
     * Заявки в CRM
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCrms($index)
    {
        unset($this->crms[$index]);
    }

    /**
     * Gets as crms
     *
     * Заявки в CRM
     *
     * @return \common\models\sbbolxml\request\CrmType[]
     */
    public function getCrms()
    {
        return $this->crms;
    }

    /**
     * Sets a new crms
     *
     * Заявки в CRM
     *
     * @param \common\models\sbbolxml\request\CrmType[] $crms
     * @return static
     */
    public function setCrms(array $crms)
    {
        $this->crms = $crms;
        return $this;
    }

    /**
     * Adds as callBack
     *
     * Заявки на обратный звонок
     *
     * @return static
     * @param \common\models\sbbolxml\request\CallBackType $callBack
     */
    public function addToCallBacks(\common\models\sbbolxml\request\CallBackType $callBack)
    {
        $this->callBacks[] = $callBack;
        return $this;
    }

    /**
     * isset callBacks
     *
     * Заявки на обратный звонок
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetCallBacks($index)
    {
        return isset($this->callBacks[$index]);
    }

    /**
     * unset callBacks
     *
     * Заявки на обратный звонок
     *
     * @param scalar $index
     * @return void
     */
    public function unsetCallBacks($index)
    {
        unset($this->callBacks[$index]);
    }

    /**
     * Gets as callBacks
     *
     * Заявки на обратный звонок
     *
     * @return \common\models\sbbolxml\request\CallBackType[]
     */
    public function getCallBacks()
    {
        return $this->callBacks;
    }

    /**
     * Sets a new callBacks
     *
     * Заявки на обратный звонок
     *
     * @param \common\models\sbbolxml\request\CallBackType[] $callBacks
     * @return static
     */
    public function setCallBacks(array $callBacks)
    {
        $this->callBacks = $callBacks;
        return $this;
    }

    /**
     * Gets as admPayTemplate
     *
     * Шаблоны внесения средств
     *
     * @return \common\models\sbbolxml\request\AdmPaymentTemplateType
     */
    public function getAdmPayTemplate()
    {
        return $this->admPayTemplate;
    }

    /**
     * Sets a new admPayTemplate
     *
     * Шаблоны внесения средств
     *
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateType $admPayTemplate
     * @return static
     */
    public function setAdmPayTemplate(\common\models\sbbolxml\request\AdmPaymentTemplateType $admPayTemplate)
    {
        $this->admPayTemplate = $admPayTemplate;
        return $this;
    }

    /**
     * Gets as admPayTemplateBlock
     *
     * Блокировка\разблокировка Шаблона внесения средств
     *
     * @return \common\models\sbbolxml\request\AdmPaymentTemplateBlockType
     */
    public function getAdmPayTemplateBlock()
    {
        return $this->admPayTemplateBlock;
    }

    /**
     * Sets a new admPayTemplateBlock
     *
     * Блокировка\разблокировка Шаблона внесения средств
     *
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateBlockType $admPayTemplateBlock
     * @return static
     */
    public function setAdmPayTemplateBlock(\common\models\sbbolxml\request\AdmPaymentTemplateBlockType $admPayTemplateBlock)
    {
        $this->admPayTemplateBlock = $admPayTemplateBlock;
        return $this;
    }

    /**
     * Gets as admCashierDel
     *
     * Запрос на удаление сущности «Вноситель средств»
     *
     * @return \common\models\sbbolxml\request\RequestType\AdmCashierDelAType
     */
    public function getAdmCashierDel()
    {
        return $this->admCashierDel;
    }

    /**
     * Sets a new admCashierDel
     *
     * Запрос на удаление сущности «Вноситель средств»
     *
     * @param \common\models\sbbolxml\request\RequestType\AdmCashierDelAType $admCashierDel
     * @return static
     */
    public function setAdmCashierDel(\common\models\sbbolxml\request\RequestType\AdmCashierDelAType $admCashierDel)
    {
        $this->admCashierDel = $admCashierDel;
        return $this;
    }

    /**
     * Gets as admPayTemplateDel
     *
     * Удаление документа вносители средств
     *
     * @return \common\models\sbbolxml\request\AdmPaymentTemplateDelType
     */
    public function getAdmPayTemplateDel()
    {
        return $this->admPayTemplateDel;
    }

    /**
     * Sets a new admPayTemplateDel
     *
     * Удаление документа вносители средств
     *
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateDelType $admPayTemplateDel
     * @return static
     */
    public function setAdmPayTemplateDel(\common\models\sbbolxml\request\AdmPaymentTemplateDelType $admPayTemplateDel)
    {
        $this->admPayTemplateDel = $admPayTemplateDel;
        return $this;
    }

    /**
     * Gets as admCashierAdd
     *
     * Вносители средств
     *
     * @return \common\models\sbbolxml\request\AdmCashierType
     */
    public function getAdmCashierAdd()
    {
        return $this->admCashierAdd;
    }

    /**
     * Sets a new admCashierAdd
     *
     * Вносители средств
     *
     * @param \common\models\sbbolxml\request\AdmCashierType $admCashierAdd
     * @return static
     */
    public function setAdmCashierAdd(\common\models\sbbolxml\request\AdmCashierType $admCashierAdd)
    {
        $this->admCashierAdd = $admCashierAdd;
        return $this;
    }

    /**
     * Gets as admPayTemplateList
     *
     * Запрос списка документов вносители средств
     *
     * @return \common\models\sbbolxml\request\AdmPaymentTemplateListType
     */
    public function getAdmPayTemplateList()
    {
        return $this->admPayTemplateList;
    }

    /**
     * Sets a new admPayTemplateList
     *
     * Запрос списка документов вносители средств
     *
     * @param \common\models\sbbolxml\request\AdmPaymentTemplateListType $admPayTemplateList
     * @return static
     */
    public function setAdmPayTemplateList(\common\models\sbbolxml\request\AdmPaymentTemplateListType $admPayTemplateList)
    {
        $this->admPayTemplateList = $admPayTemplateList;
        return $this;
    }

    /**
     * Gets as admCashierList
     *
     * Запрос списка документов вносители средств
     *
     * @return \common\models\sbbolxml\request\AdmCashierListType
     */
    public function getAdmCashierList()
    {
        return $this->admCashierList;
    }

    /**
     * Sets a new admCashierList
     *
     * Запрос списка документов вносители средств
     *
     * @param \common\models\sbbolxml\request\AdmCashierListType $admCashierList
     * @return static
     */
    public function setAdmCashierList(\common\models\sbbolxml\request\AdmCashierListType $admCashierList)
    {
        $this->admCashierList = $admCashierList;
        return $this;
    }

    /**
     * Gets as admOperationList
     *
     * Запрос списка операций внесения средств
     *
     * @return \common\models\sbbolxml\request\AdmOperationListType
     */
    public function getAdmOperationList()
    {
        return $this->admOperationList;
    }

    /**
     * Sets a new admOperationList
     *
     * Запрос списка операций внесения средств
     *
     * @param \common\models\sbbolxml\request\AdmOperationListType $admOperationList
     * @return static
     */
    public function setAdmOperationList(\common\models\sbbolxml\request\AdmOperationListType $admOperationList)
    {
        $this->admOperationList = $admOperationList;
        return $this;
    }

    /**
     * Gets as admGenSMSPass
     *
     * Запрос на генерацию пароля «Вносителю средств»
     *
     * @return \common\models\sbbolxml\request\AdmGenSMSPassType
     */
    public function getAdmGenSMSPass()
    {
        return $this->admGenSMSPass;
    }

    /**
     * Sets a new admGenSMSPass
     *
     * Запрос на генерацию пароля «Вносителю средств»
     *
     * @param \common\models\sbbolxml\request\AdmGenSMSPassType $admGenSMSPass
     * @return static
     */
    public function setAdmGenSMSPass(\common\models\sbbolxml\request\AdmGenSMSPassType $admGenSMSPass)
    {
        $this->admGenSMSPass = $admGenSMSPass;
        return $this;
    }

    /**
     * Gets as admCashierGetLogin
     *
     * Запрос логина «Вносителя средств»
     *
     * @return \common\models\sbbolxml\request\AdmCashierGetLoginType
     */
    public function getAdmCashierGetLogin()
    {
        return $this->admCashierGetLogin;
    }

    /**
     * Sets a new admCashierGetLogin
     *
     * Запрос логина «Вносителя средств»
     *
     * @param \common\models\sbbolxml\request\AdmCashierGetLoginType $admCashierGetLogin
     * @return static
     */
    public function setAdmCashierGetLogin(\common\models\sbbolxml\request\AdmCashierGetLoginType $admCashierGetLogin)
    {
        $this->admCashierGetLogin = $admCashierGetLogin;
        return $this;
    }

    /**
     * Gets as auditLetter
     *
     * Письмо с данными аудита
     *
     * @return \common\models\sbbolxml\request\AuditLetterType
     */
    public function getAuditLetter()
    {
        return $this->auditLetter;
    }

    /**
     * Sets a new auditLetter
     *
     * Письмо с данными аудита
     *
     * @param \common\models\sbbolxml\request\AuditLetterType $auditLetter
     * @return static
     */
    public function setAuditLetter(\common\models\sbbolxml\request\AuditLetterType $auditLetter)
    {
        $this->auditLetter = $auditLetter;
        return $this;
    }

    /**
     * Gets as earlyRecall
     *
     * Заявление на отзыв вклада (депозита)
     *
     * @return \common\models\sbbolxml\request\EarlyRecallDataType
     */
    public function getEarlyRecall()
    {
        return $this->earlyRecall;
    }

    /**
     * Sets a new earlyRecall
     *
     * Заявление на отзыв вклада (депозита)
     *
     * @param \common\models\sbbolxml\request\EarlyRecallDataType $earlyRecall
     * @return static
     */
    public function setEarlyRecall(\common\models\sbbolxml\request\EarlyRecallDataType $earlyRecall)
    {
        $this->earlyRecall = $earlyRecall;
        return $this;
    }

    /**
     * Gets as changeAccDetails
     *
     * Заявление на изменение реквизитов расчетного счета
     *
     * @return \common\models\sbbolxml\request\ChangeAccDetailsDataType
     */
    public function getChangeAccDetails()
    {
        return $this->changeAccDetails;
    }

    /**
     * Sets a new changeAccDetails
     *
     * Заявление на изменение реквизитов расчетного счета
     *
     * @param \common\models\sbbolxml\request\ChangeAccDetailsDataType $changeAccDetails
     * @return static
     */
    public function setChangeAccDetails(\common\models\sbbolxml\request\ChangeAccDetailsDataType $changeAccDetails)
    {
        $this->changeAccDetails = $changeAccDetails;
        return $this;
    }

    /**
     * Gets as cardDeposits
     *
     * Запрос списка карточек депозитов организации клиента
     *
     * @return \common\models\sbbolxml\request\CardDepositsType
     */
    public function getCardDeposits()
    {
        return $this->cardDeposits;
    }

    /**
     * Sets a new cardDeposits
     *
     * Запрос списка карточек депозитов организации клиента
     *
     * @param \common\models\sbbolxml\request\CardDepositsType $cardDeposits
     * @return static
     */
    public function setCardDeposits(\common\models\sbbolxml\request\CardDepositsType $cardDeposits)
    {
        $this->cardDeposits = $cardDeposits;
        return $this;
    }

    /**
     * Gets as appForDepositNew
     *
     * Запрос отправки заявления на пролонгацию вклада (депозита)
     *
     * @return \common\models\sbbolxml\request\AppForDepositType
     */
    public function getAppForDepositNew()
    {
        return $this->appForDepositNew;
    }

    /**
     * Sets a new appForDepositNew
     *
     * Запрос отправки заявления на пролонгацию вклада (депозита)
     *
     * @param \common\models\sbbolxml\request\AppForDepositType $appForDepositNew
     * @return static
     */
    public function setAppForDepositNew(\common\models\sbbolxml\request\AppForDepositType $appForDepositNew)
    {
        $this->appForDepositNew = $appForDepositNew;
        return $this;
    }

    /**
     * Gets as permBalanceNew
     *
     * Запрос отправки заявления на пролонгацию вклада (депозита)
     *
     * @return \common\models\sbbolxml\request\PermBalanceDataType
     */
    public function getPermBalanceNew()
    {
        return $this->permBalanceNew;
    }

    /**
     * Sets a new permBalanceNew
     *
     * Запрос отправки заявления на пролонгацию вклада (депозита)
     *
     * @param \common\models\sbbolxml\request\PermBalanceDataType $permBalanceNew
     * @return static
     */
    public function setPermBalanceNew(\common\models\sbbolxml\request\PermBalanceDataType $permBalanceNew)
    {
        $this->permBalanceNew = $permBalanceNew;
        return $this;
    }

    /**
     * Gets as cardPermBalance
     *
     * Запрос списка договоров НСО
     *
     * @return \common\models\sbbolxml\request\CardPermBalanceType
     */
    public function getCardPermBalance()
    {
        return $this->cardPermBalance;
    }

    /**
     * Sets a new cardPermBalance
     *
     * Запрос списка договоров НСО
     *
     * @param \common\models\sbbolxml\request\CardPermBalanceType $cardPermBalance
     * @return static
     */
    public function setCardPermBalance(\common\models\sbbolxml\request\CardPermBalanceType $cardPermBalance)
    {
        $this->cardPermBalance = $cardPermBalance;
        return $this;
    }

    /**
     * Gets as genAuthSMSSign
     *
     * Запрос на генерацию SMS с кодом подтверждения сброса пароля Администратору СББ
     *
     * @return string
     */
    public function getGenAuthSMSSign()
    {
        return $this->genAuthSMSSign;
    }

    /**
     * Sets a new genAuthSMSSign
     *
     * Запрос на генерацию SMS с кодом подтверждения сброса пароля Администратору СББ
     *
     * @param string $genAuthSMSSign
     * @return static
     */
    public function setGenAuthSMSSign($genAuthSMSSign)
    {
        $this->genAuthSMSSign = $genAuthSMSSign;
        return $this;
    }

    /**
     * Gets as verifyAuthSMSSign
     *
     * Отправка кода подтверждения сброса пароля Администратору СББ, полученного по SMS, на проверку
     *
     * @return \common\models\sbbolxml\request\VerifyAuthSMSSignType
     */
    public function getVerifyAuthSMSSign()
    {
        return $this->verifyAuthSMSSign;
    }

    /**
     * Sets a new verifyAuthSMSSign
     *
     * Отправка кода подтверждения сброса пароля Администратору СББ, полученного по SMS, на проверку
     *
     * @param \common\models\sbbolxml\request\VerifyAuthSMSSignType $verifyAuthSMSSign
     * @return static
     */
    public function setVerifyAuthSMSSign(\common\models\sbbolxml\request\VerifyAuthSMSSignType $verifyAuthSMSSign)
    {
        $this->verifyAuthSMSSign = $verifyAuthSMSSign;
        return $this;
    }

    /**
     * Gets as audit
     *
     * Информация для фиксирования в журнале аудита СББОЛ
     *
     * @return \common\models\sbbolxml\request\AuditType
     */
    public function getAudit()
    {
        return $this->audit;
    }

    /**
     * Sets a new audit
     *
     * Информация для фиксирования в журнале аудита СББОЛ
     *
     * @param \common\models\sbbolxml\request\AuditType $audit
     * @return static
     */
    public function setAudit(\common\models\sbbolxml\request\AuditType $audit)
    {
        $this->audit = $audit;
        return $this;
    }

    /**
     * Gets as feesRegistry
     *
     * Реестр задолженностей
     *
     * @return \common\models\sbbolxml\request\FeesRegistryType
     */
    public function getFeesRegistry()
    {
        return $this->feesRegistry;
    }

    /**
     * Sets a new feesRegistry
     *
     * Реестр задолженностей
     *
     * @param \common\models\sbbolxml\request\FeesRegistryType $feesRegistry
     * @return static
     */
    public function setFeesRegistry(\common\models\sbbolxml\request\FeesRegistryType $feesRegistry)
    {
        $this->feesRegistry = $feesRegistry;
        return $this;
    }

    /**
     * Gets as feesRegistryDownload
     *
     * Запрос на скачивание реестра платежей
     *
     * @return \common\models\sbbolxml\request\FeesRegistryDownloadType
     */
    public function getFeesRegistryDownload()
    {
        return $this->feesRegistryDownload;
    }

    /**
     * Sets a new feesRegistryDownload
     *
     * Запрос на скачивание реестра платежей
     *
     * @param \common\models\sbbolxml\request\FeesRegistryDownloadType $feesRegistryDownload
     * @return static
     */
    public function setFeesRegistryDownload(\common\models\sbbolxml\request\FeesRegistryDownloadType $feesRegistryDownload)
    {
        $this->feesRegistryDownload = $feesRegistryDownload;
        return $this;
    }

    /**
     * Gets as feesRegistryAccept
     *
     * Запрос изменения статуса Реестра платежей при выгрузке вложений
     *
     * @return \common\models\sbbolxml\request\FeesRegistryAcceptType
     */
    public function getFeesRegistryAccept()
    {
        return $this->feesRegistryAccept;
    }

    /**
     * Sets a new feesRegistryAccept
     *
     * Запрос изменения статуса Реестра платежей при выгрузке вложений
     *
     * @param \common\models\sbbolxml\request\FeesRegistryAcceptType $feesRegistryAccept
     * @return static
     */
    public function setFeesRegistryAccept(\common\models\sbbolxml\request\FeesRegistryAcceptType $feesRegistryAccept)
    {
        $this->feesRegistryAccept = $feesRegistryAccept;
        return $this;
    }

    /**
     * Adds as essenceLink
     *
     * Запрос на получение ссылок для загрузки файлов в систему 'Большие Файлы'
     *
     * @return static
     * @param \common\models\sbbolxml\request\EssenceLinkType $essenceLink
     */
    public function addToEssenceLinks(\common\models\sbbolxml\request\EssenceLinkType $essenceLink)
    {
        $this->essenceLinks[] = $essenceLink;
        return $this;
    }

    /**
     * isset essenceLinks
     *
     * Запрос на получение ссылок для загрузки файлов в систему 'Большие Файлы'
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetEssenceLinks($index)
    {
        return isset($this->essenceLinks[$index]);
    }

    /**
     * unset essenceLinks
     *
     * Запрос на получение ссылок для загрузки файлов в систему 'Большие Файлы'
     *
     * @param scalar $index
     * @return void
     */
    public function unsetEssenceLinks($index)
    {
        unset($this->essenceLinks[$index]);
    }

    /**
     * Gets as essenceLinks
     *
     * Запрос на получение ссылок для загрузки файлов в систему 'Большие Файлы'
     *
     * @return \common\models\sbbolxml\request\EssenceLinkType[]
     */
    public function getEssenceLinks()
    {
        return $this->essenceLinks;
    }

    /**
     * Sets a new essenceLinks
     *
     * Запрос на получение ссылок для загрузки файлов в систему 'Большие Файлы'
     *
     * @param \common\models\sbbolxml\request\EssenceLinkType[] $essenceLinks
     * @return static
     */
    public function setEssenceLinks(array $essenceLinks)
    {
        $this->essenceLinks = $essenceLinks;
        return $this;
    }

    /**
     * Gets as debtRegistry
     *
     * Реестр задолженностей
     *
     * @return \common\models\sbbolxml\request\DebtRegistryType
     */
    public function getDebtRegistry()
    {
        return $this->debtRegistry;
    }

    /**
     * Sets a new debtRegistry
     *
     * Реестр задолженностей
     *
     * @param \common\models\sbbolxml\request\DebtRegistryType $debtRegistry
     * @return static
     */
    public function setDebtRegistry(\common\models\sbbolxml\request\DebtRegistryType $debtRegistry)
    {
        $this->debtRegistry = $debtRegistry;
        return $this;
    }

    /**
     * Gets as cashOrder
     *
     * Заявка на получение наличных средств
     *
     * @return \common\models\sbbolxml\request\CashOrderType
     */
    public function getCashOrder()
    {
        return $this->cashOrder;
    }

    /**
     * Sets a new cashOrder
     *
     * Заявка на получение наличных средств
     *
     * @param \common\models\sbbolxml\request\CashOrderType $cashOrder
     * @return static
     */
    public function setCashOrder(\common\models\sbbolxml\request\CashOrderType $cashOrder)
    {
        $this->cashOrder = $cashOrder;
        return $this;
    }

    /**
     * Gets as recallCashOrder
     *
     * Отмена заявки на получение наличных средств
     *
     * @return \common\models\sbbolxml\request\RecallCashOrderType
     */
    public function getRecallCashOrder()
    {
        return $this->recallCashOrder;
    }

    /**
     * Sets a new recallCashOrder
     *
     * Отмена заявки на получение наличных средств
     *
     * @param \common\models\sbbolxml\request\RecallCashOrderType $recallCashOrder
     * @return static
     */
    public function setRecallCashOrder(\common\models\sbbolxml\request\RecallCashOrderType $recallCashOrder)
    {
        $this->recallCashOrder = $recallCashOrder;
        return $this;
    }

    /**
     * Gets as currDealCertificate181I
     *
     * Справка о валютных операциях
     *
     * @return \common\models\sbbolxml\request\CurrDealCertificate181IType
     */
    public function getCurrDealCertificate181I()
    {
        return $this->currDealCertificate181I;
    }

    /**
     * Sets a new currDealCertificate181I
     *
     * Справка о валютных операциях
     *
     * @param \common\models\sbbolxml\request\CurrDealCertificate181IType $currDealCertificate181I
     * @return static
     */
    public function setCurrDealCertificate181I(\common\models\sbbolxml\request\CurrDealCertificate181IType $currDealCertificate181I)
    {
        $this->currDealCertificate181I = $currDealCertificate181I;
        return $this;
    }

    /**
     * Gets as intCtrlStatementXML181I
     *
     * Запрос о получении данных по ведомости банковского контроля
     *
     * @return \common\models\sbbolxml\request\IntCtrlStatementXML181IType
     */
    public function getIntCtrlStatementXML181I()
    {
        return $this->intCtrlStatementXML181I;
    }

    /**
     * Sets a new intCtrlStatementXML181I
     *
     * Запрос о получении данных по ведомости банковского контроля
     *
     * @param \common\models\sbbolxml\request\IntCtrlStatementXML181IType $intCtrlStatementXML181I
     * @return static
     */
    public function setIntCtrlStatementXML181I(\common\models\sbbolxml\request\IntCtrlStatementXML181IType $intCtrlStatementXML181I)
    {
        $this->intCtrlStatementXML181I = $intCtrlStatementXML181I;
        return $this;
    }

    /**
     * Gets as bigFilesDownloadPrintFormLink
     *
     * Запрос на создание печатной формы выписки на Банке
     *
     * @return \common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType
     */
    public function getBigFilesDownloadPrintFormLink()
    {
        return $this->bigFilesDownloadPrintFormLink;
    }

    /**
     * Sets a new bigFilesDownloadPrintFormLink
     *
     * Запрос на создание печатной формы выписки на Банке
     *
     * @param \common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType $bigFilesDownloadPrintFormLink
     * @return static
     */
    public function setBigFilesDownloadPrintFormLink(\common\models\sbbolxml\request\BigFilesDownloadPrintFormLinkType $bigFilesDownloadPrintFormLink)
    {
        $this->bigFilesDownloadPrintFormLink = $bigFilesDownloadPrintFormLink;
        return $this;
    }

    /**
     * Gets as genericLetterToBank
     *
     * Письмо свободного формата в банк
     *
     * @return \common\models\sbbolxml\request\GenericLetterToBankType
     */
    public function getGenericLetterToBank()
    {
        return $this->genericLetterToBank;
    }

    /**
     * Sets a new genericLetterToBank
     *
     * Письмо свободного формата в банк
     *
     * @param \common\models\sbbolxml\request\GenericLetterToBankType $genericLetterToBank
     * @return static
     */
    public function setGenericLetterToBank(\common\models\sbbolxml\request\GenericLetterToBankType $genericLetterToBank)
    {
        $this->genericLetterToBank = $genericLetterToBank;
        return $this;
    }

    /**
     * Gets as incrementStatements
     *
     * Запрос на получение выписки с новыми операциями
     *
     * @return \common\models\sbbolxml\request\IncrementStatementsType
     */
    public function getIncrementStatements()
    {
        return $this->incrementStatements;
    }

    /**
     * Sets a new incrementStatements
     *
     * Запрос на получение выписки с новыми операциями
     *
     * @param \common\models\sbbolxml\request\IncrementStatementsType $incrementStatements
     * @return static
     */
    public function setIncrementStatements(\common\models\sbbolxml\request\IncrementStatementsType $incrementStatements)
    {
        $this->incrementStatements = $incrementStatements;
        return $this;
    }

    /**
     * Gets as docDigest
     *
     * Запрос на получение дайджеста по уже отправленному в банк документу.
     *
     * @return \common\models\sbbolxml\request\RequestType\DocDigestAType
     */
    public function getDocDigest()
    {
        return $this->docDigest;
    }

    /**
     * Sets a new docDigest
     *
     * Запрос на получение дайджеста по уже отправленному в банк документу.
     *
     * @param \common\models\sbbolxml\request\RequestType\DocDigestAType $docDigest
     * @return static
     */
    public function setDocDigest(\common\models\sbbolxml\request\RequestType\DocDigestAType $docDigest)
    {
        $this->docDigest = $docDigest;
        return $this;
    }

    /**
     * Adds as sign
     *
     * ЭП клиента
     *
     * @return static
     * @param \common\models\sbbolxml\request\DigitalSignType $sign
     */
    public function addToSign(\common\models\sbbolxml\request\DigitalSignType $sign)
    {
        $this->sign[] = $sign;
        return $this;
    }

    /**
     * isset sign
     *
     * ЭП клиента
     *
     * @param scalar $index
     * @return boolean
     */
    public function issetSign($index)
    {
        return isset($this->sign[$index]);
    }

    /**
     * unset sign
     *
     * ЭП клиента
     *
     * @param scalar $index
     * @return void
     */
    public function unsetSign($index)
    {
        unset($this->sign[$index]);
    }

    /**
     * Gets as sign
     *
     * ЭП клиента
     *
     * @return \common\models\sbbolxml\request\DigitalSignType[]
     */
    public function getSign()
    {
        return $this->sign;
    }

    /**
     * Sets a new sign
     *
     * ЭП клиента
     *
     * @param \common\models\sbbolxml\request\DigitalSignType[] $sign
     * @return static
     */
    public function setSign(array $sign)
    {
        $this->sign = $sign;
        return $this;
    }

    /**
     * Gets as fraud
     *
     * Данные для fraud-мониторинга. Информация об отправившем документ в банк
     *
     * @return \common\models\sbbolxml\request\FraudType
     */
    public function getFraud()
    {
        return $this->fraud;
    }

    /**
     * Sets a new fraud
     *
     * Данные для fraud-мониторинга. Информация об отправившем документ в банк
     *
     * @param \common\models\sbbolxml\request\FraudType $fraud
     * @return static
     */
    public function setFraud(\common\models\sbbolxml\request\FraudType $fraud)
    {
        $this->fraud = $fraud;
        return $this;
    }


}

