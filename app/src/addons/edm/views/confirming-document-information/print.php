<?php

/** @var \yii\web\View $this */
/** @var \addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt $model */
/** @var array $signatures */

$attachedFiles = [];

echo $this->render('_view', compact('model', 'attachedFiles', 'signatures'));
