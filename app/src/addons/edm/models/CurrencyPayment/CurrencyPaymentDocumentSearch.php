<?php

namespace addons\edm\models\CurrencyPayment;

use addons\edm\models\CurrencyPaymentRegister\CurrencyPaymentRegisterDocumentExt;
use addons\edm\models\DictBank;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\EdmPayerAccountUser;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationDocumentExt;
use addons\edm\models\Pain001Fcy\Pain001FcyType;
use addons\edm\models\VTBPayDocCur\VTBPayDocCurType;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use common\base\traits\ForSigningCountable;
use common\document\DocumentSearch;
use common\models\User;
use Yii;
use yii\db\ActiveQuery;

class CurrencyPaymentDocumentSearch extends DocumentSearch
{
    use ForSigningCountable;

    private const DOCUMENT_TYPE_PAYMENT = 'payment';
    private const DOCUMENT_KIND_REGISTER = 'register';
    private $_cachedExtModels = null;

    public $date;
    public $numberDocument;
    public $currency;
    public $sum;
    public $currencySum;
    public $businessStatus;
    public $debitAccount;
    public $payerName;
    public $payerOrganizationId;
    public $bankName;
    public $bankBik;
    public $paymentsCount;
    public $documentKind; // register or payment

    public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            [
                'date' => Yii::t('edm', 'Document date'),
                'debitAccount' => Yii::t('edm', 'Debiting account'),
                'payerOrganizationId' => Yii::t('edm', 'Payer'),
                'bankBik' => Yii::t('edm', 'Bank'),
                'businessStatus' => Yii::t('document', 'Execution status'),
                'currency' => Yii::t('edm', 'Currency'),
                'sum' => Yii::t('edm', 'Rouble amount'),
                'currencySum' => Yii::t('edm', 'Currency amount'),
                'totalSum' => Yii::t('edm', 'Total Sum'),
                'numberDocument' => Yii::t('edm', 'Number document'),
                'documentKind' => Yii::t('app', 'Type'),
                'paymentsCount' => Yii::t('edm', 'Documents count'),
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
                        'date', 'payerOrganizationId', 'debitAccount', 'bankBik', 'businessStatus',
                        'currency', 'sum', 'currencySum', 'totalSum', 'numberDocument', 'documentKind'
                    ],
                    'safe'
                ],
            ]
        );
    }

    /**
     * Метод добавляет к запросу расширенные поля и фильтры поиска
     * @param ActiveQuery $query
     * @return void
     */
    public function applyExtFilters($query)
    {
        $query
            // Объединить с ext-таблицей по признаку платёжных документов
            ->leftJoin(
                ForeignCurrencyOperationDocumentExt::tableName() . ' paymentExt',
                [
                    'and',
                    'paymentExt.documentId = document.id',
                    ['document.type' => $this->getPaymentDocumentTypes()]
                ]
            )
            // Объединить с ext-таблицей по признаку реестров
            ->leftJoin(
                CurrencyPaymentRegisterDocumentExt::tableName() . ' registerExt',
                [
                    'and',
                    'registerExt.documentId = document.id',
                    ['document.type' => $this->getRegisterDocumentTypes()]
                ]
            );

        // Объединить с таблицей счетов плательщика по признаку совпадения счёта
        $query->innerJoin(
            EdmPayerAccount::tableName() . ' payerAccount',
            'payerAccount.number = paymentExt.debitAccount or payerAccount.number = registerExt.debitAccount'
        );
        // Объединить с таблицей организаций по признаку совпадения организации
        $query->innerJoin(
            DictOrganization::tableName() . ' org',
            'org.id = payerAccount.organizationId'
        );
        // Объединить со справочником банков по признаку совпадения банка у счёта
        $query->innerJoin(
            DictBank::tableName() . ' debitAccountBank',
            'debitAccountBank.bik = payerAccount.bankBik'
        );

        // Выбрать поля для получения
        $this->_select[] = 'paymentExt.numberDocument';
        $this->_select[] = 'paymentExt.sum';
        $this->_select[] = 'paymentExt.currencySum';
        $this->_select[] = 'paymentExt.currency';
        $this->_select[] = 'coalesce(registerExt.paymentsCount, 1) as paymentsCount';
        $this->_select[] = 'coalesce(paymentExt.date, registerExt.date) as date';
        $this->_select[] = 'coalesce(paymentExt.debitAccount, registerExt.debitAccount) as debitAccount';
        $this->_select[] = 'coalesce(paymentExt.businessStatus, registerExt.businessStatus) as businessStatus';
        $this->_select[] = 'org.name as payerName';
        $this->_select[] = 'org.id as payerOrganizationId';
        $this->_select[] = 'debitAccountBank.name as bankName';
        $this->_select[] = 'debitAccountBank.bik as bankBik';

        // Фильтровать по пользовательскому доступу
        // Получить модель пользователя из активной сессии
        $currentUser = Yii::$app->user->identity;
        if (!in_array($currentUser->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN])) {
            $accountsNumbers = EdmPayerAccountUser::getUserAllowAccountsNumbers($currentUser->id);
            $query->andWhere([
                'or',
                ['in', 'paymentExt.debitAccount', $accountsNumbers],
                ['in', 'registerExt.debitAccount', $accountsNumbers],
            ]);
        }

        if ($this->date) {
            $documentDate = \DateTime::createFromFormat('Y-m-d', $this->date);
            if ($documentDate) {
                $query->andFilterWhere([
                    'or',
                    ['paymentExt.date' => $documentDate->format('Y-m-d')],
                    ['registerExt.date' => $documentDate->format('Y-m-d')]
                ]);
            }
        }

        $query->andFilterWhere([
            'or',
            ['paymentExt.debitAccount' => $this->debitAccount],
            ['registerExt.debitAccount' => $this->debitAccount]
        ]);
        $query->andFilterWhere(['debitAccountBank.bik' => $this->bankBik]);
        $query->andFilterWhere(['paymentExt.numberDocument' => $this->numberDocument]);
        $query->andFilterWhere(['paymentExt.currency' => $this->currency]);

        $sum = floatval(str_replace(' ', '', $this->sum)) ?: null;
        $currencySum = floatval(str_replace(' ', '', $this->currencySum)) ?: null;
        $query->andFilterWhere(['paymentExt.sum' => $sum]);
        $query->andFilterWhere(['paymentExt.currencySum' => $currencySum]);

        $query->andFilterWhere([
            'or',
            ['paymentExt.businessStatus' => $this->businessStatus],
            ['registerExt.businessStatus' => $this->businessStatus]
        ]);

        if ($this->documentKind === static::DOCUMENT_TYPE_PAYMENT) {
            $query->andWhere(['document.type' => $this->getPaymentDocumentTypes()]);
        } else if ($this->documentKind === static::DOCUMENT_KIND_REGISTER) {
            $query->andWhere(['document.type' => $this->getRegisterDocumentTypes()]);
        }

        static::applyPayerFilter($query, $this->payerOrganizationId, 'payerAccount');
    }

    private function getRegisterDocumentTypes(): array
    {
        return [Pain001FcyType::TYPE, VTBRegisterCurType::TYPE];
    }

    private function getPaymentDocumentTypes(): array
    {
        return ['MT103', VTBPayDocCurType::TYPE];
    }

    public function getDocumentKindLabel()
    {
        $types = static::getDocumentKindFilter();
        return $types[$this->documentKind] ?? null;
    }

    public static function getDocumentKindFilter()
    {
        return [
            static::DOCUMENT_TYPE_PAYMENT  => Yii::t('edm', 'Payment'),
            static::DOCUMENT_KIND_REGISTER => Yii::t('edm', 'Register'),
        ];
    }

    public static function getTypeFilter()
    {
        return [
            Pain001FcyType::TYPE => Pain001FcyType::TYPE,
            'MT103' => 'MT103',
            VTBRegisterCurType::TYPE => VTBRegisterCurType::TYPE,
            VTBPayDocCurType::TYPE => VTBPayDocCurType::TYPE,
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->documentKind = in_array($this->type, $this->getRegisterDocumentTypes())
            ? static::DOCUMENT_KIND_REGISTER
            : static::DOCUMENT_TYPE_PAYMENT;
    }

    protected function applySignaturePermissionFilter(ActiveQuery $query): void
    {
        $accountsNumbers = EdmPayerAccount::find()
            ->leftJoin(EdmPayerAccountUser::tableName() . ' userAccount', 'userAccount.accountId = edmPayersAccounts.id')
            ->where(['userAccount.userId' => Yii::$app->user->id, 'userAccount.canSignDocuments' => true])
            ->select('edmPayersAccounts.number')
            ->column();
        $query->andWhere([
            'or',
            ['in', 'paymentExt.debitAccount', $accountsNumbers],
            ['in', 'registerExt.debitAccount', $accountsNumbers],
        ]);
    }

    private function getCachedExtModels()
    {
        if ($this->_cachedExtModels === null) {
            $this->_cachedExtModels = [];
            $list = ForeignCurrencyOperationDocumentExt::findAll(['documentId' => $this->id]);
            foreach($list as $model) {
                $this->_cachedExtModels[] = $model;
            }
        }
        return $this->_cachedExtModels;
    }

    public function getCurrency()
    {
        if ($this->documentKind == static::DOCUMENT_TYPE_PAYMENT) {
            return $this->currency;
        }

        $extModels = $this->getCachedExtModels();

        return $extModels[0]->currency;
    }

    public function getTotalSum()
    {
        if ($this->documentKind == static::DOCUMENT_TYPE_PAYMENT) {
            return $this->sum ?: $this->currencySum;
        }

        $extModels = $this->getCachedExtModels();
        $sum = 0;
        foreach($extModels as $extModel) {
            $sum += $extModel->sum ?: $extModel->currencySum;
        }

        return $sum;
    }
}
