<?php
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('doc', 'Verify document #{id}', ['id' => Yii::$app->request->get('id')]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Documents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$js = "
        $('.options').hide();
        $('.mt-new-ui input').attr('disabled','disabled');
        $('.mt-new-ui select').attr('disabled','disabled');
        $('.mt-new-ui textarea').attr('disabled','disabled');

        var activeTags = [" . $verifyTags . '];

        ';
$js .= <<<JS
        activeTags.forEach(
            function(item)
            {
                $("input[name*='"+item+"']").removeAttr('disabled').addClass('verifyTag').val('');
                $("select[name*='"+item+"']").removeAttr('disabled').addClass('verifyTag').val('');
                $("textarea[name*='"+item+"']").removeAttr('disabled').addClass('verifyTag').val('');
            }
        );

        $('[disabled = disabled]').removeAttr('placeholder');
JS
;
$this->registerJs($js, yii\web\View::POS_READY);
?>
<div class="panel-body">
    <?php $form = ActiveForm::begin([
        'id' => 'docEdit',
        'type' => ActiveForm::TYPE_HORIZONTAL,
        'fullSpan' => 12,
        'formConfig' => ['labelSpan' => 3],
        'enableClientValidation' => false,
        'enableAjaxValidation' => false,
    ]);
    ?>

    <div class="row">
        <div class="col-sm-12">
            <div class="mt20 mt-new-ui">
                <?php
                if (isset($model->view)) {
                    // Вывести страницу
                    print $this->render($model->view, ['model' => $model, 'form' => $form]);
                } else {
                    // Вывести страницу
                    print $this->render($model->getViewPath($model->type), ['model' => $model, 'form' => $form]);
                }
                ?>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-4"  style="margin-top:20px;">
            <?= Html::submitButton(Yii::t('doc', 'Verify'), ['class' => 'btn btn-success']) ?>
            <?= Html::a(Yii::t('doc', 'For modification'),
                "#",
                [
                    'class' => 'btn btn-info',
                    'data-toggle' => 'modal',
                    'data-target' => '#correctionReasonModal'
                ]
            )?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>

<div class="modal fade" id="correctionReasonModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">
                    <?=Yii::t('doc/swiftfin', 'Enter the reason for modification')?>
                </h4>
            </div>
            <?php $form = ActiveForm::begin([
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'action' => Url::to('/swiftfin/documents/send-correction')
            ]);
            ?>
            <div class="modal-body">
                <textarea class="form-control" name="correctionReason" rows="5"></textarea>
                <input type="hidden" name="documentId" value="<?=Yii::$app->request->get('id')?>">
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">
                    <?=Yii::t('app', 'Close')?>
                </button>
                <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-primary']) ?>
            </div>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</div>