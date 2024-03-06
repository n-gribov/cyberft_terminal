<?php

namespace common\modules\certManager\components\ssl;

class Exception extends \yii\base\Exception {

	const ERROR_INVALID_CERT_ID = 101;
	const ERROR_UNKNOWN_CERT_ID = 102;
    const ERROR_BROKEN_CERT_PATH = 103;

	public function getName() {
		return 'SSL Exception';
	}

}
