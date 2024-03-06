<?php

/* @var $this yii\web\View */
/* @var $model common\models\Participant */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Participant',
]) . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/participant', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<?= $this->render('_form', [
    'model' => $model,
]) ?>
