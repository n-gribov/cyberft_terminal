<?php

namespace console\controllers;

use Yii;
use common\base\ConsoleController as ConsoleController;


class MonitorController extends ConsoleController {
    /**
     * Help action
     *
     * @return integer Return 0 on success or 1 on error
     */
    public function actionIndex()
    {
        $this->run('/help', ['monitor']);
        return ConsoleController::EXIT_CODE_NORMAL;
    }

    /**
     * Checker check
     *
     * @param string $checkerName Checker name
     * @return integer Return 0 on success or 1 on error
     */
    public function actionCheck($checkerName)
    {
        $checker = Yii::$app->monitoring->getChecker($checkerName);
        var_dump(
            $checker->run()
        );

        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Add event
     *
     * @param string  $event    Event name
     * @param string  $param    Params
     * @param string  $entity   Entity
     * @param integer $entityId Entity ID
     * @return integer Return 0 on success or 1 on error
     */
    public function actionAddEvent($event, $param, $entity = 'NULL', $entityId = 0)
    {
        if(empty($event) || empty($param)){
            echo "Empty input params!\n";
            return self::EXIT_CODE_ERROR;
        }

        var_dump(
            $this->module->log($event, $entity, $entityId, json_decode($param, TRUE))
        );

        return self::EXIT_CODE_NORMAL;
    }
}