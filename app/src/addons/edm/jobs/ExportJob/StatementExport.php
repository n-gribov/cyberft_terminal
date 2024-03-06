<?php

namespace addons\edm\jobs\ExportJob;

use addons\edm\EdmModule;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\Statement\StatementType;
use addons\ISO20022\models\Camt052Type;
use addons\ISO20022\models\Camt053Type;
use addons\ISO20022\models\Camt054Type;
use addons\ISO20022\settings\ISO20022Settings;
use common\document\Document;
use common\helpers\FileHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\settings\AppSettings;
use common\settings\ExportSettings;
use Yii;

abstract class StatementExport
{
    /**
     * @var Document
     */
    protected $document;
    /**
     * @var CyberXmlDocument
     */
    protected $cyxDocument;
    /**
     * @var StatementType
     */
    protected $typeModel;
    /**
     * @var EdmModule
     */
    protected $module;
    /**
     * @var ExportSettings
     */
    protected $terminalExportSettings;
    /**
     * @var callable
     */
    protected $logCallback;
    /**
     * @var ExportedStatementTransactionsChecker
     */
    protected $exportedTransactionChecker;
    /**
     * @var EdmPayerAccount
     */
    protected $account;

    public static function run(
        Document $document,
        CyberXmlDocument $cyxDocument,
        StatementType $typeModel,
        callable $logCallback
    ): ExportResult {
        $instance = new static($document, $cyxDocument, $typeModel, $logCallback);
        return $instance->export();
    }

    protected function __construct(
        Document $document,
        CyberXmlDocument $cyxDocument,
        StatementType $typeModel,
        callable $logCallback
    ) {
        $this->document = $document;
        $this->cyxDocument = $cyxDocument;
        $this->typeModel = $typeModel;
        $this->logCallback = $logCallback;

        $this->account = $this->getAccount();

        $this->module = Yii::$app->getModule(EdmModule::SERVICE_ID);
        $this->terminalExportSettings = Yii::$app->settings->get('export', $cyxDocument->receiverId);
        $this->exportedTransactionChecker = new ExportedStatementTransactionsChecker(
            $typeModel,
            $this->getExportFormat()
        );
    }

    protected function export(): ExportResult
    {
        if (!$this->isExportRequired()) {
            return ExportResult::notRequired();
        }

        $isIncrementalExport = $this->isIncrementalExport();
        $typeModelForExport = $isIncrementalExport
            ? $this->createTypeModelWithoutAlreadyExportedTransactions()
            : $this->typeModel;

        $previouslyExportedTransactionsCount = $this->exportedTransactionChecker->getExportedTransactionsCount();
        if ($isIncrementalExport && count($typeModelForExport->transactions) === 0) {
            $this->log("Statement has no new transactions and will not be exported to {$this->getExportFormat()}");
            $this->logIncrementalExportResults($typeModelForExport, $previouslyExportedTransactionsCount);
            return ExportResult::notRequired();
        }

        $fileContent = $this->convertStatement($typeModelForExport);
        $exportPath = $this->getExportPath();
        $fileName = $this->getFileName();

        $this->log("Will save {$this->getExportFormat()} statement to $exportPath/$fileName");

        $exportResult = FileHelper::exportFileToPath($exportPath, $fileName, $fileContent);
        $isExported = !empty($exportResult);

        if ($isExported && $isIncrementalExport) {
            $this->exportedTransactionChecker->storeExportedTransactions($typeModelForExport->transactions);
            $this->logIncrementalExportResults($typeModelForExport, $previouslyExportedTransactionsCount);
        }

        return $isExported ? ExportResult::exported($exportPath.'/'.$fileName) : ExportResult::failed();
    }

    abstract protected function getExportFormat(): string;

    abstract protected function convertStatement(StatementType $typeModel): string;

    abstract protected function getFileName(): string;

    abstract protected function getExportPath(): string;

    protected function createTypeModelWithoutAlreadyExportedTransactions(): StatementType
    {
        $typeModel = new StatementType();
        $typeModel->loadFromString((string)$this->typeModel); // !!! такой способ клонирования не копирует транзакции
        $typeModel->transactions = array_values(
            array_filter(
                $this->typeModel->getTransactions(),
                function ($transaction) {
                    return !$this->exportedTransactionChecker->isExported($transaction);
                }
            )
        );
        return $typeModel;
    }

    protected function isExportRequired(): bool
    {
        if ($this->account === null) {
            return false;
        }

        $accountExportFormat = $this->typeModel->isTodaysStatement()
            ? $this->account->todaysStatementsExportFormat
            : $this->account->previousDaysStatementsExportFormat;

        return $this->getExportFormat() === $accountExportFormat;
    }

    protected function shouldUseGlobalExportSettings(): bool
    {
        /** @var AppSettings $terminalSettings */
        $terminalSettings = Yii::$app->settings->get('app', $this->cyxDocument->receiverId);
        return (bool)$terminalSettings->useGlobalExportSettings;
    }

    protected function isIncrementalExport(): bool
    {
        return $this->account !== null && $this->account->useIncrementalExportForTodaysStatements && $this->typeModel->isTodaysStatement();
    }

    protected function log(string $message, bool $isWarning = false): void
    {
        call_user_func($this->logCallback, $message, $isWarning);
    }

    protected function getAccount(): ?EdmPayerAccount
    {
        $accountNumber = $this->typeModel->statementAccountNumber;
        return EdmPayerAccount::findOne(['number' => $accountNumber]);
    }

    private function logIncrementalExportResults(StatementType $typeModelForExport, int $previouslyExportedCount): void
    {
        $message = "Account: $typeModelForExport->statementAccountNumber"
            . ", previous: $previouslyExportedCount"
            . ', new: ' . count($typeModelForExport->getTransactions())
            . ', total: ' . count($this->typeModel->getTransactions());
        Yii::info($message, 'incremental-statement-export');
    }

    protected function isIsoDocument(): bool
    {
        return in_array(
            $this->document->type,
            [
                Camt052Type::TYPE,
                Camt053Type::TYPE,
                Camt054Type::TYPE,
            ]
        );
    }

    protected function shouldKeepOriginalFileName(): bool
    {
        if (!$this->isIsoDocument()) {
            return false;
        }

        /** @var ISO20022Settings $isoSettings */
        $isoSettings = Yii::$app->settings->get('ISO20022:ISO20022');
        return $isoSettings->keepOriginalFilename && !empty((string)$this->cyxDocument->filename);
    }
}
