<?php

namespace addons\edm\models;

use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * @property int $accountId
 * @property int $userId
 * @property bool $canSignDocuments
 * @property-read EdmPayerAccount $account
 */
class EdmPayerAccountUser extends ActiveRecord
{
    protected static $_userAccountCache = [];

    public static function tableName()
    {
        return 'edmPayersAccountsUsers';
    }

    public function rules()
    {
        return [
            [['userId', 'accountId'], 'required'],
            [['userId', 'accountId'], 'unique', 'targetAttribute' => ['userId', 'accountId']],
            ['canSignDocuments', 'boolean'],
            ['canSignDocuments', 'default', 'value' => false],
        ];
    }

    /**
     * Проверка доступности пользователю счета
     * @param $userId
     * @param $accountId
     * @return boolean
     */
    public static function isUserAllowAccount($userId, $accountId)
    {
        return self::find()
            ->where(['userId' => $userId, 'accountId' => $accountId])
            ->exists();
    }

    public static function userCanSingDocuments(int $userId, int $accountId): bool
    {
        return self::find()
            ->where(['userId' => $userId, 'accountId' => $accountId, 'canSignDocuments' => true])
            ->exists();
    }

    public static function userCanSingDocumentsForBank(int $userId, string $bankBik): bool
    {
        $accountsQuery = EdmPayerAccount::find()
            ->where(['bankBik' => $bankBik])
            ->select('id');
        return self::find()
            ->where(['userId' => $userId, 'accountId' => $accountsQuery, 'canSignDocuments' => true])
            ->exists();
    }

    public static function userCanSingDocumentsForBankTerminal(int $userId, string $terminalAddress): bool
    {
        $banksQuery = DictBank::find()
            ->where(['terminalId' => $terminalAddress])
            ->select('bik');
        $accountsQuery = EdmPayerAccount::find()
            ->where(['bankBik' => $banksQuery])
            ->select('id');
        return self::find()
            ->where(['userId' => $userId, 'accountId' => $accountsQuery, 'canSignDocuments' => true])
            ->exists();
    }

    /**
     * Проверка доступности пользователю счета по его номеру
     */
    public static function isUserAllowAccountByNumber($userId, $accountNumber)
    {
        // Поиск счета по номеру
        $account = EdmPayerAccount::findOne(['number' => $accountNumber]);

        // Счет не найден
        if (!$account) {
            return false;
        }

        // Проверка доступности счета пользователю
        return self::isUserAllowAccount($userId, $account->id);
    }

    /**
     * Получение списка доступных пользователю счетов
     */
    public static function getUserAllowAccounts($userId)
    {
        $accounts = static::getCachedUserAllowedAccounts($userId);

        return ArrayHelper::getColumn($accounts, 'accountId');
    }

    /**
     * Получение номеров счетов, которые доступны пользователю
     * @param $userId
     * @return array
     */
    public static function getUserAllowAccountsNumbers($userId)
    {
        $accounts = static::getCachedUserAllowedAccounts($userId);

        return ArrayHelper::getColumn($accounts, 'account.number');
    }

    protected static function getCachedUserAllowedAccounts($userId)
    {
        if (isset(static::$_userAccountCache[$userId])) {
            return static::$_userAccountCache[$userId];
        }

        $accounts = self::find()
            ->where(['userId' => $userId])
            ->with('account')
            ->asArray()->all();

        static::$_userAccountCache[$userId] = $accounts;

        return $accounts;
    }

    /**
     * Удаление доступности счета для пользователя
     */
    public static function deleteAccountFromUser($userId, $accountId = null)
    {
        if (empty($accountId)) {
            self::deleteAll(['userId' => $userId]);
        } else {
            self::deleteAll(['userId' => $userId, 'accountId' => $accountId]);
        }
    }

    public static function createOrUpdate(int $userId, int $accountId, bool $canSignDocuments): bool
    {
        $record = self::findOne(['userId' => $userId, 'accountId' => $accountId]);
        if ($record === null) {
            $record = new self(['userId' => $userId, 'accountId' => $accountId]);
        }
        $record->canSignDocuments = $canSignDocuments;
        // Сохранить модель в БД
        return $record->save();
    }

    public function getAccount()
    {
        return $this->hasOne(EdmPayerAccount::className(), ['id' => 'accountId']);
    }

}