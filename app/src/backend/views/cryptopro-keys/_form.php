<?php

use common\models\CryptoproKey;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;

/* @var $this View */
/* @var $model CryptoproKey */
/* @var $form ActiveForm */
?>
<div class="row">
    <div class="col-xs-4">
        <div>
            <h5>Статус: <?=$model->getStatusLabel();?></h5>
            <hr>
        </div>
        <?php $form = ActiveForm::begin(); ?>

        <div class="terminals-block">
            <?php
                // Подгружаем представление для организации работы со списком терминалов
                echo $this->render('_keysTerminalList', [
                    'keyId' => $keyId,
                    'terminalList' => $terminalList,
                    'dataProvider' => $dataProvider
                ]);
            ?>
        </div>
        <div class="beneficiary-block">
            <?php
                // Подгружаем представление для организации работы со списком получателей
                echo $this->render('_keysBeneficiaryList', [
                    'keyId' => $keyId,
                    'beneficiaryList' => $beneficiaryList,
                    'dataProviderBeneficiary' => $dataProviderBeneficiary
                ]);
            ?>
        </div>

        <?= $form->field($model, 'userId')->dropDownList($model->getUserList());?>
        <?= $form->field($model, 'keyId')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        <?= $form->field($model, 'serialNumber')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        <?= $form->field($model, 'ownerName')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        <?= $form->field($model, 'expireDate')->textInput(['maxlength' => true, 'readonly' => true]) ?>
        <?= $form->field($model, 'certData')->textarea(['rows' => 6, 'readonly' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? Yii::t('app/fileact', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>


<?php
// Начальное значение для инициализации списка кодов терминалов

$this->registerJs(<<<JS

// События добавления нового терминала пользователя
$('body').on('click', '#add-user-terminal', function(e) {
    e.preventDefault();

    var keyId = $(this).data('id');
    var terminalId = $('#terminal-select').val();

    if (!keyId) {
        alert('Невозможно добавить терминал. Пользователь еще не создан!');
        return;
    }

    $.ajax({
        url: '/cryptopro-keys/add-terminal',
        type: 'post',
        data: 'keyId=' + keyId + '&terminalId=' + terminalId,
        success: function(html){
            renderHtmlTerminalAnswer(html);
        }
    });
});

$('body').on('click', '.delete-terminal', function(e) {
    e.preventDefault();

    var keyId = $(this).data('key-id');
    var terminalId = $(this).data('terminal-id');

    // ajax-запрос на удаление терминала из списка терминалов пользователя
    $.ajax({
        url: '/cryptopro-keys/delete-terminal',
        type: 'post',
        data: 'keyId=' + keyId + '&terminalId=' + terminalId,
        success: function(html){
            renderHtmlTerminalAnswer(html);
        }
    });

});
	
$('body').on('click', '#add-user-beneficiary', function(e) {
    e.preventDefault();

    var keyId = $(this).data('id');
    var beneficiaryId = $('#beneficiary-select').val();

    if (!keyId) {
        alert('Невозможно добавить терминал. Пользователь еще не создан!');
        return;
    }

    $.ajax({
        url: '/cryptopro-keys/add-beneficiary',
        type: 'post',
        data: 'keyId=' + keyId + '&beneficiaryId=' + beneficiaryId,
        success: function(html){
            renderHtmlBeneficiaryAnswer(html);
        }
    });
});

$('body').on('click', '.delete-beneficiary', function(e) {
    e.preventDefault();

    var keyId = $(this).data('key-id');
    var beneficiaryId = $(this).data('beneficiary-id');

    // ajax-запрос на удаление терминала из списка терминалов пользователя
    $.ajax({
        url: '/cryptopro-keys/delete-beneficiary',
        type: 'post',
        data: 'keyId=' + keyId + '&beneficiaryId=' + beneficiaryId,
        success: function(html){
            renderHtmlBeneficiaryAnswer(html);
        }
    });

});

function renderHtmlTerminalAnswer(html) {
     $('.terminals-block').html(html);
     $('.terminals-block').find('[name="_csrf"]').detach();
}
	
function renderHtmlBeneficiaryAnswer(html) {
     $('.beneficiary-block').html(html);
     $('.beneficiary-block').find('[name="_csrf"]').detach();
}

JS
    , View::POS_READY);

$this->registerCss(
    "#add-user-terminal {
      margin-top: 23px;
    }"
);

?>