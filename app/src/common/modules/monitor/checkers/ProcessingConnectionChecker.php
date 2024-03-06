<?php

namespace common\modules\monitor\checkers;

use common\helpers\DateHelper;
use common\modules\monitor\models\MonitorLogAR;
use Yii;

class ProcessingConnectionChecker extends BaseChecker
{
    /**
     * Count of errors
     */
    const ERRORS_COUNT = 10;

    /**
     * @var integer $checkPeriod Check period in minutes
     */
    public $_checkPeriod = 10;

    protected function checkByTerminalId($terminalId, $data = []) {
        $activeSince = $data['activeSince'];
        $opData = $data['opData'];

        $lastEventId = !empty($opData['lastEventId']) ? $opData['lastEventId'] : 0;

        // Список терминалов,
        // которые не смогли подключиться к процессингу
        $terminals = [];

        $query = MonitorLogAR::find()
            ->with('terminal')
            ->where(['>', 'id', $lastEventId])
            ->andWhere(['eventCode' => ['StompFailed', 'CftcpFailed']])
            ->andWhere(['>=', 'dateCreated' , $activeSince])
            ->andWhere(['>=', 'dateCreated', $this->getActualPeriod()])
            ->orderBy('id ASC');

        // Запрос событий по конкретному
        // терминалу, если он указан
        if ($terminalId) {
            $query->andWhere(['terminalId' => $terminalId]);
        }

        $events = $query->all();

        // Получение списка терминалов,
        // которые не смогли подключиться к процессингу
        foreach($events as $event) {
            if ($event->terminal) {
                $eventTerminalId = $event->terminal->terminalId;

                if (in_array($eventTerminalId, $terminals)) {
                    continue;
                }

                $terminals[] = $eventTerminalId;
            }
        }

        $terminalsList = implode(',', $terminals);

        if (count($events) > self::ERRORS_COUNT) {
            $params['terminalsList'] = $terminalsList;
            $this->notify($this->getParams($params), $terminalId);

            $lastEvent = array_pop($events);
            $this->setOpData('lastEventId', $lastEvent->id, $terminalId);

            return true;
        }

        return false;
    }

    /**
     * Get actual period date
     *
     * @return string
     */
    private function getActualPeriod()
    {
        return DateHelper::convert((time() - $this->_checkPeriod * 60), 'datetime');
    }

    /**
     * @inheritdoc
     */
    public function getParams($data = null)
    {
        $defaults = [
            'subject' => $this->getCodeLabel(),
            'view'    => '@common/modules/monitor/views/mailer/processingConnection',
        ];

        return array_merge($data, $defaults);
    }

    /**
     * @inheritdoc
     */
    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'Processing connection fail');
    }
}