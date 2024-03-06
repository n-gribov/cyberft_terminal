<?php

namespace addons\VTB\jobs\CheckVTBDocumentsStatusJob\StatusUpdateHandler;

class StatementQueryStatusUpdateHandler extends GenericDocumentStatusUpdateHandler
{
    protected function processDocument()
    {
        $statementQuery = $this->getDocumentTypeModel()->document;
        list($statement, $signatureData) = $this->module->getStatementFromVTB($statementQuery);
        if ($statement === null || empty($statement->ACCOUNT)) {
            $this->log('Got empty statement from VTB', true);
            return;
        }

        if ($this->status->isFinal()) {
            $statementIsSent = $this->module->sendStatement($statement, $signatureData, $this->document->sender);
            if (!$statementIsSent) {
                $this->log('Failed to send statement', true);
            }
        }
    }
}
