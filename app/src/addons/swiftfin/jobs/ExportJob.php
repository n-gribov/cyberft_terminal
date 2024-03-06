<?php

namespace addons\swiftfin\jobs;

use addons\swiftfin\helpers\SwiftfinHelper;
use addons\swiftfin\models\containers\swift\SwaPackage;
use addons\swiftfin\models\containers\swift\SwtContainer;
use addons\swiftfin\settings\SwiftfinSettings;
use addons\swiftfin\SwiftfinModule;
use common\base\DocumentJob;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\modules\api\ApiModule;
use common\settings\AppSettings;
use common\settings\ExportSettings;
use Resque_Job_DontPerform;
use Yii;

/**
 * Export swift job class
 *
 * @package addons
 * @subpackage swiftfin
 *
 * @property boolean $revertHeaders Revert headers
 */
class ExportJob extends DocumentJob
{
    /**
     * @var boolean $revertHeaders Revert headers
     */
    public $revertHeaders = true;

    /**
     * @inheritdoc
     */
    public function setUp()
    {
        $this->_logCategory = 'SwiftFin';
        $this->_module = Yii::$app->getModule('swiftfin');

        parent::setUp();

        // Проверяем, разрешено ли проводить экспорт
        if (!SwiftfinModule::$exportIsActive) {
            throw new Resque_Job_DontPerform('Swiftfin export is disabled');
        }
    }

    /**
     * @inheritdoc
     */
    public function perform()
    {
        if (!$this->isExportRequired()) {
            return;
        }

        $exportResource = $this->getExportResource();
        if (!$exportResource) {
            $this->log('Cannot get export resource', true);
            return;
        }

        $typeModel = $this->_cyxDocument->getContent()->getTypeModel();

        switch($typeModel->sourceFormat) {
            case SwiftfinHelper::FILE_FORMAT_SWIFT:
                // Трансформируем свифтовку
                $this->processSwt($typeModel->source);
                break;
            case SwiftfinHelper::FILE_FORMAT_SWIFT_PACKAGE:
                $this->processSwa($typeModel->source);
                break;
        }

        if (empty($typeModel->sourceData)) {
            $this->_document->updateStatus(Document::STATUS_NOT_EXPORTED, 'Export');
            $this->log("Error exporting document {$this->_documentId}: empty sourceData", true);
        }

        $exportResult = $exportResource->putData($typeModel->sourceData, SwiftfinModule::$exportExtension); // File name will be generated
        if (!$exportResult) {
            $this->log("Failed to export document {$this->_documentId}", true);
            $this->_document->updateStatus(Document::STATUS_NOT_EXPORTED, 'Export');
        } else {
            $this->log("Document is exported to {$exportResult['path']}");
            $this->_document->updateStatus(Document::STATUS_EXPORTED, 'Export');
            
            ApiModule::addToExportQueueIfRequired($this->_document->uuidRemote, $exportResult['path'], $this->_document->receiver);
        }
    }

    /**
     * @param SwtContainer $swt
     * @return bool
     */
    protected function processSwt(SwtContainer &$swt)
    {
        if ($this->revertHeaders) {
            $swt->direction = SwtContainer::DIRECTION_OUT;
        }

        if ($this->_document) {
            $swt->inputSequenceNumber = DocumentHelper::getDayUniqueCount('mir');
            $swt->outputSequenceNumber = DocumentHelper::getDayUniqueCount('mor');
            $swt->setInputDateTime($this->_document->dateCreate);
        }

        $swt->updateMessageFields();
    }

    /**
     * @param SwaPackage $swa
     * @return bool
     */
    protected function processSwa(SwaPackage &$swa)
    {
        foreach ($swa->swtDocuments as $swt) {
            $this->processSwt($swt);
        }
    }

    private function isExportRequired(): bool
    {
        if ($this->shouldUseGlobalExportSettings()) {
            /** @var SwiftfinSettings $swiftFinSettings */
            $swiftFinSettings = Yii::$app->settings->get(SwiftfinModule::SETTINGS_CODE);
            return (bool)$swiftFinSettings->exportIsActive;
        } else {
            /** @var ExportSettings $terminalExportSettings */
            $terminalExportSettings = Yii::$app->settings->get('export', $this->getTerminalAddress());
            return (bool)$terminalExportSettings->useSwiftfinFormat;
        }
    }

    private function getExportResource()
    {
        return $this->shouldUseGlobalExportSettings()
            ? Yii::$app->registry->getExportResource(SwiftfinModule::SERVICE_ID, SwiftfinModule::RESOURCE_EXPORT_SWIFT)
            : Yii::$app->registry->getTerminalExportResource(SwiftfinModule::SERVICE_ID, $this->getTerminalAddress(), SwiftfinModule::RESOURCE_EXPORT_SWIFT);
    }

    private function shouldUseGlobalExportSettings(): bool
    {
        /** @var AppSettings $terminalSettings */
        $terminalSettings = Yii::$app->settings->get('app', $this->getTerminalAddress());
        return (bool) $terminalSettings->useGlobalExportSettings;
    }

    private function getTerminalAddress()
    {
        return $this->_cyxDocument->receiverId;
    }
}
