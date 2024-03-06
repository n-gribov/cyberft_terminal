<?php

namespace addons\edm\jobs\ExportJob;

use addons\edm\helpers\Converter;
use addons\edm\models\RaiffeisenStatement\RaiffeisenStatementType;
use addons\edm\models\SBBOLStatement\SBBOLStatementType;
use addons\edm\models\Statement\StatementType;
use addons\ISO20022\models\Camt052Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use common\models\cyberxml\CyberXmlDocument;
use Yii;

class StatementToIsoExport extends StatementExport
{
    protected function convertStatement(StatementType $statementTypeModel): string
    {
        $statementUuid = null;
        if ($this->document->type === SBBOLStatementType::TYPE) {
            /** @var SBBOLStatementType $typeModel */
            $typeModel = $this->cyxDocument->getContent()->getTypeModel();
            $statement = $typeModel->response->getStatements()->getStatement()[0];
            $statementUuid = str_replace('-', '', $statement->getDocId());
        } elseif ($this->document->type === RaiffeisenStatementType::TYPE) {
            /** @var RaiffeisenStatementType $typeModel */
            $typeModel = $this->cyxDocument->getContent()->getTypeModel();
            $statement = $typeModel->response->getStatementsRaif()[0];
            $statementUuid = str_replace('-', '', $statement->getExtId());
        }

        if ($this->isIsoDocument() && !$this->isIncrementalExport()) {
            $isoCamtXml = (string)$this->cyxDocument->getContent()->getTypeModel();
            $isoDocumentType = $this->cyxDocument->docType;
        } else {
            $isoDocumentType = $this->getIsoDocumentType();
            $this->log("Will convert statement to ISO $isoDocumentType");
            $isoCamtXml = Converter::statementToIsoCamtXml(
                $statementTypeModel,
                $this->document->dateCreate,
                $this->document->uuidRemote,
                $statementUuid,
                $isoDocumentType
            );
        }

        if ($this->shouldExportCyberXml()) {
            $typeModelClass = $this->getTypeModelClass($isoDocumentType);
            $typeModel = new $typeModelClass();
            $typeModel->loadFromString($isoCamtXml);
            $cyxDoc = CyberXmlDocument::loadTypeModel($typeModel);
            $cyxDoc->senderId = $this->document->sender;
            $cyxDoc->receiverId = $this->document->receiver;
            $cyxDoc->docDate = date('c');
            $cyxDoc->docId = $this->document->uuidRemote;
            return $cyxDoc->saveXML();
        } else {
            return $isoCamtXml;
        }
    }

    protected function getFileName(): string
    {
        if ($this->shouldKeepOriginalFileName()) {
            return $this->cyxDocument->filename;
        }

        return $this->getIsoDocumentType() . '_' . $this->typeModel->statementAccountNumber
            . '_' . date('ymd', strtotime($this->typeModel->statementPeriodStart))
            . '_' . date('ymd', strtotime($this->typeModel->statementPeriodEnd))
            . '_' . date('His')
            . '.xml';
    }

    protected function getExportPath(): string
    {
        $pathParts = [];
        if (!$this->shouldUseGlobalExportSettings()) {
            $pathParts[] = $this->cyxDocument->receiverId;
        }
        if ($this->shouldExportCyberXml()) {
            $pathParts[] = 'transport';
        } else {
            $pathParts[] = 'ISO20022/' . $this->cyxDocument->senderId;
        }

        return implode('/', $pathParts);
    }

    protected function getExportFormat(): string
    {
        return ExportFormat::ISO20022;
    }

    private function shouldExportCyberXml(): bool
    {
        if ($this->shouldUseGlobalExportSettings()) {
            $module = \Yii::$app->getModule('ISO20022');
            return (bool) $module->settings->exportXml;
        } else {
            return (bool) $this->terminalExportSettings->ISO20022ExportXml;
        }
    }

    private function getIsoDocumentType(): string
    {
        if ($this->isIncrementalExport()) {
            return Camt052Type::TYPE;
        } elseif ($this->typeModel->isTodaysStatement()) {
            return Camt054Type::TYPE;
        } else {
            return Camt053Type::TYPE;
        }
    }

    private function getTypeModelClass(string $documentType): string
    {
        $class = Yii::$app->registry->getTypemodelClass($documentType);
        if (!$class) {
            throw new \Exception("Cannot detect type model class for document type $documentType");
        }
        return $class;
    }

    protected function isExportRequired(): bool
    {
        if ($this->account === null) {
            return $this->isIsoDocument();
        }
        return parent::isExportRequired();
    }
}
