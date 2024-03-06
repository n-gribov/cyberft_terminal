<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Terminal;

/**
 * @property integer $keyId        Row ID
 * @property string  $terminalId   Terminal ID
 */
class CryptoproKeyTerminal extends ActiveRecord
{
    public static function tableName()
    {
        return 'cryptoproKeyTerminal';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['keyId', 'terminalId'], 'required'],
            [['keyId', 'terminalId'], 'integer'],
            ['terminalId', 'exist', 'targetClass' => 'common\models\Terminal', 'targetAttribute' => 'id'],
            [['keyId', 'terminalId'], 'unique', 'targetAttribute' => ['keyId', 'terminalId']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'keyId'     => Yii::t('app/fileact', 'Key ID'),
            'terminalId'  => Yii::t('app/terminal', 'Terminal ID'),
        ];
    }

    /**
     * Связь с таблицей терминалов
     * для получения информации по терминалу
     */
    public function getTerminal()
    {
        return $this->hasOne(Terminal::className(), ['id' => 'terminalId']);
    }
}
