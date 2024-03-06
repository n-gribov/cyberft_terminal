<?php

use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $dataProvider ActiveDataProvider */

$this->title = (!empty($documentId)) ? Yii::t('app', 'Edit document') : Yii::t('edm', 'Create payment order');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;

// Вывести шаг визарда
echo $this->render('step' . $currentStep, [
    'model' => isset($model) ? $model : null, // для первого шага модели может не быть
    'currentStep' => $currentStep,
    'types' => isset($types) ? $types : null,
    'errors' => isset($errors) ? $errors : null,
    'data' => isset($data) ? $data : '',
]);

