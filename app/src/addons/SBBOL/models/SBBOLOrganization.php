<?php

namespace addons\SBBOL\models;

use common\validators\TerminalIdValidator;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sbbol_organization".
 *
 * @property string $inn
 * @property string $fullName
 * @property string $terminalAddress
 * @property SBBOLCustomer[] $customers
 */
class SBBOLOrganization extends ActiveRecord
{
    const SCENARIO_WEB_UPDATE = 'web_update';

    public static function tableName()
    {
        return 'sbbol_organization';
    }

    public static function primaryKey()
    {
        return ['inn'];
    }

    public function rules()
    {
        return [
            [['inn', 'fullName'], 'required'],
            [['inn', 'terminalAddress'], 'string', 'max' => 32],
            ['fullName', 'string', 'max' => 1000],
            [['terminalAddress'], 'unique'],
            [['terminalAddress'], 'default', 'value' => null],
            [['terminalAddress'], TerminalIdValidator::className()],
        ];
    }

    public function scenarios()
    {
        return array_merge(
            parent::scenarios(),
            [self::SCENARIO_WEB_UPDATE => ['terminalAddress']]
        );
    }

    public function attributeLabels()
    {
        return [
            'inn' => Yii::t('app/sbbol', 'INN'),
            'fullName' => Yii::t('app/sbbol', 'Full name'),
            'terminalAddress' => Yii::t('app/sbbol', 'Terminal address'),
        ];
    }

    public function getCustomers()
    {
        return $this->hasMany(SBBOLCustomer::class, ['inn' => 'inn']);
    }
}
