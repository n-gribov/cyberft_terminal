<?php
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\widgets\ActiveForm;

// Создать детализированное представление
echo yii\widgets\DetailView::widget([
    'model' => $model,
    'template' => '<tr><th width="30%">{label}</th><td>{value}</td></tr>',
    'attributes' => [
        'typeCode',
        'sender',
        'recipient',
        'content:ntext'
    ]
]);

$form = ActiveForm::begin(['type' => ActiveForm::TYPE_HORIZONTAL, 'fullSpan' => 12]);
?>
    <div class="form-group">				
        <div class="col-sm-offset-2 col-sm-10 pull-right">
            <?=Html::a(Yii::t('app', 'Back'), ['/swiftfin/wizard/step2'], ['name' => 'send', 'class' => 'btn btn-default']) ?>
            <?=Html::submitButton(Yii::t('app', 'Confirm'), ['name' => 'send', 'class' => 'btn btn-primary']) ?>
            <?=Html::a(Yii::t('app', 'Print'), Url::toRoute(['/swiftfin/wizard/wizard-print']), 
                    ['name' => 'print', 'class' => 'btn btn-info', 'target' => '_blank']) ?>
        </div>
    </div>
<?php
ActiveForm::end();
