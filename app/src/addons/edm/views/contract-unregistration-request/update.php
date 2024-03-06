<?php

/** @var \yii\web\View $this */
/** @var \addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm $model */
/** @var \addons\edm\models\DictCurrency[] $currencies */

$this->title = Yii::t('edm', 'Contract unregistration request');
// Вывести форму
echo $this->render('_form', compact('model'));
