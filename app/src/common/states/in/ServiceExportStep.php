<?php

namespace common\states\in;

use common\document\Document;
use common\modules\api\ApiModule;
use common\modules\transport\models\CFTStatusReportType;
use common\modules\transport\models\StatusReportType;
use common\modules\transport\TransportModule;
use common\settings\AppSettings;
use common\settings\ExportSettings;
use common\states\BaseDocumentStep;
use Yii;

class ServiceExportStep extends BaseDocumentStep
{
    public $name = 'export';

    public function run()
    {
        if (!$this->isExportRequired()) {
            return true;
        }

        $referencedDocumentExists = Document::find()
            ->where(['uuid' => $this->state->document->uuidReference])
            ->exists();
        if (!$referencedDocumentExists) {
            $this->logError('Document with referenced uuid is not found: ' . $this->state->document->uuidReference);
            return false;
        }

        $exportResource = $this->getExportResource();
        if (!$exportResource) {
            $this->logError("Cannot get export resource for {$this->state->document->type} {$this->state->document->uuid}");
            return false;
        }

        $fileName = $this->state->document->type . '_' . $this->state->document->uuid . '.xml';
        $exportResult = $exportResource->putData($this->state->cyxDoc->saveXML(), $fileName);
        if (!$exportResult) {
            $this->logError("Failed to export {$this->state->document->type} {$this->state->document->uuid}");
            $this->state->document->updateStatus(Document::STATUS_NOT_EXPORTED, 'Export');

            return false;
        } else {
            $this->log("Status report is exported to {$exportResult['path']}");
            $this->state->document->updateStatus(Document::STATUS_EXPORTED, 'Export');

            ApiModule::addToExportQueueIfRequired($this->state->document->uuidRemote, $exportResult['path'], $this->state->document->receiver);

            return true;
        }
    }

    private function isExportRequired(): bool
    {
        $type = $this->state->document->type;
        if (!in_array($type, [StatusReportType::TYPE, CFTStatusReportType::TYPE])) {
            return false;
        }
        
        if (ApiModule::isApiEnabled($this->state->document->receiver)) {
            return true;
        }

        /** @var AppSettings $appSettings */
        $appSettings = Yii::$app->settings->get('app');
        if ($this->shouldUseGlobalExportSettings()) {
            return (bool)$appSettings->exportStatusReports;
        } else {
            /** @var ExportSettings $terminalExportSettings */
            $terminalExportSettings = Yii::$app->settings->get('export', $this->getTerminalAddress());
            return (bool)$terminalExportSettings->exportStatusReports;
        }
    }

    private function getExportResource()
    {
        return $this->shouldUseGlobalExportSettings()
            ? Yii::$app->registry->getExportResource(TransportModule::SERVICE_ID)
            : Yii::$app->registry->getTerminalExportResource(TransportModule::SERVICE_ID, $this->getTerminalAddress());
    }

    private function shouldUseGlobalExportSettings(): bool
    {
        /** @var AppSettings $terminalSettings */
        $terminalSettings = Yii::$app->settings->get('app', $this->getTerminalAddress());
        return (bool)$terminalSettings->useGlobalExportSettings;
    }

    private function getTerminalAddress()
    {
        return $this->state->cyxDoc->receiverId;
    }
}
