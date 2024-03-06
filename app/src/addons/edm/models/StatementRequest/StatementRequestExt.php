<?php

namespace addons\edm\models\StatementRequest;

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\SBBOLStmtReq\SBBOLStmtReqType;
use addons\edm\models\VTBStatementQuery\VTBStatementQueryType;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "documentExt".
 *
 * @property string  $documentId Document ID
 * @property string  $startDate  Statement start date
 * @property string  $endDate    Statement end date
 * @property string  $accountNumber
 */
class StatementRequestExt extends ActiveRecord implements DocumentExtInterface
{
    private $_document;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documentExtEdmStatementRequest';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documentId'], 'required'],
            [['startDate', 'endDate'], 'safe'],
            [['accountNumber'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'            => Yii::t('edm', 'ID'),
            'documentId'    => Yii::t('edm', 'Document ID'),
            'startDate'     => Yii::t('edm', 'Start date'),
            'endDate'       => Yii::t('edm', 'End date'),
            'accountNumber' => Yii::t('edm', 'Account number'),
        ];
    }

    public function getDocument()
    {
        if (is_null($this->_document)) {
            $this->_document = $this->hasOne('common\document\Document', ['id' => 'documentId']);
        }

        return $this->_document;
    }

    /**
     * @param StatementRequestType|VTBStatementQueryType|SBBOLStmtReqType $model
     */
    public function loadContentModel($model)
    {
        if ($model::TYPE === StatementRequestType::TYPE) {
            $this->accountNumber = $model->accountNumber;
            $this->startDate = $model->startDate;
            $this->endDate = $model->endDate;
        } elseif ($model::TYPE === VTBStatementQueryType::TYPE) {
            $this->accountNumber = $model->document->ACCOUNT;
            $this->startDate = $model->document->DATEFROM->format('Y-m-d');
            $this->endDate = $model->document->DATETO->format('Y-m-d');
        } elseif ($model::TYPE === SBBOLStmtReqType::TYPE) {
            $stmtReq = $model->request->getStmtReq();
            $this->accountNumber = $stmtReq->getAccounts()[0]->value();
            $this->startDate = $stmtReq->getBeginDate()->format('Y-m-d');
            $this->endDate = $stmtReq->getEndDate()->format('Y-m-d');
        } else {
            throw new \Exception("Unsupported type model type: {$model->getType()}");
        }
    }

    public function isDocumentDeletable(User $user = null)
    {
        return true;
    }

    public function canBeSignedByUser(User $user, Document $document): bool
    {
        $account = EdmPayerAccount::findOne(['number' => $this->accountNumber]);
        return $account !== null && EdmPayerAccountUser::userCanSingDocuments($user->id, $account->id);
    }
}
