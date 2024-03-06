<?php

use addons\edm\models\StatementRequest\StatementRequestType;
use kartik\widgets\ActiveForm;
use kartik\widgets\DatePicker;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use kartik\select2\Select2;

$modalOptions = [
    'header' => '<h4 class="statement-request-modal-header">' . Yii::t('edm', 'Statement request') . '</h4>',
    'id' => 'sendRequestForm',
    'options' => [
        'tabindex' => false
    ],
];

// Не отображаем встроенную кнопку вызова формы
if (!isset($hideToggleButton)) {
    $modalOptions['toggleButton'] = [
        'tag' => 'button',
        'class' => 'btn btn-success',
        'label' => Yii::t('edm', 'Statement request'),
    ];
}

Modal::begin($modalOptions);

$model            = new StatementRequestType();
$model->startDate = date('Y-m-d');
$model->endDate   = date('Y-m-d');

if (isset($account)) {
    $model->accountNumber = $account->number;
}

?>

<?php $form           = ActiveForm::begin(['action' => Url::toRoute(['/edm/edm-payer-account/send-request'])]); ?>
<?= $form->field($model, 'accountNumber')->widget(Select2::classname(), [
    'options'       => ['placeholder' => 'Поиск счета по имени или номеру ...', 'class' => 'has-success'],
    'theme' => Select2::THEME_BOOTSTRAP,
    'pluginOptions' => [
        'allowClear'         => true,
        'minimumInputLength' => 0,
        'ajax'               => [
            'url'      => \yii\helpers\Url::to(['/edm/edm-payer-account/simple-list']),
            'dataType' => 'json',
            'delay'    => 250,
            'data'     => new JsExpression('function(params) { return {q:params.term}; }'),
        ],
        'templateResult'     => new JsExpression('function(item) {
            if (!item.number) return item.text; return item.number + " (" + item.name + ")";
        }'),
        'templateSelection'  => new JsExpression('function(item) {
            if (!item.number) return item.text; return item.number + " (" + item.name + ")";
        }'),
    ],
    'pluginEvents'  => [
        'select2:select' => 'function(e) {
            submitButtonStatus();
        }',
        'select2:unselect' => 'function(e) {
            $("#statementrequesttype-accountnumber").val("");
            submitButtonStatus();
        }'
    ],
]);?>

<?php
    // Радиокнопки для выбора режима подписания
    echo Html::radioList('request-type', 'today', [
        'today' => Yii::t('edm', 'Today'),
        'yesterday' => Yii::t('edm', 'Yesterday'),
        'period' => Yii::t('edm', 'For period')
    ],
    [
        'class' => 'request-type'
    ]);

?>

<div class="row period-range">
    <div class="col-md-4">
        <?=
        $form->field($model, 'startDate')->widget(DatePicker::className(),
            [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                ],
                'layout'  => '{input}{remove}',
            ])
        ?>
    </div>
    <div class="col-md-4">
        <?=
        $form->field($model, 'endDate')->widget(DatePicker::className(),
            [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'pickerButton' => false
                ],
                'layout'  => '{input}{remove}',
            ])
        ?>
    </div>
</div>

<div class="clearfix">
    <?= Html::submitButton(Yii::t('app', 'Send'), ['class' => 'btn btn-success pull-right btn-send-request-form', 'disabled' => true]) ?>
</div>

<?php ActiveForm::end(); ?>

<?php Modal::end(); ?>

<?php

$this->registerCss('
    .request-type {
        margin-bottom: 10px;
    }

    .request-type label {
        margin-right: 15px;
    }

    .statement-request-modal-header {
        margin: 0;
    }

    .period-range .col-md-4 {
        width: 38%;
    }

    #sendRequestForm .modal-dialog {
        width: 500px;
    }

    #sendRequestForm .glyphicon-remove {
        font-size: 16px !important;
    }

    #sendRequestForm .glyphicon {
        top: 3px;
        left: 1px;
    }

    #sendRequestForm .input-group-addon {
        padding: 7px 6px;
    }
');

$script = <<< JS
    // Событие переключения типа запроса
    $('.request-type input[name=request-type]').on('change', function() {
        checkRadioValue();
    });

    // Получение необходимой даты
    function getDate(type) {

        var now = new Date();

        if (type == 'yesterday') {
            now.setDate(now.getDate() - 1);
        }

        var day = now.getDate().toString();
        var month = now.getMonth() + 1;
        month = month.toString();
        var year = now.getFullYear().toString();

        if (month.length == 1) {
            month = '0' + month;
        }

        date = year + '-' + month + '-' + day;

        return date;
    }

    // Проверка состояние радиокнопок выбора типа периода
    function checkRadioValue() {

        var value = $('.request-type input[name=request-type]:checked').val();

        // Обработка события выбора радиокнопок
        if (value === 'yesterday') {
            $('.period-range').slideUp();
            date = getDate('yesterday');

            $('#statementrequesttype-startdate').val(date);
            $('#statementrequesttype-enddate').val(date);
        } else if (value === 'today') {
            $('.period-range').slideUp();
            date = getDate('today');

            $('#statementrequesttype-startdate').val(date);
            $('#statementrequesttype-enddate').val(date);
        } else if (value === 'period') {
            $('.period-range').slideDown();
        }
    }

    // События изменения полей периода
    $('#statementrequesttype-startdate, #statementrequesttype-enddate').on('change', function() {
         submitButtonStatus();
    });

    // Проверка доступности submit-кнопки
    function submitButtonStatus() {
        var disabled = true;

        // Проверка заполненности счета
        var accountNumber = $('#statementrequesttype-accountnumber').val();

        var startDate = $('#statementrequesttype-startdate').val();
        var endDate = $('#statementrequesttype-enddate').val();

        // Проверка дат периода и номера счета
        if (startDate.length !== 0 &&
            endDate.length !== 0 &&
            accountNumber.length !== 0) {
            disabled = false;
        }

        $('.btn-send-request-form').prop('disabled', disabled);
    }

    checkRadioValue();
    submitButtonStatus();
JS;

$this->registerJs($script, yii\web\View::POS_READY);

?>
