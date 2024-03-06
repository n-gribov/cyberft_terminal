<?php

namespace common\components;

class User extends \yii\web\User
{
    public function loginRequired($checkAjax = true, $checkAcceptHeader = true)
    {
        // Костыль для поддержки редиректа в IE
        return parent::loginRequired($checkAjax, false);
    }
}
