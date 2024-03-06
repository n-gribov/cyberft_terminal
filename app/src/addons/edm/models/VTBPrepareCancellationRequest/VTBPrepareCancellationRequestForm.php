<?php

namespace addons\edm\models\VTBPrepareCancellationRequest;

use common\document\Document;
use Yii;
use yii\base\Model;

class VTBPrepareCancellationRequestForm extends Model
{
    public $messageForBank;

    /** @var Document */
    public $document;
    public $documentNumber;
    public $documentDate;

    public function rules()
    {
        return [
            ['messageForBank', 'string'],
            [['messageForBank', 'documentDate', 'documentNumber'], 'safe'],
            ['document', 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'messageForBank' => Yii::t('edm', 'Message for bank'),
            'document' => Yii::t('edm', 'Document'),
        ];
    }

    public function getFirstErrorMessage()
    {
        $errorMessages = array_values($this->getFirstErrors());
        return count($errorMessages) > 0 ? $errorMessages[0] : null;
    }
}
