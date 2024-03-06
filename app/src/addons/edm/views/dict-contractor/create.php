<?php

/* @var $this yii\web\View */
/* @var $model addons\edm\models\DictContractor */

$this->title                   = Yii::t('app', 'Create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Contractors'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<?=$this->render('_form', [
    'model' => $model,
])?>
