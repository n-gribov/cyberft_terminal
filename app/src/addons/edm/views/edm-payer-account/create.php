<?php

$this->title                   = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'Payers Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Вывести форму редактирования
echo $this->render('_form', [
    'model' => $model,
    'name' => $name
]);
