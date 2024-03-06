<?php

namespace common\components;

use addons\edm\models\EdmPayerAccount;
use common\models\User;
use Yii;
use yii\base\Component;
use addons\edm\models\EdmPayerAccountUser;
use yii\db\ActiveQuery;

/**
 * Отбор данных согласно доступу к счетам банковского обслуживания
 * Class edmAccountAccess
 * @package common\components
 */
class EdmAccountAccess extends Component
{
    public function query($query, $field, $byNumber = false)
    {
        $this->applyCondition(
            $query,
            $field,
            function (User $user) use ($byNumber): array {
                return $byNumber
                    ? EdmPayerAccountUser::getUserAllowAccountsNumbers($user->id)
                    : EdmPayerAccountUser::getUserAllowAccounts($user->id);
            }
        );

        return $query;
    }

    public function querySignable(ActiveQuery $query, string $accountIdField): void
    {
        $this->applyCondition(
            $query,
            $accountIdField,
            function (User $user): array {
                return EdmPayerAccountUser::find()
                    ->where(['userId' => $user->id, 'canSignDocuments' => true])
                    ->select(['accountId'])
                    ->column();
            }
        );
    }

    public function queryBanksHavingSignableAccounts(ActiveQuery $query, string $bankBikField): void
    {
        $this->applyCondition(
            $query,
            $bankBikField,
            function (User $user): array {
                return EdmPayerAccount::find()
                    ->joinWith('accountUsers as user')
                    ->where(['user.userId' => $user->id, 'user.canSignDocuments' => true])
                    ->select(['bankBik'])
                    ->distinct()
                    ->column();
            }
        );
    }

    public function queryBankTerminalsHavingSignableAccounts(ActiveQuery $query, string $terminalAddressField): void
    {
        $this->applyCondition(
            $query,
            $terminalAddressField,
            function (User $user): array {
                return EdmPayerAccount::find()
                    ->joinWith('accountUsers as user')
                    ->joinWith('bank as bank')
                    ->where(['user.userId' => $user->id, 'user.canSignDocuments' => true])
                    ->select(['bank.terminalId'])
                    ->distinct()
                    ->column();
            }
        );
    }

    private function applyCondition(ActiveQuery $query, string $field, callable $getValues): void
    {
        // Получить модель пользователя из активной сессии
        $user = Yii::$app->user->identity;

        // Главным администраторам доступны все счета (поправка CYB-4493)
        if ($user->role === User::ROLE_ADMIN) {
            return;
        }

        $query->andWhere([$field => $getValues($user)]);
    }
}
