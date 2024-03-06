<?php
namespace common\modules\monitor\checkers;

use common\models\User;
use common\modules\monitor\models\MonitorLogAR;
use Yii;
use yii\helpers\Url;

class FailedLoginChecker extends BaseChecker
{
    protected function checkByTerminalId($terminalId, $data = []) {
        $activeSince = $data['activeSince'];
        $opData = $data['opData'];

        $lastId = !empty($opData['lastId']) ? $opData['lastId'] : 0;

        $query = MonitorLogAR::find()
            ->where([
                'and',
                ['eventCode' => 'FailedLogin'],
                ['entity' => 'user'],
                ['>=', 'dateCreated' , $activeSince],
                ['>', 'id', $lastId]
            ])
            ->orderBy('id ASC');

        // Запрос событий по конкретному
        // терминалу, если он указан
        if ($terminalId) {
            $query->andWhere(['terminalId' => $terminalId]);
        }

        $entries = $query->all();

        if (count($entries) > 0) {
            $userIds = [];
            foreach($entries as $entry) {
                $userIds[$entry->entityId] = true;
                $lastId = $entry->id;
            }

            $users = User::find()->where(['id' => array_keys($userIds)])->all();

            $this->notify($this->getParams($users), $terminalId);
            $this->setOpData('lastId', $lastId, $terminalId);

            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function getParams($data = null)
    {
        return [
            'subject'  => $this->getCodeLabel(),
            'view'     => '@common/modules/monitor/views/mailer/failedLogin',
            'userList' => $data,
            'url'      => Url::to(['/user'], true),
        ];
    }

    /**
     * @inheritdoc
     */
//    public function getFormRowView()
//    {
//        return '@common/modules/monitor/views/checkers/failedLogin';
//    }

    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'Failed login attempts');
    }

    public function attributeLabels()
    {
        return [
            'actualTime'   => Yii::t('monitor/events', 'Time period'),
        ];
    }


}