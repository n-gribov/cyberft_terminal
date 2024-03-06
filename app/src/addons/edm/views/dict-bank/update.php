<?php

/* @var $this yii\web\View */
/* @var $model addons\edm\models\DictBank */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
		'modelClass' => Yii::t('edm', 'Bank'),
	]) . ' ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banks Directory'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->bik]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>

<?=$this->render('_form', [
	'model' => $model,
	'certs' => $certs
])?>

