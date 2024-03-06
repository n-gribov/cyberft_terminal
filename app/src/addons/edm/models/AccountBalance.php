<?php

namespace addons\edm\models;

use yii\db\ActiveRecord;

/**
 * @property string $accountNumber
 * @property float $balance
 * @property string $date
 * @property string $updateDate
 */
class AccountBalance extends ActiveRecord
{
    public static function tableName()
    {
        return 'edm_accountBalance';
    }

    public static function primaryKey()
    {
        return ['accountNumber'];
    }

    public static function createOrUpdate(string $accountNumber, float $balance, string $date, string $updateDate): void
    {
        $record = self::findOne($accountNumber);
        if (!$record) {
            $record = new self(['accountNumber' => $accountNumber]);
        }
        if ($record->date <= $date) {
            $record->setAttributes(
                [
                    'balance'    => $balance,
                    'date'       => $date,
                    'updateDate' => $updateDate,
                ],
                false
            );
        }
        // Сохранить модель в БД
        $isSaved = $record->save();
        if (!$isSaved) {
            throw new \Exception("Failed to save balance for account $accountNumber, errors: " . var_export($record->errors, true));
        }
    }
}
