<?php

namespace addons\edm\models\BankLetter;

use Yii;

final class IsoMessageTypeCodes
{
    public static function all(): array
    {
        $typeCodes = Yii::$app->settings->get('ISO20022:ISO20022')->getTypeCodeList();
        return ['OTHR' => $typeCodes['OTHR']] + $typeCodes;
    }

    public static function getNameById(string $id): ?string
    {
        $all = self::all();
        return $all[$id] ?? null;
    }
}
