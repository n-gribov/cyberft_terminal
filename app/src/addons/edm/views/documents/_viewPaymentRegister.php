<?php

use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder;
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrderSearch;
use yii\data\ActiveDataProvider;

$extModel = $model->getExtModel()->one();

$registerId = $extModel ? $extModel->documentId : null;
$searchModel = new PaymentRegisterPaymentOrderSearch();

$dataProvider = empty($registerId)
        ? null
        //: $searchModel->search(['PaymentRegisterPaymentOrderSearch' => ['registerId' => $registerId]]);
        : new ActiveDataProvider([
            'query' => PaymentRegisterPaymentOrder::find()->where(['registerId' => $registerId])
        ]);
echo $this->render('readable/paymentOrderLog', [
	    'dataProvider' => $dataProvider,
		'model' => $model,
]);

?>
