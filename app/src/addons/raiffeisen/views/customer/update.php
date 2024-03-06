<?php
$this->title = $model->shortName;

// Вывести форму
echo $this->render('_form', compact('model', 'terminalAddressSelectOptions', 'signatureTypeSelectOptions'));
