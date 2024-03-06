<?php

namespace addons\edm\models\PaymentRegister;

use addons\edm\models\PaymentOrder\PaymentOrderType;
use common\base\Model;
use common\models\Terminal;
use Yii;
use yii\helpers\ArrayHelper;

class PaymentRegisterWizardForm extends Model
{
    private $_paymentOrderIdList = [];

    public $docId;
    public $sender;
	public $recipient;
    public $account;
	public $comment;
    public $sum;

	public function rules()
	{
		return [
			[['sender', 'recipient'], 'required'],
			[['account'], 'string'],
            [['sum'], 'integer', 'integerOnly' => false],
			[['sender', 'recipient'], 'string', 'length' => [11, 12]],
            ['paymentOrders', 'validatePaymentOrders'],
		];
	}

	public function attributeLabels()
	{
		return ArrayHelper::merge(parent::attributeLabels(), [
			'sender'       => Yii::t('doc', 'Sender'),
			'recipient'    => Yii::t('doc', 'Recipient'),
			'account' => Yii::t('doc', 'Account'),
			'comment' => Yii::t('doc', 'Comment'),
            'sum' => Yii::t('doc', 'Sum'),
            'paymentOrderCount' => Yii::t('edm', 'Payment orders'),
            'paymentOrders' => Yii::t('edm', 'Payment orders'),
		]);
	}

	public function addPaymentOrdersId($listId = [])
	{
        foreach ($listId as $value) {
            $id = (int) $value;

            if (!in_array($id, $this->_paymentOrderIdList)) {
                $this->_paymentOrderIdList[] = $id;
            }
        }
	}

    /**
     * @param PaymentRegisterPaymentOrder[] $paymentOrders
     */
    public function addPaymentOrders(array $paymentOrders): void
    {
        foreach ($paymentOrders as $paymentOrder) {
            $id = (int) $paymentOrder->id;
            if (!in_array($id, $this->_paymentOrderIdList)) {
                if (empty($this->account)) {
                    $this->account = $paymentOrder->payerAccount;
                }

                if (empty($this->sender)) {
                    $terminal = Terminal::findOne($paymentOrder->terminalId);
                    if ($terminal) {
                        $this->sender = $terminal->terminalId;
                    } else {
                        $this->sender = Yii::$app->terminals->defaultTerminal->terminalId;
                    }
                }

                $this->_paymentOrderIdList[] = $id;
                $this->sum = $this->sum + $paymentOrder->sum;
            }
        }

        $payerAccounts = ArrayHelper::getColumn($paymentOrders, 'payerAccount');
        if (count(array_unique($payerAccounts)) > 1) {
            $this->addError('paymentOrders', Yii::t('edm',  'Payment orders must have same payer account'));
            return;
        }
    }

    public function clearPaymentOrders()
    {
        $this->_paymentOrderIdList = [];
    }

    public function getPaymentOrders()
    {
        return array_values($this->_paymentOrderIdList);
    }

    public function validate($attributeNames = null, $clearErrors = true)
    {
        if (!parent::validate($attributeNames, $clearErrors)) {
            return false;
        }

        if (count($this->_paymentOrderIdList) == 0) {
            $this->addError('paymentOrderCount', Yii::t('edm', 'You must select at least one payment order'));

            return false;
        }

        return true;
    }

    public function validatePaymentOrders($attribute, $params = []): void
    {
        $paymentOrderRecords = PaymentRegisterPaymentOrder::find()
            ->where(['id' => $this->_paymentOrderIdList])
            ->all();

        $paymentOrders = array_map(
            function (PaymentRegisterPaymentOrder $paymentOrderRecord) {
                $paymentOrder = new PaymentOrderType();
                $paymentOrder->loadFromString($paymentOrderRecord->body);
                return $paymentOrder;
            },
            $paymentOrderRecords
        );

        if (!$this->allPaymentOrdersHasSameAttributeValue($paymentOrders, 'payerAccount')) {
            $this->addError($attribute, Yii::t('edm',  'Payment orders must have same payer account'));
            return;
        }

        if (!$this->allPaymentOrdersHasSameAttributeValue($paymentOrders, 'payerInn')) {
            $this->addError($attribute, Yii::t('edm',  'Payment orders must have same payer INN'));
            return;
        }

        if (!$this->allPaymentOrdersHasSameAttributeValue($paymentOrders, 'payerCheckingAccount')) {
            $this->addError($attribute, Yii::t('edm',  'Payment orders must have same payer checking account'));
            return;
        }

        if (!$this->allPaymentOrdersHasSameAttributeValue($paymentOrders, 'payerBik')) {
            $this->addError($attribute, Yii::t('edm',  'Payment orders must have same payer bank BIK'));
            return;
        }
    }

    /**
     * @param PaymentOrderType[] $paymentOrders
     * @param string $attribute
     * @return bool
     */
    private function allPaymentOrdersHasSameAttributeValue(array $paymentOrders, string $attribute): bool
    {
        $isEmpty = function ($value) {
            return $value === '' || $value === null;
        };

        if (count($paymentOrders) === 0) {
            return true;
        }

        $firstValue = $paymentOrders[0]->$attribute;
        foreach ($paymentOrders as $paymentOrder) {
            $value = $paymentOrder->$attribute;
            $isTheSameValue = ($isEmpty($firstValue) && $isEmpty($value)) || $firstValue === $value;
            if (!$isTheSameValue) {
                return false;
            }
        }
        return true;
    }
}
