<?php

use kartik\datecontrol\DateControl;
use kartik\field\FieldRange;
use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
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
                $formParams = ['id' => 'searchModalForm', 'method' => 'get', 'action' => Url::toRoute(['payment-order'])];
                $form = ActiveForm::begin($formParams);
                if (!isset($aliases)) {
                        $aliases = [];
                }
            ?>
            <div class="row ow-fluid log-search" >
                <div class="col-xs-12">
                        <?= FieldRange::widget([
                            'form' => $form,
                            'model' => $model,
                            'attribute1' => 'startDate',
                            'attribute2' => 'endDate',
                            'class' => 'form-control',
                            'type' => FieldRange::INPUT_WIDGET,
                            'widgetClass' => DateControl::classname(),
                            'widgetOptions1' => [
                                'saveFormat' => 'php:Y-m-d'
                            ],
                            'widgetOptions2' => [
                                'saveFormat' => 'php:Y-m-d'
                            ],
                            'separator' => Yii::t('doc', '&larr; to &rarr;'),
                            'label' => Yii::t('edm', 'Date Range'),
                        ]); ?>
                </div>
            </div>
            <div class="row ow-fluid log-search" >
                <div class="col-xs-12">
                  <?php
                        $msgAccount = Yii::t('edm', 'Account');
                        $msgTitle = Yii::t('edm', 'Title');
                        echo $form->field($model, 'payerAccount')->widget(Select2::classname(), [
                            'options'       => ['placeholder' => Yii::t('edm', 'Search by title or account number ...')],
                            'pluginOptions' => [
                                'allowClear'         => true,
                                'minimumInputLength' => 0,
                                'ajax'               => [
                                    'url'      => Url::to(['edm-payer-account/list']),
                                    'dataType' => 'json',
                                    'delay'    => 250,
                                    'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
                                ],
                                'templateResult' => new JsExpression(<<<JS
                                    function(item) {
                                        if (!item.number) {
                                            return item.text;
                                        }
                                        return '$msgAccount: ' + item.number + ' $msgTitle: ' + item.name;
                                    }
                                JS),
                                'templateSelection' => new JsExpression(<<<JS
                                    function(item) {
                                        if (!item.number) {
                                            return item.text;
                                        }
                                        return '$msgAccount: ' + item.number + ' $msgTitle: ' + item.name;
                                    }
                                JS),
                            ],
                        ])->label(Yii::t('edm', 'Payer'));
                        ?>  
                </div>
            </div>
            <?php ActiveForm::end(); ?>
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
    // Submit формы поиска
    $('.btn-modal-search').on('click', function(e) {
        e.preventDefault();
        $('#searchModalForm').submit();
    });
JS;

$this->registerJs($script, View::POS_READY);
