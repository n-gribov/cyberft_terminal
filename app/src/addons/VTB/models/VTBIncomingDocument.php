<?php

namespace addons\VTB\models;

use yii\db\ActiveRecord;

/**
 * Class VTBIncomingDocument
 * @package addons\VTB\models
 * @property integer $id
 * @property string $dateCreate
 * @property string $externalId
 * @property integer $customerId
 * @property string $documentId
 */
class VTBIncomingDocument extends ActiveRecord
{
    public function rules()
    {
        return [
            [['externalId', 'customerId', 'documentId'], 'required'],
        ];
    }

    public static function tableName()
    {
        return 'vtb_incomingDocument';
    }
}
