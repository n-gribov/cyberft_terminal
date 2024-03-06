<?php

use addons\edm\models\ContractUnregistrationRequest\ContractUnregistrationRequestForm;
use kartik\widgets\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JqueryAsset;

/** @var \yii\web\View $this */
/** @var ContractUnregistrationRequestForm $model */

$availableOrganizations = $model->getAvailableOrganizations();
$availableReceiverBanks = $model->getAvailableReceiverBanks();
$organizationSelectOptions = ArrayHelper::map($availableOrganizations, 'id', 'name');
$receiverBankSelectOptions = ArrayHelper::map($availableReceiverBanks, 'bik', 'name');

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

$form = ActiveForm::begin(['id' => 'contract-unregistration-request-form']); ?>

<fieldset class="form-inline">
    <?= $form->field($model, 'documentNumber') ?>
    <?= $form
        ->field($model, 'documentDate')
        ->widget(DateTimePicker::class, $dateTimePickerOptions)
        ->label(Yii::t('edm', 'Date'))
    ?>
</fieldset>

<hr>

<?= $form
    ->field($model, 'organizationId')
    ->dropDownList($organizationSelectOptions, ['prompt' => count($organizationSelectOptions) > 1 ? '-' : null])
?>
<?php foreach ($availableOrganizations as $organization): ?>
    <div class="organization-info hidden" data-id="<?= Html::encode($organization->id) ?>">
        <p>
            <strong><?= Yii::t('edm', 'INN') ?></strong>
            <span class="value"><?= Html::encode($organization->inn) ?></span>

            <strong><?= Yii::t('edm', 'KPP') ?></strong>
            <span class="value"><?= Html::encode($organization->kpp) ?></span>

            <strong><?= Yii::t('edm', 'OGRN') ?></strong>
            <span class="value"><?= Html::encode($organization->ogrn) ?></span>

            <strong><?= Yii::t('edm', 'Date of entry to EGRUL') ?></strong>
            <span class="value"><?= Html::encode($organization->dateEgrul) ?></span>
        </p>
        <p>
            <strong><?= Yii::t('edm', 'Address') ?></strong>
            <span class="value"><?= Html::encode($organization->fullAddress) ?></span>
        </p>
    </div>
<?php endforeach; ?>

<fieldset class="form-inline">
    <?= $form->field($model, 'contactPerson')->textInput(['maxlength' => 40, 'size' => 42]) ?>
    <?= $form->field($model, 'contactPhone')->textInput(['maxlength' => 20]) ?>
</fieldset>
<hr>

<fieldset>
    <?= $form
        ->field($model, 'receiverBankBik')
        ->dropDownList($receiverBankSelectOptions, ['prompt' => count($receiverBankSelectOptions) > 1 ? '-' : null])
    ?>
</fieldset>
<hr>

<h4><?= Yii::t('edm', 'Contracts (loan agreements)') ?></h4>
<?= $this->render('form/grid-views/_contractsGridView', ['models' => $model->contracts]) ?>
<?= Html::button(
    Yii::t('app', 'Add'),
    [
        'id' => 'add-contract-button',
        'class' => 'btn btn-primary',
    ]
) ?>
<hr>

<h4><?= Yii::t('edm', 'Attached files') ?></h4>
<?= $this->render('form/grid-views/_attachedFilesGridView', ['models' => $model->attachedFiles]) ?>
<?= Html::button(
    Yii::t('app', 'Add'),
    [
        'id' => 'add-attached-file-button',
        'class' => 'btn btn-primary',
        'data' => ['loading-text' => '<i class=\'fa fa-spinner fa-spin\'></i> ' . Yii::t('app', 'Add')]
    ]
) ?>
<hr>

<?= $form->field($model, 'contractsJson', ['template' => '{input}'])->hiddenInput() ?>
<?= $form->field($model, 'attachedFilesJson', ['template' => '{input}'])->hiddenInput() ?>

<div>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

<form action="upload-attached-file" id="upload-attached-file-form" enctype="multipart/form-data">
    <input type="file" name="file" class="hidden" />
</form>

<?= $this->render('form/modals/_contractFormModal', ['model' => new ContractUnregistrationRequestForm\Contract()]) ?>

<?php
$this->registerJsFile(
    '@web/js/edm/contract-unregistration-request/contract-unregistration-request-form.js',
    ['depends' => [JqueryAsset::className()]]
);
$this->registerCss(<<<CSS
    .form-inline .form-group {
        margin-right: 10px;
    }
    .input-group-addon .glyphicon {
        font-size: 18px;
    }
    .has-error .input-group-addon {
        background-color: #f2dede;
    }
    .form-inline .form-group {
        margin-right: 30px;
        vertical-align: top;
    }
    .organization-info .value {
        margin-right: 15px;
    }
CSS
);
