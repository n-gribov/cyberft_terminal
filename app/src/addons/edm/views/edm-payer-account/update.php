<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

$this->title					 = Yii::t('edm', 'Payer account #{id}', ['id' => $model->number]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'Payers Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?=$this->render('_form', [
    'model' => $model,
])?>

