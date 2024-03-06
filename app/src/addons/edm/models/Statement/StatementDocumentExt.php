<?php

namespace addons\edm\models\Statement;

use addons\edm\models\DictCurrency;
use common\base\interfaces\DocumentExtInterface;
use common\document\Document;
use common\models\User;
use Yii;
use yii\db\ActiveRecord;

/**
 * @property integer  $id
 * @property string   $documentId
 */
class StatementDocumentExt extends ActiveRecord implements DocumentExtInterface
{
    private $_document;

    public static function tableName()
    {
        return 'documentExtEdmStatement';
    }

    public function rules()
    {
        return [
            [['documentId'], 'required'],
            [[
                'number', 'companyName', 'accountNumber', 'openingBalance', 'debitTurnover', 'creditTurnover',
                'closingBalance', 'accountBik', 'dateCreated', 'periodStart', 'periodEnd', 'prevLastOperationDate',
                'currency'
            ], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'                    => Yii::t('edm', 'ID'),
            'documentId'            => Yii::t('edm', 'Document ID'),
            'number'                => Yii::t('edm', 'Number'),
            'companyName'           => Yii::t('edm', 'Company Name'),
            'accountNumber'         => Yii::t('edm', 'Account number'),
            'accountBik'            => Yii::t('edm', 'Account BIK'),
            'openingBalance'        => Yii::t('edm', 'Opening balance'),
            'debitTurnover'         => Yii::t('edm', 'Turnover debit'),
            'creditTurnover'        => Yii::t('edm', 'Turnover credit'),
            'closingBalance'        => Yii::t('edm', 'Closing balance'),
            'dateCreated'           => Yii::t('edm', 'Create date'),
            'periodStart'           => Yii::t('edm', 'Period start date'),
            'periodEnd'             => Yii::t('edm', 'Period end date'),
            'prevLastOperationDate' => Yii::t('edm', 'Last operation date'),
            'currency'              => Yii::t('edm', 'Currency'),
        ];
    }

    public function loadContentModel($model)
    {
        $statementModel = StatementTypeConverter::convertFrom($model);

        $this->number                = $statementModel->statementNumber;
        $this->dateCreated           = date('Y-m-d H:i:s', strtotime($statementModel->dateCreated));
        $this->openingBalance        = $statementModel->openingBalance;
        $this->debitTurnover         = $statementModel->debitTurnover;
        $this->creditTurnover        = $statementModel->creditTurnover;
        $this->closingBalance        = $statementModel->closingBalance;
        $this->accountNumber         = $statementModel->statementAccountNumber;
        $this->accountBik            = $statementModel->statementAccountBIK;
        $this->periodStart           = date('Y-m-d', strtotime($statementModel->statementPeriodStart));
        $this->periodEnd             = date('Y-m-d', strtotime($statementModel->statementPeriodEnd));
        $this->prevLastOperationDate = date('Y-m-d H:i:s', strtotime($statementModel->prevLastOperationDate));
        $this->companyName           = $statementModel->companyName;

        $currency = $statementModel->currency;

        if (!$currency) {
            // Если валюта не указана, получаем её из номера счета

            // Счет должен содержать 20 символов
            if (strlen($statementModel->statementAccountNumber) == 20) {

                // Получаем 5-8 символы из номера счета
                $accountCurrency = substr($statementModel->statementAccountNumber, 5, 3);

                // Ищем в справочнике валюту по её номеру
                $currencyDict = DictCurrency::findOne(['code' => $accountCurrency]);

                // Если валюта найдена в справочнике, заполняем выписку
                if ($currencyDict) {
                    $currency = $currencyDict->name;
                }

            }
        }
	
        $this->currency = $currency;
    }

    public function getDocument()
    {
        if (is_null($this->_document)) {
            $this->_document = $this->hasOne('common\document\Document',
                ['id' => 'documentId']);
        }

        return $this->_document;
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
