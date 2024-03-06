<?php

namespace addons\edm\models\ForeignCurrencySellRequest;

use addons\edm\models\ForeignCurrencyPurchaseRequest\ForeignCurrencyPurchaseRequest;

class ForeignCurrencySellRequest extends ForeignCurrencyPurchaseRequest
    {

	const TYPE  = 'ForeignCurrencySellRequest';
	const LABEL = 'Заявление на продажу валюты';

	public function attributeLabels() {
		return array_merge(
			parent::attributeLabels(), [
				'accountRub'      => 'Рубли зачислить на счет',
				'accountCurrency' => 'Валюту списать со счета',
			]
		);
	}
}
