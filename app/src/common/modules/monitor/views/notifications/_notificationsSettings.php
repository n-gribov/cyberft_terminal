<?php

use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;

// если настройка оповещений
// для конкретного терминала
if (Yii::$app->request->get('id')) {
    $terminalId = Yii::$app->request->get('id');
    $url = Url::to(['notifications-update', 'terminalId' => $terminalId]);
} else {
    $terminalId = null;
    $url = Url::to(['notifications-update']);
}

$form = ActiveForm::begin([
    'method' => 'post',
    'action' => $url
]);

$myView = $this;

$checkerDataProvider = $data['checkerDataProvider'];

// Создать таблицу для вывода
echo GridView::widget([
    'showOnEmpty' => false,
    'summary' => false,
    'emptyText'    => Yii::t('monitor/mailer', 'Notifications are not available'),
    'dataProvider' => $checkerDataProvider,
    'columns'      => [
        [
            'class' => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function ($model) use ($terminalId) {
                return [
                    'value' => $model->code,
                    'checked' => $model->isActive($terminalId) ? 'checked' : null
                ];
            },
            'contentOptions' => ['style' => 'width:30px'],
        ],
        [
            'attribute' => 'codeLabel',
            'contentOptions' => ['style' => 'width:350px'],
            'format' => 'html',
            'label' => Yii::t('monitor/events', 'Event code'),
            'content' => function($item) use($form, $myView, $terminalId) {
                return $item->codeLabel . '<br><br>' . $myView->render(
                    $item->getFormRowView(), [
                        'form' => $form,
                        'model' => $item,
                        'terminalId' => $terminalId
                    ]
                );
            }
        ],
        [
            'format' => 'raw',
            'label' => Yii::t('monitor/events', 'Settings'),
            'value' => function($item) {
                return Html::a(Yii::t('monitor/mailer', 'Mail notification list'), '#', [
                    'class' => 'show-notification-list',
                    'data' => [
                        'id' => $item->code,
                        'name' => $item->getCodeLabel()
                    ]
                ]);
            }
        ],
    ],
]);
?>
    <p><?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success'])?></p>

<?php ActiveForm::end() ?>

<?php

$header = '<h4 class="modal-title" id="myModalLabel"></h4>';

$modal = Modal::begin([
    'id' => 'addressesModal',
    'header' => $header,
    'footer' => '',
    'size' => Modal::SIZE_LARGE,
    'options' => [
        'tabindex' => false
    ]
]); ?>

<?php $modal::end(); ?>

<?php

$script = <<<JS
    // Открытие модального окна
    $('.show-notification-list').on('click', function(e) {
        e.preventDefault();

        // Тип события оповещения
        var checkerType = $(this).data('id');

        // Наименование события оповещения
        var checkerName = $(this).data('name');

        var url = "";

        var terminal = "" + $terminalId + "";

        if (terminal !== 0) {
            url = '/monitor/notifications/get-mailing-settings-modal?checkerType=' + checkerType + '&terminalId=' + terminal;
        } else {
            url = '/monitor/notifications/get-mailing-settings-modal?checkerType=' + checkerType;
        }

        // Получение содержимого для формы
        $.ajax({
            url: url,
            type: 'get',
            success: function(result) {
                $('#addressesModal .modal-title').text(checkerName);
                $('#addressesModal .modal-body').html(result);
                $('#addressesModal').modal('show');
            }
        });
    });

    // Удаление пользователя из списка получателей
    $('body').on('click', '.delete-mail-recipient', function(e) {
        e.preventDefault();

        // Адрес действия удаления
        var href = $(this).attr('href');

        // Удаление пользователя из списка получателей
        $.ajax({
            url: href,
            success: function(result) {
                $('#addressesModal .modal-body').html(result);
            }
        });
    });

    // Добавление нового пользователя
    $('body').on('click', '.btn-submit-form', function(e) {
        e.preventDefault();

        var form = $('#add-user-form');
        var url = '/monitor/notifications/mailing-user-add';

        $.ajax({
            url: url,
            type: 'post',
            data: form.serialize(),
            success: function(result) {
                $('#addressesModal .modal-body').html(result);
            }
        });
    });
JS;

$this->registerJs($script, yii\web\View::POS_READY);
