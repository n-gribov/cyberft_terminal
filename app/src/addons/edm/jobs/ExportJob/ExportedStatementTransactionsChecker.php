<?php

namespace addons\edm\jobs\ExportJob;

use addons\edm\models\Statement\StatementType;
use Yii;

class ExportedStatementTransactionsChecker
{
    private const EXPIRY_TIME = 60 * 60 * 25;

    /**
     * @var StatementType
     */
    private $statementTypeModel;
    /**
     * @var string
     */
    private $exportFormat;

    /**
     * @var array|null
     */
    private $exportedTransactionsHashes = null;

    public function __construct(StatementType $statementTypeModel, string $exportFormat)
    {
        $this->statementTypeModel = $statementTypeModel;
        $this->exportFormat = $exportFormat;
    }

    public function isExported(array $transaction): bool
    {
        $transactionHash = $this->createTransactionHash($transaction);
        return in_array(
            $transactionHash,
            $this->getExportedTransactionsHashes()
        );
    }

    public function storeExportedTransactions(array $transactions): void
    {
        $newHashes = array_map(
            function (array $transaction) {
                return $this->createTransactionHash($transaction);
            },
            $transactions
        );

        Yii::$app->redis->setex(
            $this->createStorageKey(),
            self::EXPIRY_TIME,
            serialize(array_merge($this->getExportedTransactionsHashes(), $newHashes))
        );
    }

    public function getExportedTransactionsCount(): int
    {
        return count($this->getExportedTransactionsHashes());
    }

    private function getExportedTransactionsHashes(): array
    {
        if ($this->exportedTransactionsHashes === null) {
            $key = $this->createStorageKey();
            $value = Yii::$app->redis->get($key);
            if ($value) {
                $this->exportedTransactionsHashes = unserialize($value);
            } else {
                $this->exportedTransactionsHashes = [];
            }
        }
        return $this->exportedTransactionsHashes;
    }

    private function createStorageKey(): string
    {
        return implode(
            '_',
            [
                'exported_statement_transactions',
                $this->statementTypeModel->statementPeriodStart,
                $this->statementTypeModel->statementAccountNumber,
                $this->exportFormat
            ]
        );
    }

    private function createTransactionHash(array $transaction): string
    {
        return hash(
            'sha256',
            implode(
                '-',
                [
                    $transaction['Number'],
                    $transaction['PayerAccountNum'],
                    $transaction['PayeeAccountNum'],
                    $transaction['Amount']
                ]
            )
        );
    }
}
