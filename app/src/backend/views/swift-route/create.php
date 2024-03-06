<?php

/* @var $this yii\web\View */
/* @var $model common\models\Participant */

$this->title = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/participant', 'Members'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// Вывести форму
echo $this->render('_form', [
    'model' => $model,
]);
