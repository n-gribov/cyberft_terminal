<?php

namespace common\helpers;

use common\models\CryptoproCert;
use common\models\CryptoproKey;
use common\modules\autobot\models\Autobot;
use common\modules\certManager\models\Cert;
use common\modules\monitor\models\ExpiringCert;
use Yii;

/**
 * Методы для получения сертификатов
 * Class ExpiredCertsHelper
 * @package common\helpers
 */
class CertsHelper
{
    public static function getAutobotCerts($terminalCode, $expiring = null)
    {
        $allCerts = [];

        $query = Autobot::find()
            ->joinWith('controller.terminal')
            ->select('fingerprint, terminal.terminalId, expirationDate, autobot.id, ownerSurname, ownerName, controllerId');
        $query->where(['not', ['expirationDate' => '0000-00-00 00:00:00']]);
        
        /*
         * CYB-4342 Если мы ищем истекающие ключи для оповещения, учитываем только те,
         * что в статусе "используется для подписания". Если таких нет, учитываем все
         */
        if (!isset($expiring)) {
            $query->andWhere(['autobot.status' => [Autobot::STATUS_ACTIVE, Autobot::STATUS_USED_FOR_SIGNING]]);
        } else {
            $queryTemp = clone $query;
            $queryTemp->andWhere(['autobot.status' => Autobot::STATUS_USED_FOR_SIGNING]);
            if (empty($queryTemp->asArray()->all())){
                $query->andWhere(['autobot.status' => [Autobot::STATUS_ACTIVE, Autobot::STATUS_USED_FOR_SIGNING]]);
            } else {
                $query->andWhere(['autobot.status' => Autobot::STATUS_USED_FOR_SIGNING]);
            }
        }

        if ($terminalCode) {
            $query->andWhere(['terminal.terminalId' => $terminalCode]);
        }

        $query->asArray();

        $certs = $query->all();

        foreach($certs as $cert) {
            $allCerts[] = new ExpiringCert([
                'terminal' => $cert['terminalId'],
                'date' => $cert['expirationDate'],
                'ownerType' => ExpiringCert::OWNER_TYPE_CONTROLLER,
                'fingerprint' => $cert['fingerprint'],
                'owner' => $cert['ownerName'] . " " . $cert['ownerSurname'],
            ]);
        }

        return $allCerts;
    }

    public static function getCerts($terminalCode, $ownerRole = null)
    {
        $allCerts = [];

        $query = Cert::find();
        $query->where(['not', ['validBefore' => null]]);
        $query->andWhere(['status' => Cert::STATUS_C10]);
        $query->andFilterWhere(['role' => $ownerRole]);

        if ($terminalCode) {
            $query->andWhere([
                "concat_ws('', participantCode, countryCode, sevenSymbol, delimiter, terminalCode, participantUnitCode)"
                => $terminalCode
            ]);
        }

        $certs = $query->all();

        foreach ($certs as $cert) {
            $allCerts[] = new ExpiringCert([
                'terminal' => $cert->terminalId->value,
                'date' => $cert->useBefore,
                'ownerType' => $cert->role == Cert::ROLE_SIGNER ? ExpiringCert::OWNER_TYPE_SIGNER : ExpiringCert::OWNER_TYPE_CONTROLLER,
                'fingerprint' => $cert->fingerprint,
                'owner' => $cert->fullName,
            ]);
        }

        return $allCerts;
    }

    public static function getCryptoProCerts($moduleName, $terminalId)
    {
        $allCerts = [];
        $certModel = CryptoproCert::getInstance($moduleName);
        $query = $certModel->find();
        $query->where(['not', ['validBefore' => null]]);
        $query->andWhere(['status' => 'ready']);

        if ($terminalId) {
            $query->andWhere(['terminalId' => $terminalId]);
        }

        $certs = $query->all();

        foreach ($certs as $cert) {
            $allCerts[] = new ExpiringCert([
                'terminal' => $cert->terminal->terminalId,
                'date' => $cert->validBefore,
                'ownerType' => ExpiringCert::OWNER_TYPE_SIGNER,
                'fingerprint' => $cert->keyId,
                'owner' => $cert->ownerName,
            ]);
        }

        return $allCerts;
    }

    public static function getCryptoProKeys($terminalId)
    {
        $allCerts = [];

        // Запрос по всем терминалам
        $query = CryptoproKey::find();
        $query->where(['active' => 1]);
        $query->andWhere(['not', ['expireDate' => null]]);
        $certs = $query->all();

        foreach($certs as $cert) {
            // Получение всех терминалов и организаций, к которому привязан сертификат
            $terminals = [];
            $organizations = [];

            foreach($cert->terminals as $certTerminal) {
                // Если запрос по конкретному терминалу
                if ($terminalId && $certTerminal->id != $terminalId) {
                    continue;
                }

                $certTerminalId = $certTerminal->terminalId;
                $terminals[] = $certTerminalId;
                $organizations[] = ExpiringCert::getTerminalName($certTerminalId);
            }

            if (count($terminals) > 0) {
                $allCerts[] = new ExpiringCert(
                    [
                        'terminal'     => implode(', ', $terminals),
                        'terminalName' => implode(', ', $organizations),
                        'date'         => $cert->expireDate,
                        'ownerType'    => ExpiringCert::OWNER_TYPE_SIGNER,
                        'fingerprint'  => $cert->keyId,
                        'owner'        => $cert->ownerName,
                    ]
                );
            }
        }

        return $allCerts;
    }

    public static function filterExpiredCerts($certs)
    {
        $expiredCerts = [];

        foreach ($certs as $index => $cert) {
            $expiredGroup = $cert->isExpired
                ? Yii::t('monitor/mailer', 'Attention! These certificates already expired')
                : Yii::t('monitor/mailer', 'Attention! These certificates expired');

            $expiredType = $cert->ownerType === ExpiringCert::OWNER_TYPE_SIGNER
                ? Yii::t('monitor/mailer', 'Signer Certificates')
                : Yii::t('monitor/mailer', 'Controller Certificates');

            $expiredCerts[$expiredGroup][$expiredType][$index] = [
                'terminal'     => $cert->terminal,
                'terminalName' => $cert->terminalName,
                'date'         => $cert->date,
                'fingerprint'  => $cert->fingerprint,
                'owner'        => $cert->owner,
            ];
        }

        return $expiredCerts;
    }

    public static function linearize($certBody)
    {
        $body = trim(str_replace([
            '-----BEGIN CERTIFICATE-----', '-----END CERTIFICATE-----',
            //"\r", "\n"
            ],
            '',
            $certBody
        ));

        $body = str_replace("\r", "\n", $body);
        $body = str_replace("\n\n", "\n", $body);

        return $body;
    }

}