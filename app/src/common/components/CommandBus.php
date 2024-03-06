<?php

namespace common\components;

use common\commands\CommandAcceptAR;
use common\commands\CommandAR;
use Yii;
use yii\base\Component;
use yii\base\ErrorException;
use yii\base\Model;
use yii\helpers\ArrayHelper;

class CommandBus extends Component
{
    /**
     * Get command or handler class name by code
     *
     * @param string   $code     Command code
     * @param booleand $handler  Command handler flag
     * @return string
     */
    public function commandCodeInflect($code, $handler = false)
    {
        $className = 'common\\';

        $isCommonCommand = (strpos($code, ':') === false);
        if ($isCommonCommand) {
            $className .= 'commands\\' . ucfirst($code) . '\\' . ucfirst($code);
        } else {
            $moduleCommand = explode(':', $code);
            $className .= 'addons\\' . $moduleCommand[0] . 'commands\\'
                    . ucfirst($moduleCommand[1]) . '\\' . ucfirst($moduleCommand[1]);
        }

        $className .= ($handler) ? 'Handler' : 'Command';

        return $className;
    }

    /**
     * Add command
     *
     * @param integer  $userId    Current user id
     * @param string  $code    Command code
     * @param array   $params  Command params
     * @return boolean
     */
    public function addCommand($userId, $code, $params = [])
    {
        $commandClass = $this->commandCodeInflect($code);

        $command = new $commandClass($params);
        if (!$command->validate()) {
            $this->log('Command validation error['.json_encode($command->getErrors()).']');

            return false;
        }

        $commandAR = new CommandAR([
            'userId'       => $userId,
            'code'         => $command->code,
            'entity'       => $command->entity,
            'entityId'     => $command->entityId,
            'acceptsCount' => $command->acceptsCount,
            'args'         => $command->serializeArgs(),
            'status'       => ($command->acceptsCount === 0) ? CommandAR::STATUS_FOR_PERFORM
                    : CommandAR::STATUS_FOR_ACCEPTANCE,
        ]);

        if (!$commandAR->save()) {
            $this->log('Create command error['.json_encode($commandAR->getErrors()).']');

            return false;
        }

        if (CommandAR::STATUS_FOR_PERFORM === $commandAR->status) {
            $this->processCommand($commandAR->id);
        }

        return true;
    }

    /**
     * Get command class instance by command ID
     *
     * @param integer $commandId Command ID
     * @return Model Command class instance
     */
    protected function getCommandById($commandId)
    {
        $commandAR = CommandAR::findOne($commandId);
        if (!$commandAR) {
            $this->log('Get command from DB error. CommandID['.$commandId.']');

            return null;
        }

        $commandClass = $this->commandCodeInflect($commandAR->code);
        $command      = new $commandClass($commandAR->getCommandArgs());

        return $command;
    }

    /**
     * Get list of commands with specific status
     *
     * @param string|array  $status  Command single status or list of command status
     * @param integer       $limit   Count of limit commands in commands list
     * @param string        $order   Order direction
     * @return array Return list of commands active record instance
     */
    public function getCommandByStatus($status, $limit = 10, $order = 'DESC')
    {
        $orderValue = ['dateCreate' => ($order === 'DESC') ? SORT_DESC : SORT_ASC];

        return CommandAR::find()->where($status)->limit($limit)->orderBy($orderValue)->all();
    }

