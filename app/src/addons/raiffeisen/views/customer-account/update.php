<?php
$this->title = Yii::t('app/raiffeisen', 'Account') . ' ' . \yii\helpers\Html::encode($model->number);

// Вывести форму
echo $this->render('_form', compact('model', 'currencySelectOptions'));
