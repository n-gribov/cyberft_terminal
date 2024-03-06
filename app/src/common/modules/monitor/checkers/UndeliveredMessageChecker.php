<?php

namespace common\modules\monitor\checkers;

use common\document\Document;
use common\helpers\DateHelper;
use Yii;
use yii\helpers\Url;

class UndeliveredMessageChecker extends BaseChecker
{
    public $_deliveryDays = 1;

    protected function checkByTerminalId($terminalId, $data = [])
    {
        $activeSince = $data['activeSince'];
        $opData = $data['opData'];

        $lastDocumentId = !empty($opData['lastDocumentId']) ? $opData['lastDocumentId'] : 0;

        $deliveryDays = $this->getDeliveryDays($terminalId);

        $nowDate = (new \DateTime())->format('Y-m-d H:i:s');
        $pastDate = (new \DateTime("-{$deliveryDays} days"))->format('Y-m-d H:i:s');

        $query = Document::find();
        $query->select('id, typeGroup, dateCreate, sender, receiver');
        $query->where(['direction' => Document::DIRECTION_OUT]);
        $query->andWhere(['not in', 'typeGroup', ['service', 'transport', 'participant']]);
        $query->andWhere(['status' => [
            Document::STATUS_REJECTED, Document::STATUS_UNDELIVERED, Document::STATUS_ATTACHMENT_UNDELIVERED
        ]]);
        $query->andWhere(['between', 'dateCreate', $pastDate, $nowDate]);
        $query->andWhere(['>', 'id', $lastDocumentId]);
        $query->asArray();

        if ($terminalId) {
            $query->andWhere(['terminalId' => $terminalId]);
        }

        $documents = $query->all();

        // Если есть недоставленные документы, уведомляем о них
        if (count($documents) > 0) {
            $lastId = array_slice($documents, -1)[0]['id'];
            $this->setOpData('lastDocumentId', $lastId, $terminalId);

            $params = $this->getParams(['documentsData' => $documents, 'deliveryDays' => $deliveryDays]);
            $this->notify($params, $terminalId);
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
            'subject'          => $this->getCodeLabel(),
            'view'             => '@common/modules/monitor/views/mailer/undelivered',
            'documentsData' => $data['documentsData'],
            'deliveryDays' => $data['deliveryDays']
        ];
    }

    /**
     * @inheritdoc
     */
    public function getFormRowView()
    {
        return '@common/modules/monitor/views/checkers/undeliveredMessage';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'deliveryDays' => Yii::t('monitor/events', 'Delivery period in days'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function getCodeLabel()
    {
        return Yii::t('monitor/events', 'Undelivered messages');
    }

    public function getDeliveryDays($terminalId)
    {
        $settings = $this->getSettingsData($terminalId);

        if (isset($settings['deliveryDays'])) {
            return $settings['deliveryDays'];
        } else {
            return $this->_deliveryDays;
        }
    }
}