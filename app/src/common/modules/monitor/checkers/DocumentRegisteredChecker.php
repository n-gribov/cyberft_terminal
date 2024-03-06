<?php

namespace common\modules\monitor\checkers;

use common\components\TerminalId;
use common\helpers\DateHelper;
use common\models\Terminal;
use common\modules\monitor\models\MonitorLogAR;
use common\modules\participant\models\BICDirParticipant;
use Exception;

class DocumentRegisteredChecker extends BaseChecker
{
    /**
     * @var integer $actualTime Actual time period in minutes
     */
    private $_actualTime = 30;

    protected function checkByTerminalId($terminalId, $data = [])
    {
        try {
            $activeSince = $data['activeSince'];
            $opData = $data['opData'];

            $lastId = !empty($opData['lastId']) ? $opData['lastId'] : 0;

            $query = MonitorLogAR::find()
                ->where(['eventCode' => 'DocumentRegistered'])
                ->andWhere(['>=', 'dateCreated' , $activeSince])
                ->andWhere(['>', 'id' , $lastId])
                ->andWhere(['>=', 'dateCreated', $this->getActualPeriod()])
                ->orderBy(['id' => SORT_ASC]);

            // Запрос событий по конкретному терминалу, если он указан
            if ($terminalId) {
                $query->andWhere(['terminalId' => $terminalId]);
            }

            $events = $query->all();

            $documents = $this->buildDocumentList($events, $terminalId);

            if (count($documents) > 0) {
                $this->notify($this->getParams(['documents' => $documents, 'participantName' => $this->getParticipantNameByTerminalId($terminalId)]), $terminalId);

                return true;
            }

            return false;
        } catch (Exception $ex) {
            \Yii::warning($ex->getMessage());

            return false;
        }
    }

    private function getParticipantNameByTerminalId($id)
    {
        if (!$id) {
            return null;
        }

        $terminal = Terminal::findOne($id);
        if (!$terminal) {
            return null;
        }

        $terminalId = TerminalId::extract($terminal->terminalId);
        if (!$terminalId) {
            return null;
        }

        $participant = BICDirParticipant::findOne(['participantBIC' => $terminalId->getParticipantId()]);
        return $participant ? $participant->name : null;
    }

    /**
     * @inheritdoc
     */
    public function getParams($data = [])
    {
        return [
            'subject'         => $this->getCodeLabel(),
            'view'            => '@common/modules/monitor/views/mailer/documentsRegistered',
            'documents'       => $data['documents'],
            'participantName' => $data['participantName'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCodeLabel()
    {
        return \Yii::t('monitor/events', 'New received documents');
    }

    /**
     * Get actual period date
     *
     * @return string
     */
    private function getActualPeriod()
    {
        return DateHelper::convert((time() - ($this->_actualTime * 60)), 'datetime');
    }

    /**
     * Build document list
     * @param $events
     * @param $terminalId
     */
    protected function buildDocumentList($events, $terminalId)
    {
        try {
            $documents = [];

            foreach ($events as $value) {
                $params       = unserialize($value['params']);
                $documentType = $params['documentType'];

                // Не учитывать сервисные документы
                if (in_array($documentType, ['service', 'participant'])) {
                    continue;
                }

                if (isset($documents[$documentType])) {
                    $documents[$documentType]++;
                } else {
                    $documents[$documentType] = 1;
                }

                $this->setOpData('lastId', $value->id, $terminalId);

                return $documents;
            }
        } catch (Exception $ex) {
            \Yii::warning($ex->getMessage());
        }
        return [];
    }
}