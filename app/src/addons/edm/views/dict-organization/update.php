<?php

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => Yii::t('edm', 'Organization')])
        . ' ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'Currencies Directory'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

// Вывести форму редактирования
echo $this->render('_form', [
    'model' => $model
]);
