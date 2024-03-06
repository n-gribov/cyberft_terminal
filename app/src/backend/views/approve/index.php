<?php

$this->title = Yii::t('app/menu', 'Approving log');
$this->params['breadcrumbs'][] = $this->title;

// Вывести форму поиска
echo $this->render('_search', [
    'model' => $searchModel,
]);

// Вывести список
echo $this->render('_list', [
    'filterModel' => $searchModel,
    'dataProvider' => $dataProvider,
]);
