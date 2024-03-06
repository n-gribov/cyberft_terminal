<?php

use yii\bootstrap\ActiveForm;
use yii\web\JqueryAsset;

/** @var \yii\web\View $this */
/** @var \addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestForm $model */

?>

<div class="modal" id="cancel-document-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><?= Yii::t('edm', 'Document call-off') ?></h4>
            </div>
            <div class="modal-body">
                <?php
                $form = ActiveForm::begin();
                echo $form->field($model, 'messageForBank')->textarea(['rows' => 5]);
                echo $form->field($model, 'documentNumber')->hiddenInput()->label(false);
                echo $form->field($model, 'documentDate')->hiddenInput()->label(false);
                ActiveForm::end();
                ?>
                <div class="error-message error"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                <button type="submit" class="btn btn-primary"><?= Yii::t('edm', 'Call off the document') ?></button>
            </div>
        </div>
    </div>
</div>

<style>
    #cancel-document-modal .error-message {
        color: #b72625;
    }
</style>

<?php

$this->registerJsFile(
    '@web/js/vtb-document-cancellation-form.js',
    ['depends' => [JqueryAsset::className()]]
);
$documentId = $model->document->id;
$this->registerJs("(new VtbDocumentCancellationForm($documentId)).initialize()", yii\web\View::POS_READY);
