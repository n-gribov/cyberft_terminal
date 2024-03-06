<?php

namespace common\models\raiffeisenxml\response;

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
     * Система-получатель
     *  Если система получатель СББОЛ, то SBBOL_DBO
     *
     * @property string $receiver
     */
    private $receiver = null;

    /**
     * @property \common\models\raiffeisenxml\response\ErrorType[] $errors
     */
    private $errors = null;

    /**
     * Прими квитки о получении документов из УС
     *
     * @property \common\models\raiffeisenxml\response\TicketType[] $tickets
     */
    private $tickets = null;

    /**
     * @property \common\models\raiffeisenxml\response\StatementType[] $statements
     */
    private $statements = null;

    /**
     * @property \common\models\raiffeisenxml\response\StatementTypeRaifType[] $statementsRaif
     */
    private $statementsRaif = null;

    /**
     * Письма из банка
     *
     * @property \common\models\raiffeisenxml\response\LetterFromBankType[] $lettersFromBank
     */
    private $lettersFromBank = null;

    /**
     * Содержит список криптопрофилей для данной организации. Является ответом на запрос информации о
     *  криптопрофилях.
     *
     * @property \common\models\raiffeisenxml\response\CryptoproType[] $cryptopros
     */
    private $cryptopros = null;

    /**
     * Содержит данные сертификатов
     *
     * @property \common\models\raiffeisenxml\response\ResponseType\CertificatesAType\CertificateAType[] $certificates
     */
    private $certificates = null;

    /**
     * @property \common\models\raiffeisenxml\response\OrgInfoType[] $orgsInfo
     */
    private $orgsInfo = null;

    /**
     * @property \common\models\raiffeisenxml\response\DictType[] $dicts
     */
    private $dicts = null;

    /**
     * Результат смены пароля для транспортной учетной записи
     *
     * @property \common\models\raiffeisenxml\response\ResponseType\ChangePasswordResponseAType $changePasswordResponse
     */
    private $changePasswordResponse = null;

    /**
     * @property \common\models\raiffeisenxml\response\ClientAppUpdateType[] $clientAppUpdates
     */
    private $clientAppUpdates = null;

    /**
     * КОРОБКА ПС по К 138
     *
     * @property \common\models\raiffeisenxml\response\DealPassCon138IType $dealPassCon138I
     */
    private $dealPassCon138I = null;

    /**
     * КОРОБКА ПС по КД 138
     *
     * @property \common\models\raiffeisenxml\response\DealPassCred138IType $dealPassCred138I
     */
    private $dealPassCred138I = null;

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
     * Adds as error
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ErrorType $error
     */
    public function addToErrors(\common\models\raiffeisenxml\response\ErrorType $error)
    {
        $this->errors[] = $error;
        return $this;
    }

    /**
     * isset errors
     *
     * @param int|string $index
     * @return bool
     */
    public function issetErrors($index)
    {
        return isset($this->errors[$index]);
    }

    /**
     * unset errors
     *
     * @param int|string $index
     * @return void
     */
    public function unsetErrors($index)
    {
        unset($this->errors[$index]);
    }

    /**
     * Gets as errors
     *
     * @return \common\models\raiffeisenxml\response\ErrorType[]
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Sets a new errors
     *
     * @param \common\models\raiffeisenxml\response\ErrorType[] $errors
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
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\TicketType $ticket
     */
    public function addToTickets(\common\models\raiffeisenxml\response\TicketType $ticket)
    {
        $this->tickets[] = $ticket;
        return $this;
    }

    /**
     * isset tickets
     *
     * Прими квитки о получении документов из УС
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTickets($index)
    {
        return isset($this->tickets[$index]);
    }

    /**
     * unset tickets
     *
     * Прими квитки о получении документов из УС
     *
     * @param int|string $index
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
     *
     * @return \common\models\raiffeisenxml\response\TicketType[]
     */
    public function getTickets()
    {
        return $this->tickets;
    }

    /**
     * Sets a new tickets
     *
     * Прими квитки о получении документов из УС
     *
     * @param \common\models\raiffeisenxml\response\TicketType[] $tickets
     * @return static
     */
    public function setTickets(array $tickets)
    {
        $this->tickets = $tickets;
        return $this;
    }

    /**
     * Adds as statement
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\StatementType $statement
     */
    public function addToStatements(\common\models\raiffeisenxml\response\StatementType $statement)
    {
        $this->statements[] = $statement;
        return $this;
    }

    /**
     * isset statements
     *
     * @param int|string $index
     * @return bool
     */
    public function issetStatements($index)
    {
        return isset($this->statements[$index]);
    }

    /**
     * unset statements
     *
     * @param int|string $index
     * @return void
     */
    public function unsetStatements($index)
    {
        unset($this->statements[$index]);
    }

    /**
     * Gets as statements
     *
     * @return \common\models\raiffeisenxml\response\StatementType[]
     */
    public function getStatements()
    {
        return $this->statements;
    }

    /**
     * Sets a new statements
     *
     * @param \common\models\raiffeisenxml\response\StatementType[] $statements
     * @return static
     */
    public function setStatements(array $statements)
    {
        $this->statements = $statements;
        return $this;
    }

    /**
     * Adds as statementRaif
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\StatementTypeRaifType $statementRaif
     */
    public function addToStatementsRaif(\common\models\raiffeisenxml\response\StatementTypeRaifType $statementRaif)
    {
        $this->statementsRaif[] = $statementRaif;
        return $this;
    }

    /**
     * isset statementsRaif
     *
     * @param int|string $index
     * @return bool
     */
    public function issetStatementsRaif($index)
    {
        return isset($this->statementsRaif[$index]);
    }

    /**
     * unset statementsRaif
     *
     * @param int|string $index
     * @return void
     */
    public function unsetStatementsRaif($index)
    {
        unset($this->statementsRaif[$index]);
    }

    /**
     * Gets as statementsRaif
     *
     * @return \common\models\raiffeisenxml\response\StatementTypeRaifType[]
     */
    public function getStatementsRaif()
    {
        return $this->statementsRaif;
    }

    /**
     * Sets a new statementsRaif
     *
     * @param \common\models\raiffeisenxml\response\StatementTypeRaifType[] $statementsRaif
     * @return static
     */
    public function setStatementsRaif(array $statementsRaif)
    {
        $this->statementsRaif = $statementsRaif;
        return $this;
    }

    /**
     * Adds as letterFromBank
     *
     * Письма из банка
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\LetterFromBankType $letterFromBank
     */
    public function addToLettersFromBank(\common\models\raiffeisenxml\response\LetterFromBankType $letterFromBank)
    {
        $this->lettersFromBank[] = $letterFromBank;
        return $this;
    }

    /**
     * isset lettersFromBank
     *
     * Письма из банка
     *
     * @param int|string $index
     * @return bool
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
     * @param int|string $index
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
     * @return \common\models\raiffeisenxml\response\LetterFromBankType[]
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
     * @param \common\models\raiffeisenxml\response\LetterFromBankType[] $lettersFromBank
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
     * Содержит список криптопрофилей для данной организации. Является ответом на запрос информации о
     *  криптопрофилях.
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\CryptoproType $cryptopro
     */
    public function addToCryptopros(\common\models\raiffeisenxml\response\CryptoproType $cryptopro)
    {
        $this->cryptopros[] = $cryptopro;
        return $this;
    }

    /**
     * isset cryptopros
     *
     * Содержит список криптопрофилей для данной организации. Является ответом на запрос информации о
     *  криптопрофилях.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetCryptopros($index)
    {
        return isset($this->cryptopros[$index]);
    }

    /**
     * unset cryptopros
     *
     * Содержит список криптопрофилей для данной организации. Является ответом на запрос информации о
     *  криптопрофилях.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetCryptopros($index)
    {
        unset($this->cryptopros[$index]);
    }

    /**
     * Gets as cryptopros
     *
     * Содержит список криптопрофилей для данной организации. Является ответом на запрос информации о
     *  криптопрофилях.
     *
     * @return \common\models\raiffeisenxml\response\CryptoproType[]
     */
    public function getCryptopros()
    {
        return $this->cryptopros;
    }

    /**
     * Sets a new cryptopros
     *
     * Содержит список криптопрофилей для данной организации. Является ответом на запрос информации о
     *  криптопрофилях.
     *
     * @param \common\models\raiffeisenxml\response\CryptoproType[] $cryptopros
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
     * @param \common\models\raiffeisenxml\response\ResponseType\CertificatesAType\CertificateAType $certificate
     */
    public function addToCertificates(\common\models\raiffeisenxml\response\ResponseType\CertificatesAType\CertificateAType $certificate)
    {
        $this->certificates[] = $certificate;
        return $this;
    }

    /**
     * isset certificates
     *
     * Содержит данные сертификатов
     *
     * @param int|string $index
     * @return bool
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
     * @param int|string $index
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
     * @return \common\models\raiffeisenxml\response\ResponseType\CertificatesAType\CertificateAType[]
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
     * @param \common\models\raiffeisenxml\response\ResponseType\CertificatesAType\CertificateAType[] $certificates
     * @return static
     */
    public function setCertificates(array $certificates)
    {
        $this->certificates = $certificates;
        return $this;
    }

    /**
     * Adds as orgInfo
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\OrgInfoType $orgInfo
     */
    public function addToOrgsInfo(\common\models\raiffeisenxml\response\OrgInfoType $orgInfo)
    {
        $this->orgsInfo[] = $orgInfo;
        return $this;
    }

    /**
     * isset orgsInfo
     *
     * @param int|string $index
     * @return bool
     */
    public function issetOrgsInfo($index)
    {
        return isset($this->orgsInfo[$index]);
    }

    /**
     * unset orgsInfo
     *
     * @param int|string $index
     * @return void
     */
    public function unsetOrgsInfo($index)
    {
        unset($this->orgsInfo[$index]);
    }

    /**
     * Gets as orgsInfo
     *
     * @return \common\models\raiffeisenxml\response\OrgInfoType[]
     */
    public function getOrgsInfo()
    {
        return $this->orgsInfo;
    }

    /**
     * Sets a new orgsInfo
     *
     * @param \common\models\raiffeisenxml\response\OrgInfoType[] $orgsInfo
     * @return static
     */
    public function setOrgsInfo(array $orgsInfo)
    {
        $this->orgsInfo = $orgsInfo;
        return $this;
    }

    /**
     * Adds as dict
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\DictType $dict
     */
    public function addToDicts(\common\models\raiffeisenxml\response\DictType $dict)
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
     * @return \common\models\raiffeisenxml\response\DictType[]
     */
    public function getDicts()
    {
        return $this->dicts;
    }

    /**
     * Sets a new dicts
     *
     * @param \common\models\raiffeisenxml\response\DictType[] $dicts
     * @return static
     */
    public function setDicts(array $dicts)
    {
        $this->dicts = $dicts;
        return $this;
    }

    /**
     * Gets as changePasswordResponse
     *
     * Результат смены пароля для транспортной учетной записи
     *
     * @return \common\models\raiffeisenxml\response\ResponseType\ChangePasswordResponseAType
     */
    public function getChangePasswordResponse()
    {
        return $this->changePasswordResponse;
    }

    /**
     * Sets a new changePasswordResponse
     *
     * Результат смены пароля для транспортной учетной записи
     *
     * @param \common\models\raiffeisenxml\response\ResponseType\ChangePasswordResponseAType $changePasswordResponse
     * @return static
     */
    public function setChangePasswordResponse(\common\models\raiffeisenxml\response\ResponseType\ChangePasswordResponseAType $changePasswordResponse)
    {
        $this->changePasswordResponse = $changePasswordResponse;
        return $this;
    }

    /**
     * Adds as clientAppUpdates
     *
     * @return static
     * @param \common\models\raiffeisenxml\response\ClientAppUpdateType $clientAppUpdates
     */
    public function addToClientAppUpdates(\common\models\raiffeisenxml\response\ClientAppUpdateType $clientAppUpdates)
    {
        $this->clientAppUpdates[] = $clientAppUpdates;
        return $this;
    }

    /**
     * isset clientAppUpdates
     *
     * @param int|string $index
     * @return bool
     */
    public function issetClientAppUpdates($index)
    {
        return isset($this->clientAppUpdates[$index]);
    }

    /**
     * unset clientAppUpdates
     *
     * @param int|string $index
     * @return void
     */
    public function unsetClientAppUpdates($index)
    {
        unset($this->clientAppUpdates[$index]);
    }

    /**
     * Gets as clientAppUpdates
     *
     * @return \common\models\raiffeisenxml\response\ClientAppUpdateType[]
     */
    public function getClientAppUpdates()
    {
        return $this->clientAppUpdates;
    }

    /**
     * Sets a new clientAppUpdates
     *
     * @param \common\models\raiffeisenxml\response\ClientAppUpdateType[] $clientAppUpdates
     * @return static
     */
    public function setClientAppUpdates(array $clientAppUpdates)
    {
        $this->clientAppUpdates = $clientAppUpdates;
        return $this;
    }

    /**
     * Gets as dealPassCon138I
     *
     * КОРОБКА ПС по К 138
     *
     * @return \common\models\raiffeisenxml\response\DealPassCon138IType
     */
    public function getDealPassCon138I()
    {
        return $this->dealPassCon138I;
    }

    /**
     * Sets a new dealPassCon138I
     *
     * КОРОБКА ПС по К 138
     *
     * @param \common\models\raiffeisenxml\response\DealPassCon138IType $dealPassCon138I
     * @return static
     */
    public function setDealPassCon138I(\common\models\raiffeisenxml\response\DealPassCon138IType $dealPassCon138I)
    {
        $this->dealPassCon138I = $dealPassCon138I;
        return $this;
    }

    /**
     * Gets as dealPassCred138I
     *
     * КОРОБКА ПС по КД 138
     *
     * @return \common\models\raiffeisenxml\response\DealPassCred138IType
     */
    public function getDealPassCred138I()
    {
        return $this->dealPassCred138I;
    }

    /**
     * Sets a new dealPassCred138I
     *
     * КОРОБКА ПС по КД 138
     *
     * @param \common\models\raiffeisenxml\response\DealPassCred138IType $dealPassCred138I
     * @return static
     */
    public function setDealPassCred138I(\common\models\raiffeisenxml\response\DealPassCred138IType $dealPassCred138I)
    {
        $this->dealPassCred138I = $dealPassCred138I;
        return $this;
    }


}

