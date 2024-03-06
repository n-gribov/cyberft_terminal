<?php

namespace common\document;

use common\components\TerminalId;
use common\modules\certManager\models\Cert;
use common\modules\participant\models\BICDirParticipant;
use Yii;

class DocumentFormatGroup
{
    public const ISO20022 = 'ISO20022';
    public const ROSBANK_ISO20022 = 'Rosbank_ISO20022';
    public const RAIFFEISEN = 'Raiffeisen';
    public const SBBOL = 'sbbol';
    public const SBBOL2 = 'sbbol2';
    public const VTB = 'vtb';

    private static $all;

    public static function getAll(): array
    {
        if (static::$all === null) {
            static::$all = [
                static::ISO20022         => Yii::t('app', 'ISO20022'),
                static::ROSBANK_ISO20022 => Yii::t('app', 'ISO20022 (Rosbank)'),
                static::RAIFFEISEN       => Yii::t('app', 'Raiffeisen'),
                static::SBBOL            => Yii::t('app', 'Sberbank (legacy)'),
                static::SBBOL2           => Yii::t('app', 'Sberbank'),
                static::VTB              => Yii::t('app', 'VTB'),
            ];
        }

        return static::$all;
    }

    public static function getNameById(string $id): ?string
    {
        return static::getAll()[$id] ?? null;
    }

    public static function getTerminalAddressesByGroup(string $group): array
    {
        $participants = BICDirParticipant::find()
            ->where(['documentFormatGroup' => $group])
            ->all();

        $getParticipantTerminalsAddresses = function (BICDirParticipant $participant) {
            return array_map(
                function (Cert $cert) {
                    return $cert->getTerminalAddress();
                },
                Cert::findByParticipant($participant->participantBIC)->all(),
            );
        };

        $participantsTerminalsAddresses = array_map($getParticipantTerminalsAddresses, $participants);

        if (count($participantsTerminalsAddresses) === 0) {
            return [];
        }

        return array_unique(array_merge(...$participantsTerminalsAddresses));
    }

    public static function getTerminalAddressByGroup(string $group): ?string
    {
        $addresses = self::getTerminalAddressesByGroup($group);
        if (count($addresses) === 0) {
            return null;
        } else if (count($addresses) > 1) {
            Yii::warning("Multiple terminal addresses for $group exchange format found: " . implode(', ', $addresses)  . ', will return null');
            return null;
        }

        return $addresses[0];
    }

    public static function getGroupByTerminalAddress(string $terminalAddress): ?string
    {
        $participantBic = TerminalId::extract($terminalAddress)->getParticipantId();
        $participant = BICDirParticipant::findOne(['participantBIC' => $participantBic]);
        return $participant ? $participant->documentFormatGroup : null;
    }
}
