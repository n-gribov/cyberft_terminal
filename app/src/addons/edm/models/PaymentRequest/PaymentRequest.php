<?php

namespace addons\edm\models\PaymentRequest;

use addons\edm\models\Document1C;

class PaymentRequest extends Document1C {

	const TYPE       = 'PaymentRequest';
	const TYPE_LABEL = 'Платежное требование';
	const LABEL      = 'Платежное требование';


	public function rules() {
		return array_merge([
			[
				[
					'sum', 'payerName', 'payerCheckingAccount', 'payerBank1', 'payerBank2', 'payerBik',
					'payerCorrespondentAccount', 'beneficiaryBank1', 'beneficiaryBank2', 'beneficiaryBik',
					'beneficiaryCorrespondentAccount', 'beneficiaryName', 'beneficiaryCheckingAccount', 'payType',
				], 'required'
			],
			[['paymentCondition1', 'paymentCondition2', 'paymentCondition3', 'paymentType'], 'safe']
		], parent::rules());
	}

	public function beforeValidate() {
		$this->documentSendDate = date('d.m.Y');
		return parent::beforeValidate();
	}
}
