<?php

/* @var $this yii\web\View */
/* @var $model addons\edm\models\DictContractor */

$this->title = Yii::t('app', 'Update {modelClass}: ', ['modelClass' => Yii::t('edm', 'Contractor')])
        . ' ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Contractors Directory'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

// Вывести форму редактирования
echo $this->render('_form', [
    'model' => $model
]);
