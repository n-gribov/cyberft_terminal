<?php

namespace common\modules\autobot\controllers;


use common\modules\autobot\models\Autobot;
use common\base\Model;
use common\models\Terminal;
use common\models\UserTerminal;
use common\models\User;
use common\modules\autobot\services\AutobotService;
use yii\helpers\Url;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use Yii;

trait Exchange
{
    /**
     * Метод останавливает обмен всех терминалов с сетью CyberFT
     */
    public function actionStopExchange()
    {
        $componentTerminal = Yii::$app->exchange;

        // Получаем все активные терминалы
        $terminals = Terminal::find()->where(['status' => Terminal::STATUS_ACTIVE]);

        // Получить модель пользователя из активной сессии
        $adminIdentity = Yii::$app->user->identity;

        // Для дополнительного администратора
        // останавливаем только доступные ему терминалы
        if ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN) {

            $allTerminalsList = UserTerminal::getUserTerminalIndexes($adminIdentity->id);

            if (!$allTerminalsList) {
                // Если не доступно ни одного терминала, возвращаем искключение
                throw new ForbiddenHttpException;
            }

            $terminals->andWhere(['id' => $allTerminalsList]);
        }

        $terminals = $terminals->all();

        try {
            // Проверяем терминалы и останавливаем их, если они запущены
            foreach($terminals as $terminal) {
                $terminalId = $terminal->terminalId;
                if ($componentTerminal->isRunning($terminalId)) {
                    $componentTerminal->stop($terminalId);
                }
            }

            // Поместить в сессию флаг сообщения об успешной остановке обмена
            Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'CyberFT exchange stop completed successfully'));
        } catch (\Exception $e) {
            // Поместить в сессию флаг сообщения об ошибке остановки обмена
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'CyberFT exchange stop failed'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    /**
     * Метод останавливает автопроцессы (они же regular jobs)
     */
    public function actionStopJobs()
    {
        // Действие выполняется только если
        // текущий пользователь - главный администратор
        $adminIdentity = Yii::$app->user->identity;

        if ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN) {
            throw new ForbiddenHttpException;
        }

        $settings = Yii::$app->settings->get('app');

        $settings->jobsEnabled = false;

        // Если модель успешно сохранена в БД
        if ($settings->save()) {
            // Поместить в сессию флаг сообщения об успешной остановке автопроцессов
            Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Automatic processes stopped successfully. Wait for the current jobs to finish.'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке остановки автопроцессов
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Automatic processes could not be stopped'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    /**
     * Метод запускает автопроцессы (они же regular jobs)
     */
    public function actionStartJobs()
    {
        // Получить модель пользователя из активной сессии
        $adminIdentity = Yii::$app->user->identity;

        // Действие выполняется только если
        // текущий пользователь - главный администратор
        if ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN) {
            throw new ForbiddenHttpException;
        }

        $settings = Yii::$app->settings->get('app');

        $settings->jobsEnabled = true;

        // Если модель успешно сохранена в БД
        if ($settings->save()) {
            // Поместить в сессию флаг сообщения об успешном запуске автопроцессов
            Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Automatic processes started'));
        } else {
            // Поместить в сессию флаг сообщения об ошибке запуска автопроцессов
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Automatic processes could not be started'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    /**
     * Метод останавливает конкретный терминал
     * @param $terminalId
     */
    public function actionStopTerminal($terminalId)
    {
        // Получение объекта терминала
        $terminal = Terminal::findOne(['terminalId' => $terminalId]);

        // Если терминал не найден
        if (!$terminal) {
            // Поместить в сессию флаг сообщения об отсутствующем терминале
            Yii::$app->session->setFlash('error',
                Yii::t(
                    'app/autobot',
                    '{terminal} not found. Stop failed',
                    ['terminal' => $terminalId]
                )
            );
            // Перенаправить на страницу индекса
            return $this->redirect('index');
        }

        $componentTerminal = Yii::$app->exchange;

        // Если терминал не запущен
        if (!$componentTerminal->isRunning($terminalId)) {
            // Поместить в сессию флаг сообщения о незапущенном терминале
            Yii::$app->session->setFlash('error',
                Yii::t(
                    'app/autobot',
                    '{terminal} not running. Stop failed',
                    ['terminal' => $terminalId]
                )
            );
            // Перенаправить на страницу индекса
            return $this->redirect('index');
        }

        // Во всех остальных случаях останавливаем указанный терминал
        try {
            $componentTerminal->stop($terminalId);

            // Поместить в сессию флаг сообщения об успешной остановке обмена
            Yii::$app->session->setFlash('success', Yii::t('app/autobot', '{terminal} stop completed successfully', ['terminal' => $terminalId]));
        } catch (\Exception $e) {
            // Поместить в сессию флаг сообщения об ошибке остановки обмена
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', '{terminal} exchange stop failed', ['terminal' => $terminalId]));
        }
        // Перенаправить на страницу индекса
        return $this->redirect('index');
    }

    protected function start($terminalId)
    {
        $terminals = Yii::$app->exchange;

        // Подсчитываем число автоботов, участвовавших в запросе
        $count = count(Yii::$app->request->post('Autobot', []));
        // Готовим массив моделей автоботов для загрузки данных из запроса
        $autobots = [];
        for($i = 0; $i < $count; $i++) {
            $newAutobot = new Autobot();
            $newAutobot->setScenario('control');
            $autobots[] = $newAutobot;
        }
        // Грузим полученные данные автоботов
        Model::loadMultiple($autobots, Yii::$app->request->post());
        // Получаем массив паролей, введенных в grid-форму
        $passwords = Yii::$app->request->post('keyPassword');

        // Формируем вектор id автоботов и паролей для последующей проверки
        $keys = [];
        for($i = 0; $i < $count; $i++) {
            $keys[$autobots[$i]->id] = $passwords[$i];
        }
        // Верифицируем все пароли при помощи компонента Terminals
        try {
            if (true === $terminals->verifyKeyPasswords($terminalId, $keys)) {
                $terminals->start($terminalId, $keys);

                // Зарегистрировать событие запуска обмена с CyberFT в модуле мониторинга
                Yii::$app->monitoring->extUserLog('StartAutoprocesses');

                return [
                    'status' => 'success',
                    'body' => Yii::t('app/autobot', "Automatic process started for terminal {terminal}", ['terminal' => $terminalId])
                ];
            }
        } catch(\Exception $ex) {
            return [
                'status' => 'error',
                'body' => $ex->getMessage()
            ];
        }
    }

    /**
     * Метод проверяет наличие
     * ключей контролера у терминала
     * @param $terminalAddress
     * @return bool|int
     */
    protected function hasTerminalActiveControllerKeys($terminalAddress)
    {
        // Получаем основной ключ контролера
        $count = Autobot::find()
            ->joinWith('controller.terminal')
            ->where(
            [
                'terminal.terminalId' => $terminalAddress,
                'primary' => Autobot::AUTOBOT_PRIMARY,
                'autobot.status' => [Autobot::STATUS_ACTIVE, Autobot::STATUS_USED_FOR_SIGNING]
            ]
        )->count();

        return boolval($count);
    }

    public function actionCheckTerminalRunning($terminalId)
    {
        // Включить формат вывода JSON
        Yii::$app->response->format = Response::FORMAT_JSON;
        $response = [];

        $componentTerminal = Yii::$app->exchange;

        if ($componentTerminal->isRunning($terminalId)) {
            $response['status'] = 'error';
            $response['message'] = 'running';
        } else {
            $response['status'] = 'ok';
        }


        return $response;
    }

    public function actionStopTerminalBlockKey($autobotId, $terminalId)
    {
        // Остановка обмена терминала
        $componentTerminal = Yii::$app->exchange;

        $redirectUrl = Url::to([
            '/autobot/terminals/index',
            'id' => Terminal::getIdByAddress($terminalId),
            'tabMode' => 'tabAutobot'
        ]);

        try {
            $componentTerminal->stop($terminalId);
        } catch (\Exception $e) {
            // Поместить в сессию флаг сообщения об ошибке остановки терминала
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', '{terminal} exchange stop failed', ['terminal' => $terminalId]));
            // Перенаправить на страницу перенаправления
            return $this->redirect($redirectUrl);
        }

        // Деактивация ключа контролера
        try {
            $autobot = Autobot::findOne($autobotId);
            $autobotService = new AutobotService();
            $autobotService->block($autobot);
        } catch (\Exception $e) {
            // Поместить в сессию флаг сообщения об ошибке деактивации ключа контролёра
            Yii::$app->session->setFlash('error', Yii::t('app/autobot', 'Failed to block controller key'));
            // Перенаправить на страницу перенаправления
            return $this->redirect($redirectUrl);
        }
        
        // Поместить в сессию флаг сообщения об успешной деактивации ключа контролёра
        Yii::$app->session->setFlash('success', Yii::t('app/autobot', 'Controller key successfully blocked'));

        // Перенаправить на страницу перенаправления
        return $this->redirect($redirectUrl);
    }

}