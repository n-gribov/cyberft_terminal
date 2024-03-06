<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

$this->title = 'Сертификат ключа ' . $model->user->name .' ('. $model->fingerprint . ')';
?>
<div class="tab-buttons" style="margin-bottom: 1em;">
    <?= Html::a(Yii::t('app', 'Back'), '/user/view?id=' . $model->userId . '&tabMode=certs', ['class' => 'btn btn-default']) ?>
<?php
    // Если keyId не подается, значит у нас предпросмотр
    if (isset($keyId)) {
        echo $this->render('@backend/views/user/certs/_replaceCertForm', ['model' => $uploadCertForm, 'userId' => $model->userId, 'certId' => $model->id]);
        echo Html::a(Yii::t('app', 'Delete'), ['/user-auth-cert/delete', 'id' => $model->id], ['class' => 'btn btn-danger', 'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?')]);
    }
    echo Html::a(
        'Сформировать акт о признании ЭП',
        ['/certManager/acknowledgement-act/create', 'userAuthCertId' => $model->id],
        ['class' => 'btn btn-info pull-right']
    );
?>
</div>

<?php
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'user.id',
        [
            'attribute' => 'user.name',
            'label' => Yii::t('app/cert', 'Owned by user'),
        ],
        [
            'attribute' => 'user.statusLabel',
            'value' => function ($model) {
                return ($model->status === 'active') ? 'Используется для подписания' : 'Не используется для подписания';
            }
        ]
    ],
]);
?>

<?= Html::tag('p', Yii::t('app/cert', 'Certificate details')) ?>

<?php
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'validFrom:datetime',
        'validTo:datetime',
        'issuer:ntext',
        'subject:ntext',
        'signatureTypeLN',
        'fingerprint',
        'serialNumber',
    ],
]);
?>
<div class="row">
    <div class="col-xs-4 beneficiary-block">
        <?php
            // Подгружаем представление для организации работы со списком получателей
            echo $this->render('_keysBeneficiaryList', [
                'keyId' => $keyId,
                'beneficiarySelected' => $beneficiarySelected,
                'beneficiaryAll' => $beneficiaryAll,
            ]);
        ?>
    </div>
</div>
<div class="row">
<div class="col-xs-4 form-block">
<?php
if (isset($keyId)) {
    $route = ['/user-auth-cert/save-beneficiaries', 'id' => $keyId];
    $formId = 'save-beneficiary-form';
} else {
    $route = ['/user-auth-cert/create-from-preview'];
    $formId = 'save-certificate-form';
}

$form = ActiveForm::begin([
    'method' => 'post',
    'action' => Url::toRoute($route),
    'id' => $formId,
    'options' => [
        'enctype' => 'multipart/form-data',
        'style'   => 'display: block;'
    ]
]);

echo Html::hiddenInput('beneficiarySelectedInput', '');

if (!isset($keyId)) {
    echo $form->field($model, 'userId')->hiddenInput(['value' => $model->userId])->label(false);
    echo $form->field($model, 'certificate')->hiddenInput(['value' => $model->certificate])->label(false);
    echo $form->field($model, 'fingerprint')->hiddenInput(['value' => $model->fingerprint])->label(false);
    echo $form->field($model, 'expiryDate')->hiddenInput(['value' => $model->expiryDate])->label(false);
}

$submitButtonId = $keyId ? 'save-beneficiary' : 'save-certificate';

echo Html::button(
    Yii::t('app', 'Save'),
    [
        'class' => 'btn btn-success',
        'id'    => $submitButtonId,
        'style' => 'margin-right: 10px;',
        'type'  => 'button',
    ]
);


$modal = Modal::begin([
    'id' => 'no-beneficiary-modal'
]);
?>
    <p style="font-weight: bold;"><span style="color: red;">Внимание! Не выбраны получатели для подписания этим ключом!</span> Без выбора получателей ключ не сможет быть использован для подписания документов, но может быть использован для авторизации пользователя!</p>
    <p style="font-weight: bold; color: red">Завершить настройку?</p>
    <p align="right">
        <?= Html::button('ОК', ['id' => 'no-beneficiary-ok-button']) ?>
        <?= Html::button('Отмена', ['id' => 'no-beneficiary-cancel-button']) ?>
    </p>
<?php
$modal::end();

ActiveForm::end();

if (isset($keyId)) {
$script = <<<JS
    $('body').on('click', '#save-beneficiary', function(e) {
        if ($('input[name="beneficiarySelectedInput"]').val() === '[]') {
            $('#no-beneficiary-modal').modal('show');
        } else {
            $('#save-beneficiary-form').submit();
        }
    });

    $('body').on('click', '#no-beneficiary-ok-button', function(e) {
        $('#save-beneficiary-form').submit();
    });

    $('body').on('click', '#no-beneficiary-cancel-button', function(e) {
        $('#no-beneficiary-modal').modal('hide');
    });
JS;
} else {
$script = <<<JS
    $('body').on('click', '#save-certificate', function(e) {
        if ($('input[name="beneficiarySelectedInput"]').val() === '[]') {
            $('#no-beneficiary-modal').modal('show');
        } else {
            $('#save-certificate-form').submit();
        }
    });

    $('body').on('click', '#no-beneficiary-ok-button', function(e) {
        $('#save-certificate-form').submit();
    });

    $('body').on('click', '#no-beneficiary-cancel-button', function(e) {
        $('#no-beneficiary-modal').modal('hide');
    });
JS;
}

$this->registerJS($script, View::POS_READY);
?>
</div>
</div>