<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

/**
 * Command console controller class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage console
 */
class CommandController extends Controller
{
    /**
     * Метод выводит текст подсказки
     */
    public function actionIndex()
    {
        $this->run('/help', ['command']);
        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Add command
     *
     * @param string $command     Command name
     * @param string $paramName   Command param name
     * @param string $paramValue  Command param value
     * @return integer Return exit code. 0 - success, 1 - error.
     */
    public function actionAdd($command = null, $entity = null, $paramName = null, $paramValue = null)
    {
        if (is_null($command)) {
            echo "Command is empty\n";
            return self::EXIT_CODE_ERROR;
        }

        if (is_null($entity) || is_null($paramName) || is_null($paramValue)) {
            echo 'Empty input params';
            return self::EXIT_CODE_ERROR;
        }

        $params = [
            'code'     => $command,
            'entity'   => $entity,
            'entityId' => $paramValue,
            $paramName => $paramValue,
        ];

        $result = Yii::$app->commandBus->addCommand($command, $params);
        if (!$result) {
            echo "Add command status: error. See information in log\n";
            return self::EXIT_CODE_ERROR;
        }

        echo "Add command status: success.\n";
        return self::EXIT_CODE_NORMAL;
    }

    /**
     * Perform commnad by ID
     *
     * @param integer $commandId Command ID
     * @return intger Return exit code. 0 - success, 1 - error.
     */
    public function actionPerform($commandId = null)
    {
        if (is_null($commandId)){
            echo "Command ID is empty\n";
            return self::EXIT_CODE_NORMAL;
        }

        $result = Yii::$app->commandBus->perform($commandId);
        if (!$result){
            echo "Perform command status: error. See information in log\n";
            return self::EXIT_CODE_ERROR;
        }

        echo "Perform command status: success.\n";
        return self::EXIT_CODE_NORMAL;
    }

}
