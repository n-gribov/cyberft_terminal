<?php

namespace addons\swiftfin\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "swiftfin_templates".
 *
 * @property integer $id
 * @property string $docType
 * @property string $title
 * @property string $comment
 * @property string $text
 * @property string $terminalCode
 * @property integer terminalId
*/

class SwiftfinTemplate extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'swiftfin_templates';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['terminalId', 'integer'],
            [['docType', 'title', 'text','sender', 'recipient', 'terminalId'], 'required'],
            ['bankPriority', 'match', 'pattern' => '/^[a-zA-Z0-9]{4}$/i'],
            ['bankPriority', 'string', 'length' => 4],
            ['terminalCode', 'string', 'length' => 1],
            [['sender', 'recipient'], 'string', 'length' => [11, 12]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('doc/swiftfin', 'ID'),
            'docType' => Yii::t('document', 'Document type'),
            'title' => Yii::t('doc/swiftfin', 'Template description'),
            'text' => Yii::t('document', 'Template text'),
            'sender'       => Yii::t('doc', 'Sender'),
            'recipient'    => Yii::t('doc', 'Recipient'),
            'terminalCode' => Yii::t('doc', 'Terminal code'),
        ];
    }
}
