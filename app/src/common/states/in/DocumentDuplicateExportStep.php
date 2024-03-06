<?php
namespace common\states\in;

use common\modules\api\ApiModule;
use common\modules\transport\TransportModule;
use common\settings\AppSettings;
use common\settings\ExportSettings;
use common\states\BaseDocumentStep;
use Yii;

class DocumentDuplicateExportStep extends BaseDocumentStep
{
    public $name = 'dupExport';

    public function run()
    {
        if (!$this->isExportRequired()) {
            return true;
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
            return false;
        }

        $this->log("Exported to {$exportResult['path']}");
        ApiModule::addToExportQueueIfRequired($this->state->document->uuidRemote, $exportResult['path'], $this->state->document->receiver);

        // В случае успеха статус документа здесь не меняем на Document::STATUS_EXPORTED,
        // это делается в ExportJob модуля, к которому относится документ.
        return true;
    }

    private function isExportRequired(): bool
    {
        if ($this->state->document->isServiceType()) {
            return false;
        }

        if ($this->state->module->getServiceId() === TransportModule::SERVICE_ID) {
            return (bool)$this->state->module->modeExportDuplicate;
        }

        if ($this->shouldUseGlobalExportSettings()) {
            $moduleSettings = $this->state->module->settings;
            return $moduleSettings && property_exists($moduleSettings, 'exportXml') && $moduleSettings->exportXml;
        } else {
            /** @var ExportSettings $terminalExportSettings */
            $terminalExportSettings = Yii::$app->settings->get('export', $this->getTerminalAddress());
            return (bool)$terminalExportSettings->serviceNeedsExport($this->state->module->getServiceId());
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
        return $this->state->document->receiver;
    }
}
