<?php

use common\document\Document;
use yii\db\Migration;
use yii\db\Query;

class m201103_130253_create_edm_accountBalance extends Migration
{
    private const TABLE_NAME = '{{%edm_accountBalance}}';

    public function safeUp()
    {
        $this->createTable(
            self::TABLE_NAME,
            [
                'accountNumber' => $this->string(32)->notNull()->unique(),
                'balance'       => $this->double()->notNull(),
                'date'          => $this->date()->notNull(),
                'updateDate'    => $this->dateTime()->notNull(),
            ]
        );

        $this->addPrimaryKey('pk_edm_accountBalance', self::TABLE_NAME, 'accountNumber');

        $this->fillTable();
    }

    public function safeDown()
    {
        $this->dropTable(self::TABLE_NAME);
    }

    private function fillTable(): void
    {
        $statementsPeriods = (new Query())
            ->select(['accountNumber', 'max(periodEnd) as periodEnd'])
            ->from('documentExtEdmStatement')
            ->leftJoin('document', 'document.id = documentId')
            ->where(['document.direction' => Document::DIRECTION_IN])
            ->groupBy('accountNumber')
            ->all();

        foreach ($statementsPeriods as $record) {
            list('accountNumber' => $accountNumber, 'periodEnd' => $periodEnd) = $record;
            $statement = (new Query())
                ->select(['closingBalance', 'dateCreate'])
                ->from('documentExtEdmStatement')
                ->leftJoin('document', 'document.id = documentId')
                ->where([
                    'document.direction' => Document::DIRECTION_IN,
                    'accountNumber'      => $accountNumber,
                    'periodEnd'          => $periodEnd,
                ])
                ->orderBy(['documentExtEdmStatement.id' => SORT_DESC])
                ->limit(1)
                ->one();

            if (!$statement) {
                continue;
            }

            $this->addRecord(
                $accountNumber,
                $statement['closingBalance'],
                $periodEnd,
                $statement['dateCreate']
            );
        }
    }

    private function addRecord($accountNumber, $balance, $date, $updateDate): void
    {
        try {
            $columns = [
                'accountNumber' => $accountNumber,
                'balance'       => $balance,
                'date'          => $date,
                'updateDate'    => $updateDate,
            ];
            $this->upsert(
                self::TABLE_NAME,
                $columns,
                $columns
            );
        } catch (\Exception $exception) {
            echo "Failed to save balance for account $accountNumber, caused by: {$exception->getMessage()}\n";
        }
    }
}
