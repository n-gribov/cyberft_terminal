<?php
namespace common\modules\monitor\components;

use common\models\User;
use common\modules\monitor\events\BaseEvent;
use common\modules\monitor\models\MonitorLogAR;
use yii\base\Component;
use yii\base\InvalidCallException;
use Yii;
use common\helpers\UserHelper;

class MonitorComponent extends Component
{
    public static $logIntervals = [
        'transport:SftpOpenFailed' => 600, // 600 seconds
    ];

    public function extUserLog($event, $params = [])
    {
        $user = Yii::$app->user;

        // Определение текущего пользователя
        if (!empty($user)) {
            $userId = $user->id;
        } else {
            throw new \Exception('error');
        }

        // Определяем тип инициатора
        $params['initiatorType'] = UserHelper::getEventInitiatorType($user);
        $params['userId'] = $userId;
        $this->log('user:' . $event, 'user', $userId, $params);
    }

    public function log($eventCode, $entity = '', $entityId = 0, $params = [])
    {
        if (isset(static::$logIntervals[$eventCode])) {
            $lastTime = time() - static::$logIntervals[$eventCode];
            $lastModel = $this->getLastLog($eventCode, ['>', 'dateCreated', $lastTime]);
            if ($lastModel) {
                return $lastModel;
            }
        }

        $event = BaseEvent::getEventObject($eventCode);
        $event->entity   = $entity;
        $event->entityId = $entityId;

        if (isset($params['logLevel'])) {
            $event->logLevel = $params['logLevel'];
        }

        if (isset($params['userId'])) {
            $event->userId = $params['userId'];

            // если указан id пользователя, получаем его ip и текущий терминал
            $event->ip = Yii::$app->getRequest()->getUserIP();

            $user = User::findOne($event->userId);

            if ($user) {
                $event->terminalId = $user->terminalId;
            }
        }

        if (isset($params['terminalId'])) {
            $event->terminalId = $params['terminalId'];
        }

        if (isset($params['initiatorType'])) {
            $event->initiatorType = $params['initiatorType'];
        }

        $event->setAttributes($params);

        if (!$event->validate()) {
            throw new InvalidCallException("Wrong event params {$eventCode}");
        }

        $model = new MonitorLogAR();
        $model->loadEvent($event);

        return $model->save() ? $model : false;
    }

    public function getLastEvent($eventCode, $params = [])
    {
        $log = $this->getLastLog($eventCode, $params);

        return empty($log) ? null : $log->getEvent();
    }

    /**
     * Optimized getLastLog: all additional parameters now passed in $where
     * @param type $eventCode
     * @param type $where 'where' condition in ActiveRecord format
     * @return MonitorLogAR
     */
    public function getLastLog($eventCode, $where = [])
    {
        $parts = MonitorLogAR::parseEventCode($eventCode);
        \Yii::info($parts);
        \Yii::info($where);

        if (empty($parts['componentId'])) {
            unset($parts['componentId']);
        }

        $query = MonitorLogAR::find()->where($parts);
        if (!empty($where)) {
            $query->andWhere($where);
        }

        $log = $query->orderBy(['id' => SORT_DESC])->one();

        return $log;
    }

}