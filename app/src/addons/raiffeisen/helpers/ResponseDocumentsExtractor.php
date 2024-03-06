<?php

namespace addons\raiffeisen\helpers;

use common\models\raiffeisenxml\response\Response;

class ResponseDocumentsExtractor
{
    const DOCUMENTS_TYPES = [
        'Statements',
        'StatementsRaif',
        'Errors',
        'Tickets',
        'ExchangeMessagesFromBank',
        'LettersFromBank',
        'Cryptopros',
        'Certificates',
        'RevocationCertificates',
        'OrgsInfo',
        'OrganizationsInfo',
        'StatusSMS',
        'ClientAppUpdates',
        'FirmwareUpdate',
        'OfflineVersion',
        'Dict',
        'OrgSettings',
        'News',
        'Correspondent',
        'SmsCryptoProfile',
        'PayRequests',
        'ChangedDocs',
        'PayDocsRu',
        'PayDocsCur',
        'SalaryDocs',
        'ListOfEmployees',
        'DocTypeConfigs',
        'ListIntCtrlStatement181I',
        'ListDealPassCon138I',
        'ListDealPassCred138I',
        'ISKRequest',
        'CurrencyNotices',
        'ResponsePart',
        'ChatWithBankMsgs',
        'CurrCourseEntry',
        'LinksToBigFiles',
        'BigFilesStatus',
        'DealConfs',
        'SmsTimeouts',
        'IncomingRoles',
        'UserRoles',
        'Offers',
        'AdmPayTemplates',
        'AdmCashiers',
        'AdmOperations',
        'AdmCashierLogin',
        'CardDeposits',
        'CardPermBalanceList',
        'FeesRegistries',
        'DownloadLinks',
        'UploadLinks',
        'IntCtrlStatementXML181I',
    ];

    private static $documentsAccessorsPaths = [];

    /** @var Response */
    private $response;
    private $documentsTypes = [];

    public function __construct(Response $response)
    {
        $this->response = $response;

        $this->initializeDocumentsAccessorsPaths();
        $this->detectDocumentsTypes();
    }

    public function getDocumentsTypes(): array
    {
        return $this->documentsTypes;
    }

    public function getDocuments(string $documentType): array
    {
        if (!array_key_exists($documentType, static::$documentsAccessorsPaths)) {
            return [];
        }
        $result = static::invokeMethods($this->response, static::$documentsAccessorsPaths[$documentType]) ?: [];
        return is_array($result) ? $result : [$result];
    }

    private function detectDocumentsTypes()
    {
        $types = array_filter(
            array_keys(static::$documentsAccessorsPaths),
            function ($type) {
                $documents = $this->getDocuments($type);
                return !empty($documents);
            }
        );

        $this->documentsTypes = array_values($types);
    }

    private function initializeDocumentsAccessorsPaths()
    {
        foreach (self::DOCUMENTS_TYPES as $key => $value) {
            if (is_numeric($key)) {
                $accessor = "get$value";
                if (!method_exists(Response::class, $accessor)) {
                    continue;
                }
                static::$documentsAccessorsPaths[$value] = [$accessor];
            } else {
                static::$documentsAccessorsPaths[$key] = $value;
            }
        }
    }

    private static function invokeMethods($object, array $methodsNames)
    {
        if ($object === null) {
            throw new \Exception('Got null object');
        }

        $methodName = $methodsNames[0];
        if (!method_exists($object, $methodName)) {
            $className = get_class($object);
            throw new \Exception("Class $className has no method $methodName");
        }

        $result = $object->$methodName();
        $nextMethodsName = array_slice($methodsNames, 1);

        if ($result === null) {
            return null;
        }

        return count($nextMethodsName) === 0 ? $result : static::invokeMethods($result, $nextMethodsName);
    }
}
