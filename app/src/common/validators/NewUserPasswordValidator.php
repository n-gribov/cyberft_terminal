<?php

namespace common\validators;

use common\models\form\UserPasswordForm;
use common\models\User;
use common\models\UserPreviousPassword;
use common\settings\SecuritySettings;
use Yii;
use yii\helpers\ArrayHelper;
use yii\validators\Validator;

class NewUserPasswordValidator extends Validator
{
    public function validateAttribute($model, $attribute)
    {
        if (!$model->hasErrors()) {
            $this->validatePasswordStrength($model, $attribute);
            $this->validatePasswordUniqueness($model, $attribute);
        }
    }

    private function validatePasswordStrength(UserPasswordForm $model, $attribute): void
    {
        $password = $model->$attribute;
        $security = Yii::$app->settings->get('security');
        if ($security->strongPasswordRequired) {
            if (strlen($password) < 8) {
                $this->addError(
                    $model,
                    $attribute,
                    Yii::t('app/error', 'The password must contain at least {number} characters', ['number' => '8'])
                );
                return;
            }
            if (preg_match('/[\!\@\#\$\%\№\^\&\*\-\_\+\=\;\:\,\.\\' . "'" . '\~\"\[\]\{\}\(\)\?\/\|\\\\]+/', $password) === 0) {
                $this->addError(
                    $model,
                    $attribute,
                    Yii::t('app/error', 'The password must contain at least one special character')
                );
                return;
            }
            if (preg_match("/[A-Z]+/", $password) === 0) {
                $this->addError(
                    $model,
                    $attribute,
                    Yii::t('app/error', 'The password must contain at least one uppercase letter')
                );
                return;
            }
            if (preg_match("/[0-9]+/", $password) === 0) {
                $this->addError(
                    $model,
                    $attribute,
                    Yii::t('app/error', 'The password must contain at least one digit')
                );
                return;
            }
        } else {
            if (strlen($password) < 6) {
                $this->addError(
                    $model,
                    $attribute,
                    Yii::t('app/error','The password must contain at least {number} characters', ['number' => '6'])
                );
                return;
            }
        }
    }

    private function validatePasswordUniqueness(UserPasswordForm $model, string $attribute): void
    {
        $user = $model->getUser();
        if ($user->isNewRecord) {
            return;
        }

        $password = $model->$attribute;

        /** @var SecuritySettings $security */
        $security = Yii::$app->settings->get('security');
        $prevPasswordsHashes = $this->getPreviousPasswordsHashes($user, $security->userPasswordHistoryLength);
        Yii::info($security->userPasswordHistoryLength);
        \Yii::info(\yii\helpers\VarDumper::dumpAsString($prevPasswordsHashes));

        foreach ($prevPasswordsHashes as $hash) {
            $passwordMatches = Yii::$app->security->validatePassword($password, $hash);
            if ($passwordMatches) {
                $this->addError(
                    $model,
                    $attribute,
                    Yii::t('app/error', 'New password must not be the same as the one used previously')
                );
                return;
            }
        }
    }

    private function getPreviousPasswordsHashes(User $user, int $userPasswordHistoryLength): array
    {
        $passwordsHashes = ArrayHelper::getColumn(
            UserPreviousPassword::find()->select('passwordHash')->orderBy(['createDate' => SORT_DESC])->all(),
            'passwordHash'
        );
        array_unshift($passwordsHashes, $user->password_hash);
        return array_slice($passwordsHashes, 0, $userPasswordHistoryLength);
    }
}
