<?php

$this->title = Yii::t('app/menu', 'For approving');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php
echo $this->render('_search',
    [
    'model' => $searchModel,
]);
?>

<?php
echo $this->render('_list',
    [
    'filterModel' => $searchModel,
    'dataProvider' => $dataProvider,
]);
?>
