<?php

use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\select2\Select2;

$usedUsers = $data['usedUsers'];
$dropDownData = $data['dropDownData'];
$substituteEmailData = $data['substituteEmailData'];
$checkerType = $data['checkerType'];
$terminalId = $data['terminalId'];

$this->registerJs("
    var emailMap = " . json_encode($substituteEmailData) . ";
    function updateUserData() {
        $('#userEmail').val(emailMap[$('#userDropDown').val()]);
        $('#userName').val($('#userDropDown option:selected').text());
    }"
    , yii\web\View::POS_END);
?>
    <h4><?=Yii::t('monitor/mailer', 'Users receiving notifications')?></h4>
<?php
// Создать таблицу для вывода
echo GridView::widget([
    'showOnEmpty' => false,
    'summary' => false,
    'emptyText'    => Yii::t('monitor/mailer', 'No users are receiving notifications'),
    'dataProvider' => $usedUsers,
    'columns'      => [
        'name',
        'email',
        [
            'class'    => 'yii\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function($model, $key, $index) use ($checkerType, $terminalId) {
                    if ($terminalId) {
                        $url = Url::to(['/monitor/notifications/mailing-user-delete',
                            'email' => $key->email, 'checkerType' => $checkerType, 'terminalId' => $terminalId]);
                    } else {
                        $url = Url::to(['/monitor/notifications/mailing-user-delete',
                            'email' => $key->email, 'checkerType' => $checkerType]);
                    }

                    return Html::a(
                        '<span class="glyphicon glyphicon-trash">',
                        $url,
                        ['class' => 'delete-mail-recipient']
                    );
                }
            ],
        ],
    ],
]);

echo Html::beginForm(Url::to(['/monitor/notifications/mailing-user-add']), 'post', ['id' => 'add-user-form']);
?>
    <h4><?=Yii::t('monitor/mailer', 'Add new recipient')?></h4>
    <div class="row" style="margin-bottom: 25px;">
        <div class="col-lg-3">
            <?=Select2::widget([
                'name' => 'selectUserId',
                'data' => $dropDownData,
                'options' => [
                    'id' => 'userDropDown', 'prompt' => 'Пользователи...' ,'class' => 'form-control',
                    'onChange' => 'return updateUserData()'
                ],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]);?>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3">
            <?=Html::textInput('addUserName', '', ['id' => 'userName', 'class' => 'form-control',
                'placeholder' => 'Имя получателя'])?>
        </div>
        <div class="col-lg-3">
            <?=Html::textInput('addUserEmail', '', ['id' => 'userEmail', 'class' => 'form-control',
                'placeholder' => 'Email получателя'])?>
        </div>
        <?=Html::hiddenInput('addUserCheckerType', $checkerType)?>

        <?php
            if ($terminalId) {
                echo Html::hiddenInput('addUserTerminalId', $terminalId);
            }
        ?>

        <div class="col-lg-3">
            <?=Html::submitButton(Yii::t('app', 'Add'), ['class' => 'btn btn-success btn-submit-form'])?>
        </div>
    </div>
<?php
echo Html::endForm();
