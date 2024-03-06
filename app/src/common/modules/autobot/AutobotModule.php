<?php
namespace common\modules\autobot;

use common\modules\autobot\jobs\ProcessAutobotsWaitingForActivationJob;
use common\modules\autobot\models\ParticipantAutobots;
use yii\base\Module;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use Yii;

class AutobotModule extends Module implements BootstrapInterface
{
    private $_autobotKeysData = [];

    public function bootstrap($app)
    {
        if ($app instanceof ConsoleApplication) {
            $app->controllerMap[$this->id] = [
                'class' => 'common\modules\autobot\console\DefaultController',
                'module' => $this,
            ];
        }

        Yii::$app->registry->registerRegularJob(ProcessAutobotsWaitingForActivationJob::class, YII_ENV_DEV ? 60 : 3600);
    }

    /**
     * Получить атрибуты дополнительных автоботов участника
     * @param $participantId
     */
    public function getParticipantAdditionalAutobots($participantId, $terminalId)
    {
        $certAutobot = new ParticipantAutobots();

        return $certAutobot->getAutobotInfo($participantId, $terminalId);
    }


    public function getAutobotKeyData($terminalId)
    {
        if (isset($this->_autobotKeysData[$terminalId])) {
            return $this->_autobotKeysData[$terminalId];
        }

        $autobot = Yii::$app->exchange->findAutobotUsedForSigning($terminalId);

        if (!$autobot) {
            throw new \Exception("Can't get autobot data");
        }

        $terminalData = Yii::$app->exchange->findTerminalData($autobot->controller->terminal->terminalId);

        if (!$autobot) {
            throw new \Exception("Can't get terminalData data");
        }

        $keyData = [
            'privateKey' => $autobot->privateKey,
            'privatePassword' => $terminalData['passwords'][$autobot->id],
            'fingerprint' => $autobot->fingerprint,
        ];

        $this->_autobotKeysData[$terminalId] = $keyData;

        return $keyData;
    }
}