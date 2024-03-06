<?php

namespace addons\edm\models\PaymentRegister;

use addons\edm\models\EdmPayerAccount;
use addons\edm\models\PaymentOrder\PaymentOrderType;
use Yii;
use yii\helpers\ArrayHelper;

/**
 *
 * @property-read bool $isOutdated
 */
class PaymentRegisterPaymentOrderTemplate extends PaymentRegisterPaymentOrder {

    public static function tableName()
    {
        return 'edm_paymentOrderTemplates';
    }

    public function rules()
    {
        return [
            ['terminalId', 'integer'],
            [['number'], 'integer'],
            [['body'], 'string'],
            [['date', 'dateProcessing', 'dateDue', 'name', 'vat', 'paymentPurposeNds'], 'safe'],
            [['sum'], 'number'],
            [['beneficiaryName', 'payerName', 'paymentPurpose'], 'string', 'max' => 255],
            [['payerAccount'], 'safe'],
            [['beneficiaryCheckingAccount'], 'string', 'max' => 30],
            [['currency'], 'string', 'max' => 4]
        ];
    }

    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'name' => Yii::t('edm', 'Template name')
            ]
        );
    }

    public function getIsOutdated(): bool
    {
        $account = EdmPayerAccount::findOne(['number' => $this->payerAccount]);
        if (!$account) {
            return false;
        }
        $organization = $account->edmDictOrganization;
        if (!$organization) {
            return false;
        }

        $typeModel = new PaymentOrderType();
        $typeModel->loadFromString($this->body);

        return $account->getPayerName() !== $this->payerName
            || (!empty($typeModel->payerName1) && $account->getPayerName() !== $typeModel->payerName1)
            || $organization->inn !== $typeModel->payerInn
            || $organization->kpp !== $typeModel->payerKpp;
    }

    public function updateOrganizationRequisites(): bool
    {
        $account = EdmPayerAccount::findOne(['number' => $this->payerAccount]);
        if (!$account) {
            return false;
        }
        $organization = $account->edmDictOrganization;
        if (!$organization) {
            return false;
        }

        $typeModel = new PaymentOrderType();
        $typeModel->loadFromString($this->body);

        $typeModel->payerInn = $organization->inn;
        $typeModel->payerKpp = $organization->kpp;
        $typeModel->payerName1 = $account->getPayerName();
        $typeModel->payerName = "ИНН {$typeModel->payerInn} {$typeModel->payerName1}";

        $this->payerName = $account->getPayerName();
        $this->body = (string)$typeModel;

        return $this->save();
    }
}
