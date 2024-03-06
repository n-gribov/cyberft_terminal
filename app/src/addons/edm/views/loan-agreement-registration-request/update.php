<?php

/** @var \yii\web\View $this */
/** @var \addons\edm\models\LoanAgreementRegistrationRequest\LoanAgreementRegistrationRequestForm $model */
/** @var \addons\edm\models\DictCurrency[] $currencies */

$this->title = Yii::t('edm', 'Loan agreement registration request');

// Вывести страницу
echo $this->render('_form', compact('model', 'currencies'));
