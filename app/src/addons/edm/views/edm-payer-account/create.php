<?php

$this->title                   = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'Payers Accounts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=$this->render('_form', [
    'model' => $model,
    'name' => $name
])?>
