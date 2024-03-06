<?php

/* @var $this yii\web\View */
/* @var $model addons\swiftfin\models\SwiftfinTemplate */
/* @var $docTypes addons\swiftfin\models\form\WizardForm */

$this->title = Yii::t('doc', 'Document Template');
$this->params['breadcrumbs'][] = ['label' => Yii::t('doc', 'Document Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('_form', [
    'model' => $model,
    'docTypes' => $docTypes
]) ?>
