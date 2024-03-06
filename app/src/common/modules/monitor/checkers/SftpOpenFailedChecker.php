<?php
namespace common\modules\monitor\checkers;

use common\modules\monitor\models\MonitorLogAR;
use Yii;

class SftpOpenFailedChecker extends BaseChecker
{
    protected function checkByTerminalId($terminalId, $data = [])
    {
        $activeSince = $data['activeSince'];
        $opData = $data['opData'];

        $lastId = !empty($opData['lastId']) ? $opData['lastId'] : 0;

        $events = MonitorLogAR::find()
            ->where(['eventCode' => 'SftpOpenFailed'])
            ->andWhere(['>=', 'dateCreated' , $activeSince])
            ->andWhere(['>', 'id' , $lastId])
            ->orderBy(['id' => SORT_ASC])
            ->all();

        if (count($events) > 0) {
            $this->notify($this->getParams(count($events)), $terminalId);
            $lastEvent = array_pop($events);
            $this->setOpData('lastId', $lastEvent->id, $terminalId);
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getParams($count = 0)
    {
        return [
            'subject' => $this->getCodeLabel(),
            'view' => '@common/modules/monitor/views/mailer/sftpOpenFailed',
            'count' => $count,
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCodeLabel() {
        return Yii::t('monitor/events', 'Failed to open SFTP resource');
    }

}