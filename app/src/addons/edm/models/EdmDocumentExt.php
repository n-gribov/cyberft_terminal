<?php

namespace addons\edm\models;

use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\models\User;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "documentExt".
 *
 * @property integer $id         Row ID
 * @property string  $documentId Document ID
 */
class EdmDocumentExt extends ActiveRecord implements DocumentExtInterface
{
    private $_document;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documentExtEdm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentId'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('edm', 'ID'),
            'documentId' => Yii::t('edm', 'Document ID'),
            'currency'   => Yii::t('edm', 'Currency'),
        ];
    }

    public function getDocument()
    {
        if (is_null($this->_document)) {
            $this->_document = $this->hasOne('common\document\Document', ['id' => 'documentId']);
        }

        return $this->_document;
    }

    public function loadContentModel($model)
    {

    }

    public function isDocumentDeletable(User $user = null)
    {
        return false;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        return false;
    }
}
