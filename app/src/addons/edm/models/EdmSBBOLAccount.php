<?php

namespace addons\edm\models;

use yii\db\ActiveRecord;

/**
 * Class EdmSBBOLAccount
 * @package addons\edm\models
 * @property string $id
 * @property string $number
 * @property string $customerId
 */
class EdmSBBOLAccount extends ActiveRecord
{
    public function rules()
    {
        return [
            [['id', 'number', 'customerId'], 'safe'],
        ];
    }

    public static function tableName()
    {
        return 'edm_sbbolAccount';
    }
}
