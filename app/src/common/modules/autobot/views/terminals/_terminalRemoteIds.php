<?php

use yii\data\ActiveDataProvider;
use common\widgets\GridView;
use yii\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;


$model = $data['model'];
$remoteIdsQuery = $data['remoteIds'];
$terminalsReceivers = $data['terminalsReceivers'];

?>

<p>
    <strong><?= Yii::t('app/terminal', 'Company code in recipient system') ?></strong>
</p>

<?php

$dataProvider = new ActiveDataProvider([
    'query' => $remoteIdsQuery,
    'pagination' => false,
]);
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-3">
        <?php

            $inputOptions = ['class' => 'form-control', 'id' => 'remote-id'];

            $selectOptions = [
                'name' => 'test',
                'value' => '',
                'data' => $terminalsReceivers,
                'options' => ['prompt' => '', 'id' => 'receiver']
            ];

            if (empty($terminalsReceivers)) {
                $selectOptions['options']['disabled'] = true;
                $inputOptions['disabled'] = true;
            }

            echo Select2::widget($selectOptions);
        ?>
    </div>
    <div class="col-md-2">
        <?=Html::textInput('test2', null, $inputOptions)?>
    </div>
    <div class="col-lg-1">
        <a href="#" id="add-remote-id" class="btn btn-primary" data-id="<?=$model->id?>"><?= Yii::t('app', 'Add') ?></a>
    </div>
</div>


<?php ActiveForm::end(); ?>

<div class="row">
    <div class="col-md-6">
        <?php
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'id' => 'user-terminals-list',
            'columns' => [
                [
                    'attribute' => 'terminalReceiver',
                    'value' => function($item) {

                        $value = $item->getTerminalReceiverTitle();

                        if (empty($value)) {
                            return "Все";
                        } else {
                            return $value;
                        }

                    }
                ],
                [
                    'attribute' => 'remoteId'
                ],
                [
                    'format' => 'raw',
                    'value' => function($item) use ($model) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', '#',
                            [
                                'id' => 'delete-remote-id',
                                'data' => [
                                    'receiver' => $item->terminalReceiver,
                                    'terminal' => $model->id
                                ],
                            ]);
                    }
                ],
            ],
        ]);
        ?>
    </div>
</div>


<?php

$script = <<< JS
    // Добавление кода
    $('body').on('click', '#add-remote-id', function(e) {
        e.preventDefault();

        var receiver = $('#receiver').val();
        var remoteId = $('#remote-id').val();
        var terminalId = $(this).data('id');

        // Поля должны быть заполнены
        if (receiver.length === 0) {
            alert('Укажите компанию-получателя');
            return false;
        }

        if (remoteId.length === 0) {
            alert('Укажите код в системе получателя');
            return false;
        }

        // Добавление кода для терминала
        $.ajax({
            url: '/autobot/terminals/terminal-add-remote-id',
            data: {terminalId: terminalId, receiver: receiver, remoteId: remoteId},
            type: 'post',
            success: function(result) {
                $('body').off('click', '#add-remote-id');
                $('body').off('click', '#delete-remote-id');
                $('.remote-ids-block').html(result);
            }
        });
    });

    // Удаление кода
    $('body').on('click', '#delete-remote-id', function(e) {
        e.preventDefault();

        var receiver = $(this).data('receiver');
        var terminal = $(this).data('terminal');

        // Удаление кода терминала
        $.ajax({
            url: '/autobot/terminals/terminal-delete-remote-id',
            data: {terminal: terminal, receiver: receiver},
            type: 'post',
            success: function(result) {
                $('body').off('click', '#add-remote-id');
                $('body').off('click', '#delete-remote-id');
                $('.remote-ids-block').html(result);
            }
        });
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);

$this->registerCss('
    .form-control[disabled] {
        background-color: #eee;
    }
');

?>