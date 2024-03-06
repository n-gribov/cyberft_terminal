<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use yii\jui\JuiAsset;

JuiAsset::register($this);

$script = <<<JS
   $('.btn-columns-settings').on('click', function(e) {
        e.preventDefault();
        $('#columnsSettingsModal').modal();
   });

   $('.btn-save-columns-settings').on('click', function() {

       // Проверка, чтобы хотя бы один чекбокс с полем был выбран
       var checkedColumns = $('#form-settings #sortable input:checked').length;

       if (checkedColumns === 0) {
            alert('Необходимо указать хотя бы одну колонку для отображения!');
            return false;
       }

       // Отправка формы сохранения настроек колонок
       $('#form-settings').submit();
   });

   $("#sortable").sortable({
        cursor: "move"
   });

   $("#sortable").disableSelection();

   // Возможность перетаскивания окна
   $('#columnsSettingsModal .modal-dialog').draggable(
        {
            cursor: "move",
            containment: "window",
            scroll: false,
            create: function(event, ui) {

                var modalBlock = $('#columnsSettingsModal .modal-dialog');

                // Определение позиционирования на экране
                var left = Math.max(0, (($(window).width() - modalBlock.width()) / 2) + $(window).scrollLeft()) + "px";

                var settings = {
                    margin: 0,
                    top: 35,
                    left: left,
                    position: 'absolute'
                };

                modalBlock.css(settings);
            }
        }
   );

   $('#columnsSettingsModal').on('hidden.bs.modal', function (e) {

        var modalBlock = $('#columnsSettingsModal .modal-dialog');

        // Удаляем у блока с формой свойства, присвоенные при перетаскивании
        modalBlock.attr('style', '');

        // Задаем свойства для правильного позиционирования после закрытия формы
        var left = Math.max(0, (($(window).width() - modalBlock.width()) / 2) + $(window).scrollLeft()) + "px";

        var settings = {
            margin: 0,
            top: 35,
            left: left,
            position: 'absolute'
        };

        modalBlock.css(settings);
   });

   // Событие выбора всех чекбоксов в форме
   $('#columns-settings-check-all').on('click', function() {

        // Если чекбокс заполнен, то заполняем чекбоксы, иначе снимаем выделение

        var statusCheckAll = $(this).prop("checked");

        var inputs = $('#sortable input[type=checkbox]');

        inputs.each(function() {
            $(this).prop("checked", statusCheckAll);
        });
   });

JS;

$this->registerJs($script, yii\web\View::POS_READY);

$this->registerCss("
    .table-columns-settings-td-left {
        width: 10%;
    }

    .table-columns-settings-td-right {
        width: 90%;
    }

    #columnsSettingsModal .modal-dialog {
        width: 400px;
    }

    #columnsSettingsModal .row {
        position: relative;
    }

    #sortable { list-style-type: none; margin: 0; padding: 0;}
    #sortable li { margin: 0 0 6px 0; padding: 0.4em; padding-left: 1.5em; position: relative}
    #sortable li span {position: absolute; top: 10px; right: 5px;}

    #sortable input[type=checkbox] {
        margin-right: 15px;
    }

    #sortable .ui-state-default {
        color: #000;
    }

    .checkall-block {
        border-bottom: 2px solid #ddd;
        margin-bottom: 20px;
    }

    #columns-settings-check-all {
        margin-right: 10px;
    }
");

// Содержимое шапки модального окна
$header = '<h4 class="modal-title">' . Yii::t('app', 'Columns settings') . '</h4>';

// Содержимое подвала модального окна
$footer = '<button type="button" class="btn btn-default" data-dismiss="modal">' . Yii::t('app', 'Close') . '</button>
        <button type="button" class="btn btn-primary btn-save-columns-settings">' . Yii::t('app', 'Save') . '</button>';

Modal::begin([
    'id' => 'columnsSettingsModal',
    'header' => $header,
    'footer' => $footer
]);

?>

<div class="row">
    <div class="col-md-12 modal-left">


            <?php
                // Вывод формы с настройками журнала
                ActiveForm::begin([
                    'id' => 'form-settings',
                    'action' => '/document/save-column-settings',
                    'method' => 'post'
                ]);

                echo Html::hiddenInput('data[user]', Yii::$app->user->id);
                echo Html::hiddenInput('data[type]', $listType);

                echo '<div class="checkall-block">';
                    echo Html::checkbox("checkAll", false, ['id' => 'columns-settings-check-all']);
                    echo Html::label(Yii::t('app', 'Select all'), 'columns-settings-check-all');
                echo '</div>';

                echo '<ul id="sortable">';
                    foreach($settingsColumns as $column=>$value) {
                        echo '<li class="ui-state-default">';
                            echo Html::hiddenInput("settings[" . $column . "]", 0);
                            echo Html::checkbox("settings[" . $column. "]", $value);
                            echo Html::label($model->getAttributeLabel($column));
                            echo '<span class="ui-icon ui-icon-arrowthick-2-n-s"></span>';
                        echo '</li>';
                    }
                echo '</ul>';
                ActiveForm::end();
            ?>
    </div>
</div>

<?php Modal::end(); ?>