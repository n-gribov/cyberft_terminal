<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

class ResqueController extends Controller
{
    /**
     * Метод выводит текст подсказки
     */
    public function actionIndex()
    {
        $this->run('/help', ['resque']);
    }

    /**
     * Cleans all resque related keys from Redis base
     */
    public function actionPurge()
    {
        $redis_host = getenv ('REDIS_HOSTNAME');
        system("redis-cli -h $redis_host --raw keys \"resque:*\" | xargs redis-cli -h $redis_host del");
        Controller::stdout("All redis keys deleted\n");
    }

    /**
     * Enqueue resque job
     *
     * @param string $job
     * @param json $args
     */
    public function actionEnqueue($job, $args = null)
    {
        $args = json_decode($args, true);
        $token = Yii::$app->resque->enqueue($job, $args);
        Console::output("Enqueued job {$token}\n");
    }

    /**
     * Perform job
     *
     * @param string $job
     * @param json $args
     */
    public function actionPerform($job, $args = null)
    {
        $args = json_decode($args, true);
        $job = new $job;
        $job->args = $args;
        $job->setUp();
        $job->perform();
    }
}
