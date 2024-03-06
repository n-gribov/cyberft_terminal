<?php

namespace common\modules\autobot\controllers;

use common\models\Terminal;
use common\models\TerminalRemoteId;
use Yii;
use common\modules\certManager\models\Cert;
use common\modules\participant\models\BICDirParticipant;
use common\helpers\Address;
use yii\web\MethodNotAllowedHttpException;

trait TerminalsRemoteIds
{
    /**
     * Получение списка терминалов-получателей
     * для взаимодействия с терминалом-отправителем
     * @param $terminal
     * @return array
     */
    protected function getTerminalsReceivers($terminal)
    {
        $terminalsReceivers = [];
        $existedReceivers = [];

        // Проверка текущих кодов для терминала
        $remoteIds = $terminal->remoteIds;

        foreach($remoteIds as $remoteId) {
            if ($remoteId->terminalReceiver) {
                $existedReceivers[] = $remoteId->terminalReceiver;
            }
        }

        // Если получателей нет, но список кодов не пустой,
        // считаем, что один код добавлен для всех получателей
        if(empty($existedReceivers) && !empty($remoteIds)) {
            return $terminalsReceivers;
        }

        $certs = Cert::getAutobotCerts();

        foreach($certs as $cert) {
            $terminalId = (string) $cert->terminalId;

            // Если код для получателя уже добавлен, пропускаем его
            if (in_array($terminalId, $existedReceivers)) {
                continue;
            }

            $fixedAddress = Address::truncateAddress($terminalId);

            $participant = BICDirParticipant::findOne(['participantBIC' => $fixedAddress]);

            if ($participant) {
                $terminalTitle = $terminalId . ' (' . $participant->name . ')';
            } else {
                $terminalTitle = $terminalId;
            }

            $terminalsReceivers[$terminalId] = $terminalTitle;
        }

        return $terminalsReceivers;
    }

    public function actionTerminalAddRemoteId()
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException;
        }

        $terminalId = Yii::$app->request->post('terminalId');
        $receiver = Yii::$app->request->post('receiver');
        $remoteId = Yii::$app->request->post('remoteId');

        if (empty($terminalId) || empty($receiver) || empty($remoteId)) {
            throw new \InvalidArgumentException;
        }

        // Запись кода
        $terminalRemoteId = new TerminalRemoteId;
        $terminalRemoteId->terminalId = $terminalId;
        $terminalRemoteId->terminalReceiver = $receiver;
        $terminalRemoteId->remoteId = $remoteId;

        if ($terminalRemoteId->save()) {
            // Обновление отображение
            $html = $this->renderRemoteIds($terminalId);
            return $html;
        } else {
            throw new \InvalidArgumentException;
        }
    }

    public function actionTerminalDeleteRemoteId()
    {
        if (!Yii::$app->request->isAjax) {
            throw new MethodNotAllowedHttpException;
        }

        $terminal = Yii::$app->request->post('terminal');
        $receiver = Yii::$app->request->post('receiver');

        if (empty($terminal)) {
            throw new \InvalidArgumentException;
        }

        if (empty($receiver)) {
            $receiver = null;
        }

        $terminalRemoteId = TerminalRemoteId::findOne(['terminalId' => $terminal, 'terminalReceiver' => $receiver]);

        if ($terminalRemoteId && $terminalRemoteId->delete()) {
            // Обновление отображение
            $html = $this->renderRemoteIds($terminal);
            return $html;
        } else {
            throw new \InvalidArgumentException;
        }
    }

    protected function renderRemoteIds($terminalId)
    {
        $terminal = Terminal::findOne($terminalId);

        if (!$terminal) {
            throw new \InvalidArgumentException;
        }

        $data['model'] = $terminal;
        $data['remoteIds'] = $terminal->getRemoteIds();
        $data['terminalsReceivers'] = $this->getTerminalsReceivers($terminal);

        return $this->renderAjax('/terminals/_terminalRemoteIds', compact('data'));
    }
}
