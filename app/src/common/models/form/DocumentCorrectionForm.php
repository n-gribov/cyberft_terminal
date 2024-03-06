<?php

namespace common\models\form;

use common\document\Document;
use common\helpers\DocumentHelper;
use Yii;
use yii\base\Model;

/**
 * Document correction form model

 * @package core
 * @subpackage models
 *
 * @property integer $documentId       Document ID
 * @property string  $correctionReason Correction reason
 */
class DocumentCorrectionForm extends Model
{
    /**
     * Command code
     */
    const COMMAND_CODE = 'DocumentStatusUpdate';

    /**
     * Command entity
     */
    const COMMAND_ENTITY = 'document';

    /**
     * @var integer $documentId Document ID
     */
    public $documentId;

    /**
     * @var string $correctionReason Correction reason
     */
    public $correctionReason;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['correctionReason', 'required'],
            ['correctionReason', 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'correctionReason' => Yii::t('doc', 'Correction reason'),
        ];
    }

    /**
     * Add correction command
     *
     * @param integer $userId User ID
     * @return boolean
     */
    public function toCorrection()
    {
        try {
            $document = Yii::$app->terminalAccess->findModel(Document::className(), $this->documentId);
        } catch (Exception $ex) {
            Yii::warning("Cannot find document ID[{$this->documentId}]");

            return false;
        }

        $document->correctionReason = $this->correctionReason;

        if (!$document->save([false, ['correctionReason']])) {
            Yii::warning('Please provide correction reason');

            return false;
        }

        return DocumentHelper::updateDocumentStatus($document, Document::STATUS_CORRECTION);
    }

}