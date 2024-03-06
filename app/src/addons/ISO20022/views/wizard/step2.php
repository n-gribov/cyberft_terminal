<?php

use addons\ISO20022\models\form\WizardForm;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;

/* @var $model WizardForm */

?>
<div class="col-md-8">
    <?php
        $form = ActiveForm::begin([
            'method'	=> 'post',
            'action'	=> 'step3',
            'options'	=> [
                'enctype' => 'multipart/form-data',
                'name' => 'step2Form',
                'class' => 'iso-form'
            ],
        ]);

        echo $form->field($model, 'subject')->textInput();
        echo $form->field($model, 'descr')->textarea();
        echo $form->field($model, 'typeCode')->dropDownList($settings->getTypeCodeList());
        echo $form->field($model, 'file')->widget(
            kartik\file\FileInput::class,
            [
                'pluginOptions' => [
                    'showPreview' => false,
                    'showUpload' => false
                ]
            ]
        )->label('Выбор файла');

        ActiveForm::end();
    ?>
</div>
<?php if (!empty($model->fileName)) : ?>
	Загружен файл: <?= $model->fileName ?>
<?php endif ?>
<div class="row">
	<div class="row col-md-8">
		<div class="col-md-offset-4 col-md-8">
			<?=Html::a(Yii::t('app', 'Back'),	['index'], ['class' => 'btn btn-default'])?>
			<?=Html::button(Yii::t('app', 'Next'), ['class' => 'btn btn-primary btn-submit-step2'])?>
		</div>
	</div>
</div>

<?php

$script = <<< JS

    // Проверка состояния доступности кнопки отправки формы
    function submitButtonStatus() {

        // Получение значений требуемых полей
        var subject = $('#wizardform-subject').val();
        var description = $('#wizardform-descr').val();

        // Если какое-то поле пустое, делаем кнопку недоступной
        if (subject.length == 0 || description.length == 0) {
            $('.btn-submit-step2').addClass('disabled');
            return false;
        } else {
            $('.btn-submit-step2').removeClass('disabled');
            return true;
        }
    }

    // Отображение ошибки для незаполненных полей
    function showErrors() {
        var subject = $('#wizardform-subject').val();
        var description = $('#wizardform-descr').val();

        if (subject.length == 0) {
            // Выделяем поле с темой в случае ошибки
            $('.field-wizardform-subject').addClass('has-error');

            // И задаем текст ошибки
            $('.field-wizardform-subject .help-block').text('Необходимо заполнить «Тема».');
        } else {
            // Убираем класс в противном случае
            $('.field-wizardform-subject').removeClass('has-error');

            // И убираем текст ошибки
            $('.field-wizardform-subject .help-block').text('');
        }

        if (description.length == 0) {
            // Выделяем поле с темой в случае ошибки
            $('.field-wizardform-descr').addClass('has-error');

            // И задаем текст ошибки
            $('.field-wizardform-descr .help-block').text('Необходимо заполнить «Описание».');
        } else {
            // Убираем класс в противном случае
            $('.field-wizardform-descr').removeClass('has-error');

            // И убираем текст ошибки
            $('.field-wizardform-descr .help-block').text('');
        }
    }

    // Событие нажатия на кнопку отправки формы
    $('.btn-submit-step2').on('click', function(e) {
        //
        e.preventDefault();

        showErrors();
        // Если нужные поля заполнены,
        // кнопка доступна и действие можно совершить
        if (submitButtonStatus() === true) {
            step2Form.submit();
        }
    });

    // Инициализация проверки состояния доступности кнопки отправки формы
    submitButtonStatus();

    // События ввода значений в текстовые поля
    $('#wizardform-subject').on('keyup', function() {
        submitButtonStatus();
        showErrors();
    });

    $('#wizardform-descr').on('keyup', function() {
        submitButtonStatus();
        showErrors();
    });
JS;
//маркер конца строки, обязательно сразу, без пробелов и табуляции
$this->registerJs($script, yii\web\View::POS_READY);

?>
