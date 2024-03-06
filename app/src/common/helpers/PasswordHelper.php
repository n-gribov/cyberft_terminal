<?php

namespace common\helpers;

class PasswordHelper
{
    public static function generatePassword($minLength = 15, $maxLength = 25, $allChars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_+~/\\`-=[]{}"\'')
    {
        $passwordChars = [];
        $allCharsCount = strlen($allChars);
        $length = random_int($minLength, $maxLength);
        for ($i = 0; $i < $length; ++$i) {
            $passwordChars []= $allChars[random_int(0, $allCharsCount - 1)];
        }
        return implode('', $passwordChars);
    }
}
