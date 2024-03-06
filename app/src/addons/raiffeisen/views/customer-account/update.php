<?php

/** @var $this yii\web\View */
/** @var $model \addons\raiffeisen\models\RaiffeisenCustomerAccount */
/** @var $currencySelectOptions array */

$this->title = Yii::t('app/raiffeisen', 'Account') . ' ' . \yii\helpers\Html::encode($model->number);

echo $this->render('_form', compact('model', 'currencySelectOptions'));
