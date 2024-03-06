<?php
$this->title = Yii::t('app/raiffeisen', 'New customer');

// Вывести форму
echo $this->render('_form', compact('model', 'terminalAddressSelectOptions', 'signatureTypeSelectOptions'));
