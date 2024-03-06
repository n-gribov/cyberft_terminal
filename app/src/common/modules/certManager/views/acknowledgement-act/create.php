<?php

use kartik\widgets\ActiveForm;
use kartik\widgets\DateTimePicker;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\certManager\models\CertAcknowledgementActForm */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Формирование акта о признании электронной подписи';

$dateTimePickerOptions = [
    'pluginOptions' => [
        'format'         => 'dd.mm.yyyy',
        'todayHighlight' => true,
        'weekStart'      => 1,
        'autoclose'      => true,
        'startView'      => 4,
        'minView'        => 2
    ]
];

$this->registerJs(<<<JS
    function applyPassportFieldsMasks() {
        var country = $('#certacknowledgementactform-certownerpassportcountry').val();
        var maskedFields = $('.passport-fields input[data-inputmask]');
        if (/^\s*(рф|россия|российская федерация)\s*$/i.test(country)) {
            maskedFields.inputmask();
        } else {
            maskedFields.inputmask('remove');
        }
    }
    $('#certacknowledgementactform-certownerpassportcountry').on('keyup', applyPassportFieldsMasks);
    $('#certificate-data-link').on('click', function() {
        $('#certificate-data-block').toggle('slow');
        return false;
    });
    applyPassportFieldsMasks();
JS
);

?>

<?php $form = ActiveForm::begin(['method'	=> 'post', 'id' => 'act-form']); ?>

<div class="alert alert-info">
    Заполните форму автоматического формирования акта признания электронной подписи
</div>

<div class="row">
    <div class="col-md-12"><?= $form->field($model, 'participantFullName') ?></div>
</div>

<h3>Данные владельца ключа</h3>
<div class="row">
    <div class="col-md-9"><?= $form->field($model, 'certOwnerFullName')->label('ФИО полностью') ?></div>
</div>
<div class="row">
    <div class="col-md-9"><?= $form->field($model, 'certOwnerPosition')->label('Должность') ?></div>
</div>
<div class="row passport-fields">
    <div class="col-md-3">
        <?= $form->field($model, 'certOwnerPassportSeries')
            ->textInput(['data-inputmask' => "'mask': '9999', 'clearIncomplete': true"])
            ->label('Серия')
        ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'certOwnerPassportNumber')
            ->textInput(['data-inputmask' => "'mask': '999999', 'clearIncomplete': true"])
            ->label('Номер')
        ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'certOwnerPassportAuthorityCode')
            ->textInput(['data-inputmask' => "'mask': '999-999', 'clearIncomplete': true"])
            ->label('Код подразделения')
        ?>
    </div>
    <div class="col-md-3"><?= $form->field($model, 'certOwnerPassportCountry')->label('Страна выдачи') ?></div>
</div>
<div class="row">
    <div class="col-md-9"><?= $form->field($model, 'certOwnerPassportAuthority')->label('Кем выдан') ?></div>
    <div class="col-md-3">
        <?= $form->field($model, 'certOwnerPassportIssueDate')
            ->widget(DateTimePicker::classname(), $dateTimePickerOptions)
            ->label('Дата выдачи') ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'agreementType')->dropDownList($model->agreementTypeLabels(), ['prompt' => 'Выберите тип...']) ?>
    </div>
    <div class="col-md-3"><?= $form->field($model, 'agreementNumber') ?></div>
    <div class="col-md-3">
        <?= $form->field($model, 'agreementDate')->widget(DateTimePicker::classname(), $dateTimePickerOptions) ?>
    </div>
</div>

<h3>Данные уполномоченного представителя клиента</h3>
<div class="row">
    <div class="col-md-9">
        <?=
        $form->field($model, 'signerFullName')
        ->textInput([
            'placeholder' => 'Иванова Ивана Ивановича',
            'data' => [
                'toggle' => 'tooltip',
                'placement' => 'right',
                'title' => 'ФИО в родительном падеже'
            ]
        ])->label('В лице: ФИО полностью')
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        <?=
        $form->field($model, 'signerPosition')
            ->textInput([
                'placeholder' => 'Генерального директора',
                'data' => [
                    'toggle' => 'tooltip',
                    'placement' => 'right',
                    'title' => 'Должность в родительном падеже'
                ]
            ])->label('Занимающего должность')
        ?>
    </div>
</div>
<div class="row">
    <div class="col-md-9"><?= $form->field($model, 'signerAuthority')->label('Действует на основании (доверенности, устава и т.п.)...') ?></div>
</div>

<h3><a href="#" id="certificate-data-link">Данные сертификата</a></h3>
<div id="certificate-data-block">
    <?php
        $certFields = [
            'certNotBefore'   => 'Дата начала действия',
            'certNotAfter'    => 'Дата окончания действия',
            'certFingerprint' => 'Отпечаток'
        ];
    ?>
    <?php foreach ($certFields as $fieldId => $label) : ?>
        <div class="row">
            <div class="col-md-12"><?= $form->field($model, $fieldId)->textInput(['readonly' => true]) ->label($label) ?></div>
        </div>
    <?php endforeach ?>

    <div class="row">
        <div class="col-md-12">
            <label class="control-label">Владелец</label>
            <pre><?= trim(preg_replace('/\//', "\r\n", $model->certSubject)) ?></pre>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <label class="control-label">Кем выдан</label>
            <pre><?= trim(preg_replace('/\//', "\r\n", $model->certIssuer)) ?></pre>
        </div>
    </div>
</div>

<div class="text-right">
    <?php
    $backUrlParams = $model->certId
        ? ['/certManager/cert/view', 'id' => $model->certId]
        : ['/autobot/view', 'id' => $model->autobotId];
    echo Html::a('Назад', $backUrlParams, ['class' => 'btn btn-info']);
    echo ' ';
    echo Html::submitButton('Сформировать акт', ['class' => 'btn btn-success'])
    ?>
</div>

<?php ActiveForm::end(); ?>

<style>
    #act-form .input-group-addon .glyphicon {
        font-size: 18px;
    }
    #act-form .has-error .input-group-addon {
        background-color: #f2dede;
    }
    #act-form input[readonly] {
        background-color: #f5f5f5;
        border: 1px solid #ccc;
    }
    #certificate-data-block {
        display: none;
    }
    .tooltip {
        white-space: nowrap;
    }
    .tooltip-inner {
        min-width:220px;
    }
</style>
