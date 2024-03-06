<?php

/** @var \yii\web\View $this */
/** @var \addons\edm\models\LoanAgreementRegistrationRequest\ContractUnregistrationRequestForm $model */

$this->title = Yii::t('edm', 'Contract unregistration request');
// Вывести форму
echo $this->render('_form', compact('model'));
