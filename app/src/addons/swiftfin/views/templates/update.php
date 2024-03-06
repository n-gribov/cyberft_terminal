<?php
$this->title = Yii::t('doc', 'Document Template').' "'.$model->title.'"';
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Swiftfin Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');

// Вывести форму
echo $this->render('_form', [
    'model' => $model,
    'docTypes' => $docTypes
]);

