<?php

/** @var $this yii\web\View */
/** @var $model \addons\raiffeisen\models\RaiffeisenCustomerAccount */
/** @var $currencySelectOptions array */

$this->title = Yii::t('app/raiffeisen', 'New account');

echo $this->render('_form', compact('model', 'currencySelectOptions'));
