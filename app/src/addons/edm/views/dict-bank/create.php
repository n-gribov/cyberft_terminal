<?php

/* @var $this yii\web\View */
/* @var $model addons\edm\models\DictBank */

$this->title                   = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banks Directory'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?=$this->render('_form', [
    'model' => $model,
])?>
