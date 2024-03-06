<?php

namespace addons\edm\models\CollectionOrder;

class CollectionOrder extends Document1C {
	
	const TYPE       = 'CollectionOrder';
	const TYPE_LABEL = 'Инкассовое поручение';
	const LABEL      = 'Инкассовое поручение';


	public function rules() {
		return array_merge(
			[
				[
					['indicatorKbk', 'okato', 'indicatorReason', 'indicatorNumber', 'indicatorDate', 'indicatorType'],
					'default', 'value' => 0
				],
				[
					[
						'senderStatus', 'payerName', 'payerInn', 'beneficiaryInn', 'payerKpp', 'payerCheckingAccount',
						'payerCorrespondentAccount', 'beneficiaryCheckingAccount', 'beneficiaryCorrespondentAccount',
						'payerBank1', 'payerBank2', 'beneficiaryBank1', 'beneficiaryBank2', 'payerBik',
						'beneficiaryBik',
						'beneficiaryName', 'beneficiaryKpp', 'payType', 'indicatorKbk', 'okato', 'indicatorReason',
						'indicatorPeriod', 'indicatorNumber', 'indicatorDate', 'indicatorType', 'sum'
					], 'required'
				],
				[['paymentType'], 'safe']
			],
			parent::rules()
		);
	}
}
