<?php


namespace common\modules\monitor\models;

use common\base\Model;
use common\helpers\Address;
use common\models\Terminal;
use common\models\UserTerminal;
use common\modules\certManager\models\Cert;
use common\modules\participant\models\BICDirParticipant;
use common\helpers\CertsHelper;

class ExpiringCert extends Model
{
    const OWNER_TYPE_CONTROLLER = 'controller';
    const OWNER_TYPE_SIGNER = 'signer';

    public $terminal;
    public $terminalName;
    public $date;
    public $isExpired;
    public $fingerprint;
    public $owner;
    public $ownerType;

    /**
     * @param $maxDaysBeforeExpiration
     * @param null $user
     * @return array
     */
    public static function search($maxDaysBeforeExpiration, $user = null)
    {
        $terminals = [];

        if ($user) {
            // Если пользователь указан, получаем доступные ему терминалы
            $userTerminals = UserTerminal::getUserTerminals($user->id);

            foreach($userTerminals as $terminal) {
                $terminals[] = [
                    'id' => $terminal->id,
                    'code' => $terminal->terminalId,
                ];
            }

        } else {
            // Иначе получаем все терминалы
            $terminals[] = [
                'id' => null,
                'code' => null
            ];
        }

        $allCerts = [];

        foreach($terminals as $item) {
            $terminalId = $item['id'];
            $terminalCode = $item['code'];

            // Ключи контроллера
            $allCerts = array_merge($allCerts, CertsHelper::getAutobotCerts($terminalCode, true));
            
            // Общие сертификаты
            $allCerts = array_merge($allCerts, CertsHelper::getCerts($terminalCode, Cert::ROLE_SIGNER));

            // КриптоПро ISO20022 (верификация входящих)
            $allCerts = array_merge($allCerts, CertsHelper::getCryptoProCerts('ISO20022', $terminalId));

            // КриптоПро Fileact (верификация входящих)
            $allCerts = array_merge($allCerts, CertsHelper::getCryptoProCerts('fileact', $terminalId));

            // КриптоПро
            $allCerts = array_merge($allCerts, CertsHelper::getCryptoProKeys($terminalId));
        }

        // Массив истекших сертификатов
        $expiredCerts = [];

        foreach($allCerts as $cert) {
            $datetime1 = new \DateTime(date('Y-m-d H:i:s'));
            $datetime2 = new \DateTime($cert->date);
            $interval = $datetime1->diff($datetime2);
            $intervalDays = $interval->days;

            if ($datetime1 > $datetime2) {
                $cert->isExpired = true;
                $intervalDays *= -1;
            } else {
                $cert->isExpired = false;
            }

            if ($intervalDays > $maxDaysBeforeExpiration) {
                continue;
            }

            $cert->terminalName = static::getTerminalName($cert->terminal);
            $expiredCerts[] = $cert;
        }

        return static::unique($expiredCerts);
    }

    public static function getTerminalName($terminalId)
    {
        $terminal = Terminal::findOne(['terminalId' => $terminalId]);

        // Если найден терминал и у него есть описание,
        // передаем его для формирование уведомления
        if ($terminal && $terminal->title) {
            return $terminal->title;
        }

        // Пробуем найти наименование среди участников системы
        $participantAddress = Address::truncateAddress($terminalId);
        $participant = BICDirParticipant::findOne(['participantBIC' => $participantAddress]);
        return $participant !== null ? $participant->name : $terminalId;
    }

    /**
     * @param Model[] $models
     * @return Model[]
     */
    private static function unique(array $models): array
    {
        $alreadyExists = [];
        return array_filter(
            $models,
            function (Model $model) use (&$alreadyExists) {
                $attributes = $model->attributes;
                if (in_array($attributes, $alreadyExists)) {
                    return false;
                } else {
                    $alreadyExists[] = $attributes;
                    return true;
                }
            }
        );
    }
}
