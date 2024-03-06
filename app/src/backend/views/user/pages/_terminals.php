<?php

use common\models\User;
use yii\widgets\ActiveForm;
use yii\web\View;

$form = ActiveForm::begin();
?>
<div class="form-group">
    <div class="row">
        <div class="col-md-8">
            <div class="terminals-block">
                <?php
                // Получить модель пользователя из активной сессии
                $adminIdentity = Yii::$app->user->identity;

                // Если пользователь доп. администратор и открывает собственную страницу,
                // не отображаем форму выбора терминалов
                if (($adminIdentity->role == User::ROLE_ADMIN) ||
                    ($adminIdentity->role == User::ROLE_ADDITIONAL_ADMIN && $adminIdentity->id != $model->id)) {
                    echo $this->render('@backend/views/user/terminals/_userTerminalList', [
                        'model' => $model,
                        'form' => $form,
                        'userId' => $model->id
                    ]);
                }
                ?>
            </div>
        </div>
    </div>
</div>
<?php
ActiveForm::end();
$this->registerJs(<<<JS

// События добавления нового терминала пользователя
$('.form-group').on('click', '#add-user-terminal', function(e) {
    e.preventDefault();

    var userId = $(this).data('id');
    var terminalId = $('#terminal-select').val();

    if (!userId) {
        alert('Невозможно добавить терминал. Пользователь еще не создан!');
        return;
    }

    $.ajax({
        url: '/user/add-terminal',
        type: 'post',
        data: 'userId=' + userId + '&terminalId=' + terminalId,
        success: function(html){
            renderHtmlTerminalAnswer(html);
        }
    });
});

$('.form-group').on('click', '.delete-terminal', function(e) {
    e.preventDefault();

    var userId = $(this).data('user-id');
    var terminalId = $(this).data('terminal-id');

    // ajax-запрос на удаление терминала из списка терминалов пользователя
    $.ajax({
        url: '/user/delete-terminal',
        type: 'post',
        data: 'userId=' + userId + '&terminalId=' + terminalId,
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
