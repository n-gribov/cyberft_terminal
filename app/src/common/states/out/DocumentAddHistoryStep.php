<?php

namespace common\states\out;

use common\components\storage\StoredFile;
use common\events\user\CreateDocumentEvent;
use common\events\user\SignDocumentEvent;
use common\models\cyberxml\CyberXmlDocumentHistoryEvent;
use common\modules\monitor\models\MonitorLogAR;
use common\states\BaseDocumentStep;

class DocumentAddHistoryStep extends BaseDocumentStep
{
    public $name = 'addHistory';

    public function run()
    {
        $logRecords = $this->findMonitorLogRecords();
        if (count($logRecords) === 0) {
            return true;
        }

        $this->state->cyxDoc->historyEvents = array_map(
            function (MonitorLogAR $logRecord) {
                return CyberXmlDocumentHistoryEvent::fromMonitorLogRecord($logRecord);
            },
            $logRecords
        );
        $this->state->cyxDoc->pushHistoryEvents();

        $storedFile = StoredFile::findOne($this->state->document->actualStoredFileId);
        $fileInfo = $storedFile->updateData($this->state->cyxDoc->getDom()->saveXML());
        if (empty($fileInfo)) {
            $this->logError("Failed to update stored file for document {$this->state->document->id}");
            return false;
        }

        return true;
    }

    /**
     * @return MonitorLogAR[]
     */
    private function findMonitorLogRecords(): array
    {
        return MonitorLogAR::find()
            ->with('user')
            ->where([
                'entity' => 'document',
                'entityId' => $this->state->document->id,
                'eventCode' => [(new SignDocumentEvent())->getCode(), (new CreateDocumentEvent())->getCode()],
            ])
            ->andWhere(['!=', 'initiatorType', MonitorLogAR::INITIATOR_TYPE_SYSTEM])
            ->orderBy(['id' => SORT_ASC])
            ->all();
    }
}
