<?php

namespace common\modules\api;

use common\components\storage\Resource;
use common\helpers\Uuid;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\models\StatusReportType;
use common\modules\transport\TransportModule;
use common\settings\AppSettings;
use Yii;

final class LocalStatusReport
{
    /** @var string */
    private $terminalAddress;

    /** @var string */
    private $referenceUuid;

    /** @var string */
    private $statusCode;

    /** @var string|null */
    private $errorCode;

    /** @var string|null */
    private $errorDescription;

    /** @var CyberXmlDocument|null */
    private $cyxDocument;

    /** @var string|null */
    private $exportedFilePath;

    public function __construct(
        string $terminalAddress,
        string $referenceUuid,
        string $statusCode,
        ?string $errorCode,
        ?string $errorDescription
    ) {
        $this->terminalAddress = $terminalAddress;
        $this->referenceUuid = $referenceUuid;
        $this->statusCode = $statusCode;
        $this->errorCode = $errorCode;
        $this->errorDescription = $errorDescription;
    }

    public function export(): void
    {
        $exportResult = $this->exportResource()->putData(
            $this->cyxDocumentXml(),
            $this->fileName()
        );
        if (!$exportResult) {
            throw new \Exception("Failed to save file to {$this->fileName()}");
        }

        $this->exportedFilePath = $exportResult['path'];
    }

    public function exportedFilePath(): string
    {
        if ($this->exportedFilePath === null) {
            throw new \Exception('Report was not exported');
        }
        return $this->exportedFilePath;
    }

    public function documentUuid(): string
    {
        return $this->cyxDocument()->docId;
    }

    private function exportResource(): Resource
    {
        $resource = $this->shouldUseGlobalExportSettings()
            ? Yii::$app->registry->getExportResource(TransportModule::SERVICE_ID)
            : Yii::$app->registry->getTerminalExportResource(TransportModule::SERVICE_ID, $this->terminalAddress);
        if ($resource === null) {
            throw new \Exception('Cannot get export resource');
        }
        return $resource;
    }

    private function shouldUseGlobalExportSettings(): bool
    {
        /** @var AppSettings $terminalSettings */
        $terminalSettings = Yii::$app->settings->get('app', $this->terminalAddress);
        return (bool)$terminalSettings->useGlobalExportSettings;
    }

    private function cyxDocumentXml(): string
    {
        return $this->cyxDocument()->saveXML();
    }

    private function cyxDocument(): CyberXmlDocument
    {
        if ($this->cyxDocument === null) {
            $this->cyxDocument = CyberXmlDocument::loadTypeModel($this->typeModel());
            $this->cyxDocument->docDate = date('c');
            $this->cyxDocument->docId = (string)Uuid::generate();
            $this->cyxDocument->senderId = $this->terminalAddress;
            $this->cyxDocument->receiverId = $this->terminalAddress;
        }
        return $this->cyxDocument;
    }

    private function typeModel(): StatusReportType
    {
        return new StatusReportType([
            'refDocId' => $this->referenceUuid,
            'statusCode' => $this->statusCode,
            'errorDescription' => $this->errorDescription,
            'errorCode' => $this->errorCode,
        ]);
    }

    private function fileName(): string
    {
        return $this->cyxDocument()->docType . '_' . $this->cyxDocument()->docId . '.xml';
    }
}