    /**
     * Add command accept
     *
     * @param integer  $commandId   Command ID
     * @param array    $acceptData  Accept data
     * @return boolean
     */
    public function addCommandAccept($commandId, $acceptData = [])
    {
        if (CommandAR::find(['id' => $commandId, 'status' => CommandAR::STATUS_FOR_ACCEPTANCE])) {
            $acceptParams = ArrayHelper::merge($acceptData,
                ['userId' => Yii::$app->user->id, 'commandId' => $commandId]
            );

            $commandAccept = new CommandAcceptAR($acceptParams);
            if (!$commandAccept->validate()) {
                exit;
                $this->log('Validate command accept error. Data[' . json_encode($commandAccept) . ']');
                return false;
            }


            $result = $commandAccept->save();
            if (!$result) {
                $this->log('Add command accept status: error. Info[' . json_encode($commandAccept->getErrors()) . ']');
                return false;
            }

            if ($this->setAcceptCommandStatus($commandId)) {
                $this->processCommand($commandId);
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * Perfom command
     *
     * @param integer $commandId Command ID
     * @return boolean
     */
    public function perform($commandId)
    {
        $command = $this->getCommandById($commandId);
        if (is_null($command)) {
            return false;
        }

        if (!$this->checkCommandAccept($commandId)) {
            return false;
        }

        try {
            $commandHandlerClass = $this->commandCodeInflect($command->code, true);
            $handler             = new $commandHandlerClass();
            $result              = $handler->perform($command);
        } catch (ErrorException $ex) {
            $this->log('Perform command status: error. Details['.$ex->getMessage().']');
            return false;
        }

        $commandAR = CommandAR::findOne($commandId);
        if (is_null($commandAR)) {
            $this->log('Wrong command ID for save result action');

            return false;
        }

        $commandAR->status = ($result === false) ? CommandAR::STATUS_FAILED : CommandAR::STATUS_EXECUTED;
        $commandAR->setResultData($result);

        $saveResult = $commandAR->save();
        if (!$saveResult) {
            $this->log('Save command result status: error['.json_encode($commandAR->getErrors()).']');

            return false;
        }

        return true;
    }

    /**
     * Check command accept
     *
     * @param integer $commandId Command ID
     * @return boolean
     */
    protected function checkCommandAccept($commandId)
    {
        $commandAR = CommandAR::findOne($commandId);
        if (is_null($commandAR)) {
            $this->log('Get command activer record status: error');
            return false;
        }

        $acceptCount    = 0;
        $commandAccepts = $commandAR->getAccepts()->all();
        foreach ($commandAccepts as $value) {
            if ($value->acceptResult === CommandAcceptAR::ACCEPT_RESULT_ACCEPTED) {
                $acceptCount++;
            }
        }

        if ($commandAR->acceptsCount !== $acceptCount) {
            if ($commandAR->acceptsCount === count($commandAccepts)) {
                $this->setCommandNotAcceptStatus($commandId);
            }

            return false;
        }

        return true;
    }

    /**
     * Set accept status for command
     *
     * @param integer $commandId Command ID
     * @return boolean|null
     */
    protected function setAcceptCommandStatus($commandId)
    {
        if (!$this->checkCommandAccept($commandId)) {
            return null;
        }

        $commandAR = CommandAR::findOne($commandId);
        if (is_null($commandAR)) {
            $this->log('Get command activer record status: error');

            return false;
        }

        $commandAR->status = CommandAR::STATUS_FOR_PERFORM;
        if (!$commandAR->save()) {
            $this->log('Set accept command status: error. Info ['.json_encode($commandAR->getErrors()).']');

            return false;
        }

        return true;
    }

    /**
     * Set command not accepted status
     *
     * @param integer $commandId Command ID
     * @return boolean
     */
    protected function setCommandNotAcceptStatus($commandId)
    {
        $commandAR = CommandAR::findOne($commandId);
        if (is_null($commandAR)) {
            $this->log('Get command active record status: error');

            return false;
        }

        $commandAR->status = CommandAR::STATUS_NOT_ACCEPTED;

        return $commandAR->save();
    }

    /**
     * Write message to log
     *
     * @param string $message Log message
     */
    protected function log($message)
    {
        Yii::warning($message);
    }

    public function findCommandId($code, $params = [])
    {
        $params = ArrayHelper::merge(['code' => $code], $params);

        return CommandAR::find()->where($params)->orderBy(['dateCreate' => SORT_DESC])->one();
    }

    public function cancelCommand($id)
    {
        if ($command = CommandAR::findOne(['id' => $id])) {
            $command->status = CommandAR::STATUS_CANCELLED;
            return $command->save(false, ['status']);
        }

        return false;
    }

    private function processCommand($commandId)
    {
        Yii::$app->resque->enqueue('common\jobs\ProcessCommandJob', ['id' => $commandId]);
    }

    public function checkExistCommandAccept($userId, $commandCode, $params = [])
    {
        if ($command = $this->findCommandId($commandCode, $params))
        {
            if (CommandAcceptAR::find()->where([
                'userId' => $userId,
                'commandId' => $command->id,
                'acceptResult' => CommandAcceptAR::ACCEPT_RESULT_ACCEPTED
            ])->one()) {
                return true;
            }
        }

        return false;
    }

    public function isCommandForPerform($entity, $entityId, $commandCode)
    {
        $params = [
            'entity' => $entity,
            'entityId' => $entityId,
        ];

        if ($command = $this->findCommandId($commandCode, $params)) {
            if (CommandAR::STATUS_FOR_PERFORM == $command->status) {
                return true;
            }
        }

        return false;
    }

}