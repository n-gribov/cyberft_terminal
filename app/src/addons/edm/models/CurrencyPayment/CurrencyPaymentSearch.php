<?php

namespace addons\edm\models\CurrencyPayment;

use addons\edm\models\DictBank;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\Pain001Fcy\Pain001FcyType;
use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use common\document\DocumentSearch;
use common\models\User;
use Yii;
use yii\db\ActiveQuery;

/**
 * @property bool $isInRegister
 */
class CurrencyPaymentSearch extends DocumentSearch
{
    public $extId;
    public $numberDocument;
    public $date;
    public $bankName;
    public $bankBik;
    public $currency;
    public $sum;
    public $paymentPurpose;
    public $businessStatus;
    public $debitAccount;
    public $creditAccount;
    public $payerName;
    public $payerOrganizationId;
    public $beneficiary;

    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'date' => Yii::t('edm', 'Document date'),
                'debitAccount' => Yii::t('edm', 'Debiting account'),
                'creditAccount' => Yii::t('edm', 'Crediting account'),
                'payerOrganizationId' => Yii::t('edm', 'Payer'),
                'payerName' => Yii::t('edm', 'Payer'),
                'bankBik' => Yii::t('edm', 'Bank'),
                'businessStatus' => Yii::t('document', 'Execution status'),
                'paymentPurpose' => Yii::t('edm', 'Payment Purpose'),
                'beneficiary' => Yii::t('edm', 'Beneficiary'),
                'numberDocument' => Yii::t('edm', 'Number document'),
                'currency' => Yii::t('edm', 'Currency'),
                'sum' => Yii::t('edm', 'Sum'),
            ]
        );
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            [
                [
                    [
                        'date', 'payerOrganizationId', 'debitAccount', 'bankBik', 'businessStatus', 'paymentPurpose',
                        'beneficiary', 'numberDocument', 'currency', 'sum'
                    ],
                    'safe'
                ],
            ]
        );
    }

    /**
     * @param ActiveQuery $query
     * @return void
     */
    public function applyExtFilters($query)
    {
        $query->innerJoin(
            ForeignCurrencyOperationDocumentExt::tableName() . ' ext',
            'ext.documentId = ' . static::tableName() . '.id'
        );
        $query->innerJoin(
            EdmPayerAccount::tableName() . ' payerAccount',
            'payerAccount.number = ext.debitAccount'
        );
        $query->innerJoin(
            DictOrganization::tableName() . ' org',
            'org.id = payerAccount.organizationId'
        );

        $query->innerJoin(
            DictBank::tableName() . ' debitAccountBank',
            'debitAccountBank.bik = payerAccount.bankBik'
        );

        $this->_select[] = 'ext.id as extId';
        $this->_select[] = 'ext.numberDocument';
        $this->_select[] = 'ext.date';
        $this->_select[] = 'ext.debitAccount';
        $this->_select[] = 'ext.creditAccount';
        $this->_select[] = 'ext.businessStatus';
        $this->_select[] = 'ext.currency';
        $this->_select[] = 'coalesce(ext.currencySum, ext.sum) as sum';
        $this->_select[] = 'ext.beneficiary';
        $this->_select[] = 'ext.paymentPurpose';
        $this->_select[] = 'org.name as payerName';
        $this->_select[] = 'org.id as payerOrganizationId';
        $this->_select[] = 'debitAccountBank.name as bankName';
        $this->_select[] = 'debitAccountBank.bik as bankBik';

        $query->andWhere(['document.type' => $this->getDocumentTypes()]);
        // Получить модель пользователя из активной сессии
        $currentUser = Yii::$app->user->identity;
        if (!in_array($currentUser->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN])) {
            $accountsNumbers = EdmPayerAccountUser::getUserAllowAccountsNumbers($currentUser->id);
            $query->andWhere(['in', 'debitAccount', $accountsNumbers]);
        }

        if ($this->date) {
            $documentDate = \DateTime::createFromFormat('Y-m-d', $this->date);
            if ($documentDate) {
                $query->andFilterWhere(['date' => $documentDate->format('Y-m-d')]);
            }
        }

        $sum = floatval(str_replace(' ', '', $this->sum));
        if ($sum) {
            $query->andFilterWhere([
                'or',
                ['currencySum' => $sum],
                ['sum' => $sum],
            ]);
        }

        $query->andFilterWhere(['ext.numberDocument' => $this->numberDocument]);
        $query->andFilterWhere(['ext.debitAccount' => $this->debitAccount]);
        $query->andFilterWhere(['debitAccountBank.bik' => $this->bankBik]);
        $query->andFilterWhere(['ext.businessStatus' => $this->businessStatus]);
        $query->andFilterWhere(['ext.currency' => $this->currency]);
        $query->andFilterWhere(['like', 'ext.beneficiary', $this->beneficiary]);
        $query->andFilterWhere(['like', 'ext.paymentPurpose', $this->paymentPurpose]);

        static::applyPayerFilter($query, $this->payerOrganizationId, 'payerAccount');

        $query->indexBy('extId');
    }

    private function getRegisterDocumentTypes(): array
    {
        return [Pain001FcyType::TYPE, VTBRegisterCurType::TYPE];
    }

    private function getDocumentTypes(): array
    {
        return array_merge(
            ['MT103', VTBPayDocCurType::TYPE],
            $this->getRegisterDocumentTypes()
        );
    }

    public function getIsInRegister(): bool
    {
        return in_array($this->type, $this->getRegisterDocumentTypes());
    }
}
