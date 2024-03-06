<?php
$this->title = Yii::t('doc', 'Document Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('doc', 'Document Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Вывести форму
echo $this->render('_form', [
    'model' => $model,
    'docTypes' => $docTypes
]);
