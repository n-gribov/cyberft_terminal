<?php

/** @var $this yii\web\View */
/** @var $model \addons\raiffeisen\models\RaiffeisenCustomer */
/** @var $terminalAddressSelectOptions array */
/** @var $signatureTypeSelectOptions array */

$this->title = $model->shortName;

echo $this->render('_form', compact('model', 'terminalAddressSelectOptions', 'signatureTypeSelectOptions'));
