<?php

use kartik\widgets\ActiveForm;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationFactory;
use common\helpers\Html;

?>
<style type="text/css">
    .form-inline .form-group {
        width: 100%;
    }

    .form-inline .form-group .form-control {
        width: 100% ;
    }

    .form-inline-field {
        display: inline-block;
    }

    .form-inline-field input {
        border: 0;
        width: 100%;
    }

    .form-inline-subfield input {
        border: 0;
        width: 100%;
    }

    .form-label {
        display: block;
    }

    .body-select {
        width: 100% !important;
    }
</style>
<?php

$form = ActiveForm::begin([
    'type'       => ActiveForm::TYPE_INLINE,
    'formConfig' => [
        'labelSpan' => 3,
        'deviceSize' => ActiveForm::SIZE_TINY,
        'showErrors' => true,
    ]
]);
?>

<div class="row">
    <div class="col-sm-12">
        <label>Тип операции</label>
        <?php
            echo Html::dropDownList(
                'operationType',
                null,
                ForeignCurrencyOperationFactory::getOperationTypes(),
                [
                    'prompt' => 'Выберите операцию...',
                    'class' => 'body-select form-control',
                    'id' => 'operationType'
                ]
            );
        ?>
    </div>
</div>
<?php ActiveForm::end() ?>
<?php
$script = <<<JS
    $('.body-select').on('change', function(e) {
       var type = $('#operationType').val();
       $('#fcoCreateModal .modal-body').html('');

       $.ajax({
            url: '/edm/foreign-currency-operation-wizard/create?type=' + type,
            type: 'get',
            success: function(answer) {
                // Добавляем html содержимое на страницу формы
                $('#fcoCreateModal .modal-body').html(answer);
                $('#fcoCreateModalButtons').show();
            }
        });
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);
?>