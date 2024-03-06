<?php

use kartik\widgets\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * В JS блоке присутствует костыль.
 * По задаче требуется сделать так, что если текущему пользователю доступен
 * только один терминал, то список выбора должен быть недоступным для выбора
 * Disabled не подходит, т.к. данные поля в таком случае не уходят в post
 * JS скрывает единственное поле у select
 */

echo $form->field($model, 'sender')->dropDownList($senderTerminalList, ['id' => 'wizardform-sender']);

echo $form->field($model, 'recipient')->widget(Select2::classname(), [
    'data'          => $recepientsList,
    'options'       => ['placeholder' => Yii::t('doc/mt', 'Select recipient'), 'class' => 'recepient-select', 'id' => 'wizardform-recipient'],
    'pluginOptions' => [
        'allowClear' => true,
        'templateSelection' => new JsExpression('function(item) {
            <!-- Убираем ID терминала из полного названия участника -->
            return item.text.replace("(" + item.id + ")", "");
        }'),
    ],
    'pluginEvents'  => [
        "change"          => "onRecipientChange",
        "select2-removed" => "resetTerminalCodesList",
    ],
]);


// Адрес для AJAX-запроса
$terminalCodesUrl = Url::to(['/certManager/cert/terminal-codes']);
// Начальное значение для инициализации списка кодов терминалов
$initialSelection = empty($model->terminalCode)
    ? 'null'
    : "'" . $model->terminalCode . "'";


$this->registerJs(<<<JS

    var recipientList = $('#wizardform-recipient');
    var senderList = $('#wizardform-sender');
    var terminalCodesList = $('#wizardform-terminal-code').get(0);
    var initialSelection = {$initialSelection};

    // Определение id терминала отправителя
    setSenderTerminalId();

    // Запуск события подбора кода терминала получателя
    onRecipientChange();

    // Событие измнения поля выбора отправителя
    $(senderList).on('change', function() {
        setSenderTerminalId();
    });

    // Событие выбора получателя из списка
    function onRecipientChange() {
        var currentRecipient = recipientList.val();
        if (currentRecipient != '') {
            $.ajax('{$terminalCodesUrl}?id=' + currentRecipient, {
                dataType: 'json'
            }).done(function(data) {
                if (terminalCodesList) {
                    resetTerminalCodesList();
                    for(optCnt=0; optCnt<data.results.length; optCnt++) {
                        var option = new Option(data.results[optCnt].text, data.results[optCnt].text);
                        if (initialSelection && option.value == initialSelection) {
                            option.selected = true;
                            initialSelection = null;
                        }
                        terminalCodesList.options[terminalCodesList.options.length] = option;
                    }
                }
            });
        }

        $(recipientList).parent().find('.block-terminal-id').detach();
        $(recipientList).parent().append('<div class="block-terminal-id">'+ currentRecipient +'</div>');
    };

    // Событие сброса списка кодов терминала получателя
    function resetTerminalCodesList() {
        if (terminalCodesList) {
            terminalCodesList.options.length = 0;
        }
    }

    // Получение и вывод информации по терминалу отправителя
    function setSenderTerminalId() {
        var senderTerminalId = $(senderList).val();

        if (senderTerminalId) {
            $(senderList).parent().find('.block-terminal-id').detach();
            $(senderList).parent().append('<div class="block-terminal-id">'+ senderTerminalId +'</div>');
        }

        var selectOptions = $('#wizardform-sender option');

        if (selectOptions.length <=1) {
            $('#wizardform-sender').children('option').hide();
        }
    }
JS
    , yii\web\View::POS_READY
);

$this->registerCss(
    ".block-terminal-id {
        margin: 0;
        margin-top: 10px;
        font-size: 15px;
        color: #6697C4;
        font-weight: bold;
    }"
);
