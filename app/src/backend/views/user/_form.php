<?php

use common\models\User;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\ActiveForm;
use common\helpers\UserHelper;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this View */
/* @var $model User */
/* @var $form ActiveForm */

$roleModels = $model->getEditableRoleLabels();
$options = [];
if (isset($options[User::ROLE_LSO]) && User::getNotDeletedRoleCount(User::ROLE_LSO) > 0) {
    $options[User::ROLE_LSO] = ['disabled' => true];
}
if (isset($options[User::ROLE_RSO]) && User::getNotDeletedRoleCount(User::ROLE_RSO) > 0) {
    $options[User::ROLE_RSO] = ['disabled' => true];
}

// Список администраторов
$admins = UserHelper::getAdmins();
$adminsList = [];

foreach($admins as $admin) {
    $adminsList[$admin->id] = $admin->getScreenName();
}
?>

<?php $form = ActiveForm::begin(); ?>
<div class="form-group">
    <div class="row"><div class="col-lg-6">
        <?= $form->field($model, 'email')->input('email'); ?>
    </div></div>
    <div class="row"><div class="col-lg-6"><?= $form->field($model, 'lastName')->textInput(['maxlength' => 45]); ?></div></div>
    <div class="row"><div class="col-lg-6"><?= $form->field($model, 'firstName')->textInput(['maxlength' => 45]); ?></div></div>
    <div class="row"><div class="col-lg-6"><?= $form->field($model, 'middleName')->textInput(['maxlength' => 45]); ?></div></div>

    <?php
        // Выбор роли пользователя и ответственного администратора доступен только главному администратору
        if (Yii::$app->user->identity->role == User::ROLE_ADMIN) : ?>
        <div class="row"><div class="col-lg-6"><?= $form->field($model, 'role')->dropDownList($roleModels, ['options' => $options]); ?></div></div>
        <?php
            // Главный администратор не может выбирать сам у себя ответственного пользователя
            if ($model->id != Yii::$app->user->identity->id) : ?>
                <div class="row">
                    <div class="col-lg-6"><?= 
                        $form->field($model, 'ownerId')->widget(Select2::classname(), [
                            'data' => $adminsList,
                            'options' => ['prompt' => ''],
                            'pluginOptions' => [
                                'allowClear' => true,
                            ],
                        ]) ?>
                    </div>
                </div>
        <?php endif ?>
    <?php endif ?>
    <div class="row"><div class="col-lg-6">
    <?php
        // Если пользователь доп. администратор и открывает собственную страницу,
        // не отображаем форму выбора терминалов
        $adminIdentity = Yii::$app->user->identity;
        if (($adminIdentity->role == User::ROLE_ADMIN) ||
            ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN && $adminIdentity->id != $model->id)) : ?>
    <div class="row signature-option">
        <div class="col-lg-6">
            <?= $form->field($model, 'signatureNumber')->dropDownList($model->getSignatureNumberLabels()); ?>
        </div>
    </div>

    <div class="row signature-option">
        <div class="col-lg-6">
            <?= $form->field($model, 'signatureLevel')->dropDownList($model->getSignatureLevelLabels(), ['prompt' => '-']); ?>
        </div>
    </div>
    <?php endif ?>
    <div class="row"><div class="col-lg-6"><?= $form->field($model, 'authType')->dropDownList($model->getAuthTypeLabels()); ?></div></div>
    <div class="row">
        <div class="col-md-8">
            <div class="terminals-block">
            <?php
                // Для формы создания нового пользователя
                // и редактирования существующего - разные представления
                // добавления терминалов
                if ($model->isNewRecord) {
                    // Вывести страницу
                    echo $this->render('terminals/_userTerminalListNewUser', [
                        'model' => $model,
                        'form' => $form,
                        'userId' => $model->id,
                        'dataProvider' => $dataProvider
                    ]);
                }
            ?>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <?= $form->field($model, 'disableTerminalSelect')->checkBox(); ?>
        </div>
    </div>

    <?php if (!$model->isNewRecord) : ?>
        <div class="form-group">
            <?= Html::checkbox('flushPassword', false, ['id' => 'flushPassword']) ?>
            <?= Html::label(Yii::t('app/user', 'Reset password'), 'flushPassword') ?>
        </div>
    <?php endif ?>

    <?= Html::a(Yii::t('app', 'Back'), Yii::$app->request->referrer, ['class' => 'btn btn-default']) ?>
    <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Save'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>
<?php ActiveForm::end(); ?>

<?php
$adminRole = User::ROLE_ADMIN;
$additionalAdminRole = User::ROLE_ADDITIONAL_ADMIN;
$currentUserRole = Yii::$app->user->identity->role;

$this->registerJs(<<<JS
var rolesList = $('#user-role');
var ownerUser = $('.field-user-ownerid');
var servicesList = $('.user-form-terminals-list');

function toggleSignatureOptions(isShown) {
    $('.signature-option').toggle(isShown);
    if (!isShown) {
        $('.signature-option select').val('');
    }
}

(onRoleChange = function() {
    var currentRole = rolesList.val();
    if ({$adminRole} == currentRole) {
        toggleSignatureOptions(false);
        ownerUser.hide();
        servicesList.hide();
    } else if ({$additionalAdminRole} == currentRole) {
        toggleSignatureOptions(false);
        ownerUser.show();
        servicesList.show();
    } else {
        toggleSignatureOptions(true);
        ownerUser.show();
        servicesList.show();
    }
})();

if (rolesList) {
    rolesList.on('change', onRoleChange);
}

// Добавление терминала при создании нового пользователя
$('.form-group').on('click', '#add-new-user-terminal', function(e) {
    e.preventDefault();

    // Получаем выбранный терминал
    var terminalId = $('#terminal-select').val();

    $.ajax({
        url: '/user/add-terminal-new-user',
        type: 'post',
        data: '&terminalId=' + terminalId,
        success: function(html){
            renderHtmlTerminalAnswer(html);
        }
    });

});

// Удаление терминала при создании нового пользователя
$('.form-group').on('click', '.delete-terminal-new-user', function(e) {
    e.preventDefault();
    var terminalId = $(this).data('terminal-id');
    // ajax-запрос на удаление терминала из списка терминалов пользователя
    $.ajax({
        url: '/user/delete-terminal-new-user',
        type: 'post',
        data: '&terminalId=' + terminalId,
        success: function(html){
            renderHtmlTerminalAnswer(html);
        }
    });
});

function renderHtmlTerminalAnswer(html) {
    $('.terminals-block').html(html);
    $('.terminals-block').find('[name="_csrf"]').detach();
}

JS, View::POS_READY);

$this->registerCss(<<<CSS
    #add-user-terminal {
      margin-top: 22px;
    }
CSS);
