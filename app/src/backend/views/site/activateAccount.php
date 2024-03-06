<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('app/user', 'Account activate');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-reset-password">
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'account-activate']); ?>
                <?= $form->field($model, 'key')->input('text') ?>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('app/user', 'Activate'), ['class' => 'btn btn-primary']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>