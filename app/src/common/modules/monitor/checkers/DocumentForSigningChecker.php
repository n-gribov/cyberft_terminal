<?php

namespace common\modules\monitor\checkers;

use common\document\Document;
use common\models\User;
use common\modules\monitor\models\MonitorLogAR;
use Yii;
use yii\helpers\ArrayHelper;

class DocumentForSigningChecker extends BaseChecker
{
    protected function checkByTerminalId($terminalId, $data = [])
    {
        $settings = $this->getNotificationSettings($terminalId);
        $addressList = $this->getAddressesList($this->getCode(), $settings, true);

        if (empty($addressList)) {
            return false;
        }

        $activeSince = $data['activeSince'];
        $opData = $data['opData'];

        $lastId = !empty($opData['lastId']) ? $opData['lastId'] : 0;

        $query = MonitorLogAR::find()->select('id, entityId');
        $query->where(['eventCode' => 'DocumentForSigning']);
        $query->andWhere(['>', 'id' , $lastId]);
        $query->andWhere(['>=', 'dateCreated' , $activeSince]);

        // Запрос событий по конкретному терминалу, если он указан

        if ($terminalId) {
            $query->andWhere(['terminalId' => $terminalId]);
        }

        $query->orderBy(['id' => SORT_ASC]);
        $events = $query->all();

        // Формирование данных по документам, требующим подписания
        $docIds = ArrayHelper::getColumn($events, 'entityId');
        // Получение документов по событиям
        $documents = Document::find()
            ->select('id, typeGroup, dateCreate, sender, receiver, signaturesRequired, signaturesCount')
            ->where(['id' => $docIds])
            ->orderBy(['id' => SORT_DESC])->all();

        // Формирование массива с данными по документам
        $documentsData = [];

        foreach($documents as $document) {
            $documentsData[] = [
                'id' => $document->id,
                'type' => $document->typeGroup,
                'date' => date('d-m-Y', strtotime($document->dateCreate)),
                'sender' => $document->sender,
                'receiver' => $document->receiver,
                'signaturesRequired' => $document->signaturesRequired,
                'signaturesCount' => $document->signaturesCount,
            ];
        }

        if (!count($documentsData)) {
            return false;
        }

        foreach($addressList as $email => $userData) {
            $documents = [];
            $userSearch = [];
            if (is_array($userData)) {
                if (isset($userData['userId'])) {
                    $userSearch['id'] = $userData['userId'];
                } else {
                    $userSearch['email'] = $email;
                }
            } else {
                $userSearch['email'] = $email;
            }

            $user = empty($userSearch) ? null : User::findOne($userSearch);
            if (!$user) {
                $documents = $documentsData;
            } else {
                foreach($documentsData as $document) {
                    if ($user->signatureNumber == $document['signaturesCount'] + 1) {
                        $documents[] = $document;
                    }
                }
            }

            if (count($documents)) {
                $this->notify($this->getParams($documents), $terminalId, [$email]);
            }
        }

        $lastEvent = array_pop($events);

        $this->setOpData('lastId', $lastEvent->id, $terminalId);

        return true;
    }

    public function getParams($data = [])
    {
        return [
            'subject' => Yii::t('monitor/mailer', 'New unsigned documents'),
            'view'    => '@common/modules/monitor/views/mailer/documentsForSigning',
            'documentsData'   => $data
        ];
    }

    public function getCodeLabel() {
        return Yii::t('monitor/events', 'Documents waiting for signing');
    }

}