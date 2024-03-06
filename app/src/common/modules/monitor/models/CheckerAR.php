<?php

namespace common\modules\monitor\models;

use yii\db\ActiveRecord;

/**
 * Description of CheckerAR
 *
 * @author fuzz
 *
 * @package modules
 * @subpackage monitor
 */
class CheckerAR extends ActiveRecord
{
    public function rules()
    {
        return [
            ['code', 'unique'],
        ];
    }

    public static function tableName()
    {
        return 'monitor_checker';
    }


    public function getSettingsByTerminal($terminalId = null)
    {
        return CheckerSettingsAR::findOne(['checkerId' => $this->id, 'terminalId' => $terminalId]);
    }
}