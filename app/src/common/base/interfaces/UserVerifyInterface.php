<?php
namespace common\base\interfaces;

/**
 * Интерфейс для моделей верификации документов пользователями
 */
interface UserVerifyInterface
{
    /**
     * Тут должна быть описана логика проверки введенных пользователем данных
     * @param $userData Данные из формы
     */
    public function verify($userData);

    /**
     * Возвращает имена тэгов которые необходимо верифицировать
     * @return mixed
     */
    public function getVerifyTags();

    /**
     * Возвращает content model
     * @return mixed
     */
    public function getContentModel();

    /**
     * Возвращает content model
     * @param $contentModel
     * @return mixed
     */
    public function setContentModel($contentModel);
}