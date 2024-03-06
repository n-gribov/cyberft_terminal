<?php

namespace common\models\form;

use common\commands\BaseCommand;
use common\commands\CommandAcceptAR;
use common\commands\CommandAR;
use Yii;
use yii\base\Model;

/**
 * Command reject form class
 *
 * @author Kirill Ziuzin <k.ziuzin@cyberplat.com>
 *
 * @package core
 * @subpackage model
 *
 * @property integer $commandId  Command ID
 * @preperty string  $reason     Reject reason
 */
class CommandRejectForm extends Model
{
    /**
     * @var integer $commandId Command ID
     */
    public $commandId;

    /**
     * @var string $reason Reject reason
     */
    public $reason;

    /**
     * @var CommandARnd instance
     */
    private $_command;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['commandId', 'reason'], 'required'],
            ['commandId', 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'reason' => Yii::t('app', 'Reject reason'),
        ];
    }

    /**
     * Reject action
     *
     * @return boolean
     */
    public function reject()
    {
        if (!$this->validate()) {
            return false;
        }

        $command = $this->getCommand();
        if (is_null($command)) {
            $this->addError('Get command active record status: error');
            return false;
        }

        $command->status = BaseCommand::STATUS_NOT_ACCEPTED;

        // Сохранить модель в БД
        $isSaved = $command->save();
        if (!$isSaved){
            $this->addError('Save command error');
            return false;
        }

        return $this->saveCommandAccept();
    }

    /**
     * Save command accept
     *
     * @return boolean
     */
    protected function saveCommandAccept()
    {
        $commandAccept               = new CommandAcceptAR();
        $commandAccept->commandId    = $this->commandId;
        $commandAccept->userId       = Yii::$app->user->id;
        $commandAccept->acceptResult = CommandAcceptAR::ACCEPT_RESULT_REJECTED;
        $commandAccept->setDataSerialize(['reason' => $this->reason]);

        // Сохранить модель в БД
        $isSaved = $commandAccept->save();
        if (!$isSaved) {
            $this->addError('Save command accept status: error');
            return false;
        }

        return true;
    }

    /**
     * Get command instance
     *
     * @return CommandAR
     */
    function getCommand()
    {
        if (is_null($this->_command)) {
            $this->_command = CommandAR::findOne($this->commandId);
        }

        return $this->_command;
    }
}