<?php

/* @var $this yii\web\View */
/* @var $model addons\edm\models\DictContractor */

$this->title = Yii::t('app', 'Update') . ' ' . $model->name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Contractors Directory'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<?=$this->render('_form', [
    'model' => $model,
])?>
