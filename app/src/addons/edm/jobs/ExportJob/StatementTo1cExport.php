<?php

namespace addons\edm\jobs\ExportJob;

use addons\edm\helpers\Converter;
use addons\edm\models\Statement\StatementType;
use common\helpers\FileHelper;

class StatementTo1cExport extends StatementExport
{
    protected function convertStatement(StatementType $typeModel): string
    {
        return Converter::statementTo1C($typeModel);
    }

    protected function getFileName(): string
    {
        if ($this->shouldKeepOriginalFileName()) {
            $fileInfo = FileHelper::mb_pathinfo($this->cyxDocument->filename);
            return "{$fileInfo['filename']}.txt";
        }

        return '1C_' . $this->typeModel->statementAccountNumber
            . '_' . date('ymd', strtotime($this->typeModel->statementPeriodStart))
            . '_' . date('ymd', strtotime($this->typeModel->statementPeriodEnd))
            . '_' . date('His')
            . '.txt';
    }

    protected function getExportPath(): string
    {
        $docType = $this->cyxDocument->docType;
        $docProps = $this->module->config->docTypes;
        return ($this->shouldUseGlobalExportSettings() ? '' : "{$this->cyxDocument->receiverId}/")
            . "edm/{$docProps[$docType]['resources']['export']}";
    }

    protected function getExportFormat(): string
    {
        return ExportFormat::ONE_C;
    }
}
