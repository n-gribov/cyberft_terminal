<?php

use kartik\form\ActiveForm;
use kartik\field\FieldRange;
use kartik\datecontrol\DateControl;

?>

<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?=Yii::t('edm', 'Find')?></h4>
            </div>
            <div class="modal-body">
                <?php
                $formParams = ['method' => 'get', 'id' => 'searchModalForm'];
                $form = ActiveForm::begin($formParams);
                if (!isset($aliases)) {
                    $aliases = [];
                }
                ?>

                <div class="row ow-fluid log-search">
                    <div class="col-xs-12">
                        <?= FieldRange::widget([
                            'form' => $form,
                            'model' => $model,
                            'attribute1' => 'dateCreateFrom',
                            'attribute2' => 'dateCreateBefore',
                            'type' => FieldRange::INPUT_WIDGET,
                            'widgetClass' => DateControl::classname(),
                            'widgetOptions1' => [
                                'saveFormat' => 'php:Y-m-d'
                            ],
                            'widgetOptions2' => [
                                'saveFormat' => 'php:Y-m-d'
                            ],
                            'separator' => Yii::t('doc', '&larr; to &rarr;'),
                        ]); ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?=Yii::t('app', 'Close')?></button>
                <button type="button" class="btn btn-primary btn-modal-search"><?=Yii::t('app', 'Search')?></button>
            </div>
        </div>
    </div>
</div>

<?php

$script = <<<JS
    // Submit формы поиска по дате
    $('.btn-modal-search').on('click', function(e) {
        e.preventDefault();
        $('#searchModalForm').submit();
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);


?>