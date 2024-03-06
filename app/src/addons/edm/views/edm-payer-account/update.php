<?php

$this->title = Yii::t('edm', 'Payer account #{id}', ['id' => $model->number]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'Payers Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

// Вывести форму редактирования
echo $this->render('_form', [
    'model' => $model
]);

