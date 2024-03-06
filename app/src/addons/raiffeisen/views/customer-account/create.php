<?php
$this->title = Yii::t('app/raiffeisen', 'New account');

// Вывести форму
echo $this->render('_form', compact('model', 'currencySelectOptions'));
