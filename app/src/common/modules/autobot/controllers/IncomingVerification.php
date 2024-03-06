<?php

namespace common\modules\autobot\controllers;

use common\modules\certManager\models\CertSearch;
use common\helpers\Currencies;
use common\modules\certManager\models\Cert;
use common\models\CompoundCondition;
use common\helpers\Address;
use common\modules\participant\models\BICDirParticipant;
use Yii;

trait IncomingVerification
{
    /**
     * Получение списка выбора подписантов
     */
    public function actionSignerSelect()
    {
        $signerList = [];
        $query = Cert::find()->all();
        foreach($query as $model) {
            $fingerprint = $model->fingerprint;
            $signerList[$fingerprint] = $model->fullName;
        }

        $logop = Yii::$app->request->post('logop');
        $prefix = Yii::$app->request->post('prefix');
        $cond_id = Yii::$app->request->post('cond_id');
        $cond_data = Yii::$app->request->post('cond_data');

        return Yii::$app->controller->renderPartial(
            '@common/modules/autobot/views/settings/_signerSelect',
            [
                'logop' => $logop,
                'prefix' => $prefix,
                'cond_id' => $cond_id,
                'cond_data' => $cond_data,
                'signerList' => $signerList
            ]
        );
    }

    /**
     * Получение данных по верификации входящих
     */
    protected function verificationData()
    {
        $data = [];

        $sender = Yii::$app->request->get('sender');
        $currency = Yii::$app->request->get('currency');

        // Действие edit
        if ($sender && $currency) {
            $data = $this->verificationDataEdit($sender, $currency);
            $data['template'] = "_verificationEdit";
        } else {
            // Действие index
            $data = $this->verificationDataIndex();
            $data['template'] = "_verificationIndex";
        }

        return $data;
    }

    /**
     * Удаление условия в форме верификации входящих
     */
    public function actionDeleteCondition($id, $sender, $currency)
    {
        $model = CompoundCondition::findOne($id);
        if ($model) {
            // Sanity check: you can delete only specific conditions, not any
            $path = explode('/', $model->searchPath);
            if ($path[0] == 'swiftfin' && $path[1] == $sender && $path[2] == $currency) {
                // seems to be the right condition! more or less!
                // Удалить документ из БД
                $model->delete();
                // Поместить в сессию флаг сообщения об удалении условия
                Yii::$app->session->setFlash('info', Yii::t('app', 'Condition deleted'));
            } else {
                // Поместить в сессию флаг сообщения об ошибке удалении условия
                Yii::$app->session->setFlash('error', Yii::t('app', 'Cannot delete condition - check parameters'));
            }
        } else {
            // Поместить в сессию флаг сообщения об отсутствующем условии
            Yii::$app->session->setFlash('error', Yii::t('app', 'Condition not found'));
        }

        // Перенаправить на страницу индекса
        return $this->redirect([
            'index',
            'tabMode' => 'tabVerificationRule',
            'sender' => $sender,
            'currency' => $currency
        ]);
    }

    /**
     * Сохранение изменений формы верификации
     */
    public function actionVerificationUpdate($sender, $currency)
    {
        $sumsFrom = Yii::$app->request->post('sumFrom');
        $sumsTo = Yii::$app->request->post('sumTo');

        if (!$sumsFrom) {
            $sumsFrom = [];
            $sumsTo = [];
        }

        $isAdded = !empty(Yii::$app->request->post('addRange'));
        if ($isAdded) {
            if (count($sumsTo)) {
                $lastKey = array_pop(array_keys($sumsTo));
                $sumsFrom[] = (int) $sumsTo[$lastKey] + 1;
            } else {
                $sumsFrom[] = 0;
            }
            $sumsTo[] = 0;
        } else {
            // Зарегистрировать событие изменения настроек верифиации входящих сообщений в модуле мониторинга
            Yii::$app->monitoring->extUserLog('ModifyIncomingVerifySettings');
        }

        foreach($sumsFrom as $rangeKey => $fromValue) {
            $fromValue = (int) $fromValue;
            $toValue = (int) $sumsTo[$rangeKey];

            $searchPath = implode(
                '/',
                ['swiftfin', $sender, $currency, $fromValue, $toValue]
            );

            $model = CompoundCondition::findOne($rangeKey);

            if (empty($model)) {
                $model = new CompoundCondition([
                    'serviceId' => 'swiftfin',
                    'type' => 'incomingVerification'
                ]);
            }

            $model->searchPath = $searchPath;

            if (isset(Yii::$app->request->post('f')[$rangeKey])) {
                $conditions = Yii::$app->request->post('f')[$rangeKey];
            } else {
                $conditions = [];
            }

            $model->setConditions($conditions);
            // Сохранить модель в БД
            $model->save();
        }

        // Перенаправить на страницу индекса
        return $this->redirect([
            'index',
            'tabMode' => 'tabVerificationRule',
            'sender' => $sender,
            'currency' => $currency
        ]);
    }

    /**
     * Получение списка сертификатов (верификация входящих)
     */
    protected function getCertList()
    {
        $query = CertSearch::find()->all();
        $senderSelect = [];
        foreach($query as $model) {
            $terminalId = $model->getTerminalId()->getValue();

            // Получение наименования участника
            $truncatedId = Address::truncateAddress($terminalId);
            $participantData = BICDirParticipant::find()->where(['participantBIC' => $truncatedId])->one();

            if ($participantData) {
                $senderSelect[$terminalId] = $participantData->name;
            } else {
                $senderSelect[$terminalId] = $terminalId;
            }
        }
        return $senderSelect;
    }

    /**
     * Обработка действия index для верификации входящих
     */
    protected function verificationDataIndex()
    {
        // Получение списка валют
        $currencySelect = Currencies::getCodeLabels();

        // Получение списка сертификатов
        $senderSelect = $this->getCertList();

        $data['currencySelect'] = $currencySelect;
        $data['senderSelect'] = $senderSelect;

        return $data;
    }

    /**
     * Обработка действия edit для верификации входящих
     */
    protected function verificationDataEdit($sender, $currency)
    {
        $signerList = [];
        $query = Cert::find()->all();
        foreach($query as $model) {
            $fingerprint = $model->fingerprint;
            $signerList[$fingerprint] = empty($model->fullName)
                ? $fingerprint : $model->fullName;
        }

        $searchPath = implode('/', ['swiftfin', $sender, $currency]);

        $condList = CompoundCondition::find()
            ->andWhere(['serviceId' => 'swiftfin'])
            ->andWhere(['type' => 'incomingVerification'])
            ->andWhere(['like', 'searchPath', $searchPath])
            ->all();

        $rangeList = [];
        foreach($condList as $model) {

            $path = explode('/', $model->searchPath);
            $sumFrom = 0;
            $sumTo = 0;
            if (isset($path[3])) {
                $sumFrom = (int) $path[3];
            }
            if (isset($path[4])) {
                $sumTo = (int) $path[4];
            }
            $rangeList[$model->id] = [
                'sumFrom' => $sumFrom,
                'sumTo' => $sumTo,
                'condition' => $model->conditions
            ];
        }

        $data = [
            'sender' => $sender,
            'currency' => $currency,
            'signerList' => $signerList,
            'rangeList' => $rangeList,
        ];

        return $data;
    }
}