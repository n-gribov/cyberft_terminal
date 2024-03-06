<?php

/* @var $this yii\web\View */
/* @var $model addons\edm\models\DictContractor */

$this->title                   = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'Beneficiary Contractors Directory'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// Вывести форму редактирования
echo $this->render('_form', [
    'model' => $model,
]);
