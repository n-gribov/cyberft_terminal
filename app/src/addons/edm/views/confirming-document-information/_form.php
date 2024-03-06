<?php

use addons\edm\helpers\EdmHelper;
use addons\edm\models\DictOrganization;
use yii\web\JqueryAsset;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use yii\jui\DatePicker;
use kartik\select2\Select2;
use common\helpers\Html;
use yii\bootstrap\Modal;

/** @var \addons\edm\models\ConfirmingDocumentInformation\ConfirmingDocumentInformationExt $model */

$this->title = Yii::t('edm', 'Confirming document information');

// Получение списка организаций
$organizations = Yii::$app->terminalAccess->query(DictOrganization::className())->all();
$organizationsList = [];
foreach($organizations as $organization) {
    $organizationsList[$organization->id] = $organization->name . ', ИНН: ' . $organization->inn;
}

$banksByOrganization = EdmHelper::getAvailableBanksByOrganization(Yii::$app->user->identity);
$banksByOrganizationJson = empty($banksByOrganization) ? '{}' : json_encode($banksByOrganization);

$form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'class' => 'edm-cdi-form']]);

?>
<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'number')?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'date')?>
        <?php MaskedInput::widget([
            'id'            => 'confirmingdocumentinformationext-date',
            'name'          => 'confirmingdocumentinformationext-date',
            'mask'          => '99.99.9999',
            'clientOptions' => [
                'placeholder' => 'dd.MM.yyyy',
            ]
        ])?>
        <?php DatePicker::widget([
            'id'         => 'confirmingdocumentinformationext-date',
            'dateFormat' => 'dd.MM.yyyy',
        ]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form
            ->field($model, 'contractPassport')
            ->widget(
                MaskedInput::class,
                [
                    'mask' => '99999999/9999/9999/9/9',
                ]
            )
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php
            if ($model->isNewRecord && count($organizationsList) === 1) {
                $model->organizationId = array_keys($organizationsList)[0];
            }
        ?>
        <?= $form->field($model, 'organizationId')->widget(
            Select2::class,
            [
                'data' => $organizationsList,
                'options' => ['prompt' => ''],
            ])
        ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?php
            $bankSelectOptions = $banksByOrganization[$model->organizationId] ?? [];
            $bankFieldOptions = [];
            if (count($bankSelectOptions) > 1) {
                $bankFieldOptions['prompt'] = '';
            } else if (count($bankSelectOptions) === 1) {
                $model->bankBik = array_keys($bankSelectOptions)[0];
            }
            if (!$model->organizationId) {
                $bankFieldOptions['disabled'] = true;
            }
        ?>
        <?= $form->field($model, 'bankBik')->widget(
            Select2::class,
            [
                'data'    => $bankSelectOptions,
                'options' => $bankFieldOptions,
            ]
        )?>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'person')->textInput(['maxlength' => 128])?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'contactNumber')->widget(MaskedInput::className(), [
            'mask' => '+7-(999)-999-99-99',
        ]) ?>
    </div>
</div>

<div class="documents" style="margin: 25px 0;">
<?php
    // Вывести страницу уже существующих документов
    echo $this->render('_documents', ['childObjectData' => $model->documents]);
?>
</div>

<div>
    <div class="action-buttons">
        <a href="#" class="btn btn-primary btn-block btn-new-document"><?= Yii::t('edm', 'Add document') ?></a>
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), [
            'id' => 'btn-submit-cdi',
            'class' => 'btn btn-success btn-block',
            'data' => [
                'toggle' => 'tooltip',
                'placement' => 'right',
                'title' => 'Добавьте подтверждающие документы перед созданием документа'
            ]
        ]) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php

$header = '<h4 class="modal-title">' . Yii::t('edm', 'Add document') . '</h4>';
$footer = '<button type="button" class="btn btn-default" data-dismiss="modal">' . Yii::t('app', 'Close') . '</button>'
        . '<button type="button" class="btn btn-primary btn-submit-form">' . Yii::t('app', 'Save') . '</button>';

$modal = Modal::begin([
    'id' => 'document-modal',
    'header' => $header,
    'footer' => $footer,
    'options' => [
        'tabindex' => false,
        'data' => [
            'backdrop' => 'static'
        ]
    ]
]);

?>

<?php $modal::end(); ?>


<?php

$this->registerCss('
    .field-confirmingdocumentinformationitem-currencyiddocument label,
    .field-confirmingdocumentinformationitem-currencyidcontract label {
        margin-bottom: 24px;
    }
');

$script = <<<JS
    function updateBankOptions() {
        var orgId = $('#confirmingdocumentinformationext-organizationid').val();
        var banksByOrganization = $banksByOrganizationJson;
        var banks = banksByOrganization[orgId];
        $('#confirmingdocumentinformationext-bankbik option').remove();
        for (var bik in banks) {
            $('#confirmingdocumentinformationext-bankbik').append(new Option(banks[bik], bik, false, false));
        }
        $('#confirmingdocumentinformationext-bankbik').val('');
    }

    $('#confirmingdocumentinformationext-organizationid').on('change', function () {
        var selected = $(this).find(":selected").val();
        $('#confirmingdocumentinformationext-bankbik')
            .val('')
            .trigger('change.select2')
            .prop('disabled', !selected);
        updateBankOptions();
    });

    // Вызов модального окна для добавления новой строки
    $('.btn-new-document').on('click', function(e) {
        e.preventDefault();

        $.ajax({
            url: '/edm/confirming-document-information/add-document',
            type: 'get',
            success: function(result) {
                $('#document-modal .modal-body').html(result);
                attachFileController.initialize();
                $('#document-modal').modal('show');
            }
        });
    });

    $("#confirmingdocumentinformationext-number").inputmask('Regex', {regex: "^[^ ]+$"}, {placeholder:" "});

    $('#document-modal').on('hidden.bs.modal', function (e) {
        $('body').off('click', '.btn-submit-form');
        checkCreateButton();
    });

    checkCreateButton();

    // Запись кэша формы
    $('.edm-cdi-form').change(function() {
        $.post('/wizard-cache/cdi', $(this).serialize());
    });
JS;

// Если документ уже сформирован, выдаем предупреждение перед созданием
if ($model->documentId && $model->document->signaturesCount > 0) {
    $script .= <<<JS
    $('#btn-submit-cdi').on('click', function() {
        var result = confirm('Внимание! Документ подписан! ' +
        'В случае изменения документа подписи будут автоматически отозваны! Редактировать документ?');

        if (result === false) {
            return false;
        }
    });
JS;
}

$this->registerJs($script, yii\web\View::POS_READY);
$this->registerJsFile(
    '@web/js/edm/confirming-document-information/document-form.js',
    ['depends' => [JqueryAsset::class]]
);

$this->registerCss('
.action-buttons {
    float: right;
    max-width: 200px;
}
.action-buttons btn {
    margin-bottom: 5px;
}
');

?>

<script>
    // Доступность кнопки создания документа
    function checkCreateButton() {
        var submitButton = $('#btn-submit-cdi');

        var countOperations = $('.documents-item').length;

        if (countOperations > 0) {
            submitButton.removeClass('disabled');
            submitButton.tooltip('destroy');
            submitButton.off('click');
        } else {
            submitButton.addClass('disabled');
            submitButton.tooltip();
            submitButton.on('click', function(e) {
                return false;
            });
        }
    }
</script>
