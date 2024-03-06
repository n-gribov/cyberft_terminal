<?php
namespace common\base\interfaces;

interface ServiceUserExtInterface
{
    /**
     * Проверяет разрешен ли пользователю доступ к сервису
     * @return bool
     */
    public function isAllowedAccess();

    public function hasSettings();
}