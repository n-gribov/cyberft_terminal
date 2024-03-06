<?php
namespace common\modules\monitor\checkers;

use common\modules\monitor\models\MonitorLogAR;
use Yii;

class DocumentProcessErrorChecker extends BaseChecker
{
    protected function checkByTerminalId($terminalId, $data = []) {
        $activeSince = $data['activeSince'];
        $opData = $data['opData'];

        $lastId = !empty($opData['lastId']) ? $opData['lastId'] : 0;

        $query = MonitorLogAR::find();
        $query->where(['eventCode' => 'DocumentProcessError']);
        $query->andWhere(['>=', 'dateCreated' , $activeSince]);
        $query->andWhere(['>', 'id' , $lastId]);
        $query->orderBy('id ASC');

        // Запрос событий по конкретному
        // терминалу, если он указан
        if ($terminalId) {
            $query->andWhere(['terminalId' => $terminalId]);
        }

        $events = $query->all();

        if (count($events) > 0) {
            $this->notify($this->getParams($events), $terminalId);
            $lastEvent = array_pop($events);
            $this->setOpData('lastId', $lastEvent->id, $terminalId);
            return true;
        }

        return false;
    }

    public function getAttrubuteLabels()
    {
        return [
            'codeLabel' => Yii::t('monitor/events', 'Event type')
        ];
    }

    public function getParams($events = null)
    {
        return [
            'subject' => $this->getCodeLabel(),
            'view'    => '@common/modules/monitor/views/mailer/documentsProcessError',
            'count'   => count($events),
            'events' => $events
        ];
    }

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'Document processing error');
    }
}