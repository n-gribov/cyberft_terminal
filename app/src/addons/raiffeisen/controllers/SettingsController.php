<?php

namespace addons\raiffeisen\controllers;

use common\base\BaseServiceController;
use Yii;
use yii\filters\AccessControl;

class SettingsController extends BaseServiceController
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Метод обрабатывает страницу индекса
     */
    public function actionIndex()
    {
        if (!empty(Yii::$app->request->isPost)) {
            $formName = $this->module->settings->formName();

            $attributes = Yii::$app->request->post($formName);
            /**
             * Fix for autotest: при вызове из теста с выключенным чекбоксом в посте не приходит поле совсем,
             * поэтому в setAttributes оно не попадает и не обновляется
             */

            $this->module->settings->setAttributes($attributes);

            // Если настройки успешно сохранены в БД
            if ($this->module->settings->save()) {
                // Поместить в сессию флаг сообщения об успешном сохранении настроек
                Yii::$app->session->setFlash('success', Yii::t('app/fileact', 'Settings saved'));
            } else {
                // Поместить в сессию флаг сообщения об ошибке сохранения настроек
                Yii::$app->session->setFlash('error', Yii::t('app/error', 'Error! Settings not saved!'));
            }
        }

        // Вывести страницу
        return $this->render('index', [
            'settings' => $this->module->settings,
        ]);
    }

}
