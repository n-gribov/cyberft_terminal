<?php

namespace common\modules\transport\jobs;

use common\base\RegularJob;
use common\models\StateAR;
use common\helpers\Address;
use Yii;

/**
 * Regular process Input Output job class
 *
 * @package modules
 * @subpackage transport
 */
class RegularProcessIO extends RegularJob
{
    public function perform()
    {
        $notRunning = [];
        $invalidStompSettings = [];

        foreach (Yii::$app->terminals->addresses as $terminalId) {
            // Check terminal running status
            $terminalId = Address::fixSender($terminalId);

            $now = date_create();
            $nowTimestamp = date_timestamp_get($now);

            // Проверка статуса настроек STOMP
            $appSettings = Yii::$app->settings->get('app', $terminalId);

            if (!isset($appSettings->stomp) || empty($appSettings->stomp) || !isset($appSettings->stomp[$terminalId])) {
                $invalidStompSettings[] = $terminalId;
                continue;
            }

            $stompSettings = $appSettings->stomp[$terminalId];

            if (empty($stompSettings['login'] ?? null) || empty($stompSettings['password'] ?? null)) {
                $invalidStompSettings[] = $terminalId;
                continue;
            }

            if (Yii::$app->terminals->isRunning($terminalId)) {
                $stoppedStates = StateAR::find()
                        ->where(['terminalId' => $terminalId])
                        ->andWhere(['<', 'dateRetry', date('Y-m-d H:i:s', $nowTimestamp)])
                        ->all();

                foreach ($stoppedStates as $state) {
                    $state->delete();
                    $params = [
                        'terminalId' => $state->terminalId,
                        'documentId' => $state->documentId,
                        'status' => $state->status,
                        'data' => unserialize($state->data)
                    ];
                    Yii::$app->resque->enqueue(
                        'common\jobs\StateJob',
                        [
                            'stateClass' => $state->code,
                            'params' => serialize($params)
                        ]
                    );
                }
            } else {
                $notRunning[] = $terminalId;
            }
        }

        if (!empty($notRunning)) {
            $this->log('Terminals are not running: ' . join(', ', $notRunning), false, 'regular-jobs');
        }

        if (!empty($invalidStompSettings)) {
            $this->log('Invalid STOMP settings: ' . join(', ', $invalidStompSettings), false, 'regular-jobs');
        }
    }
}
