<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::t('app/menu', 'Settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'ISO20022'), 'url' => Url::toRoute(['/ISO20022/documents'])];
$this->params['breadcrumbs'][] = $this->title;
?>


<?php
    $form = ActiveForm::begin([
        'method' => 'post',
        'action' => 'update-code',
    ]);
?>
<?=$form->field($model, 'code')?>
<?=$form->field($model, 'ru')?>
<?=$form->field($model, 'en')?>

<div class="form-group">
    <?= Html::submitButton($model->code ? Yii::t('app', 'Update') : Yii::t('app', 'Create'), ['class' => $model->code ? 'btn btn-primary' : 'btn btn-success']) ?>
</div>
<?php ActiveForm::end()?>

