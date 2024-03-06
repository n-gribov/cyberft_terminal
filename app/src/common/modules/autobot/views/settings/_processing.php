<?php

use common\widgets\GridView;
use common\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$dataProvider = $data['dataProvider'];
$searchModel = $data['searchModel'];
$queryParams = $data['queryParams'];

echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'rowOptions' => function ($model) {
        $options['class'] = "multiprocesses-index-row";
        $options['ondblclick'] = "window.location='" . Url::to(['/autobot/terminals/index', 'id' => $model['id']]) . "'";
        return $options;
    },
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
            'headerOptions' => ['style' => 'width: 40px;']
        ],
        [
            'attribute' => 'terminalId',
            'filter' => Html::input(
                'text',
                'MultiprocessesSearch[terminalId]',
                isset($queryParams['MultiprocessesSearch']['terminalId']) ? $queryParams['MultiprocessesSearch']['terminalId'] : '',
                ['class' => 'form-control']
            ),
            'format' => 'html',
            'label' => Yii::t('app/terminal', 'Terminal ID'),
            'headerOptions' => ['style' => 'width: 170px;']
        ],
        [
            'attribute' => 'organization',
            'filter' => Html::input(
                'text',
                'MultiprocessesSearch[organization]',
                isset($queryParams['MultiprocessesSearch']['organization']) ? $queryParams['MultiprocessesSearch']['organization'] : '',
                ['class' => 'form-control']
            ),
            'label' => Yii::t('app/autobot', 'Organization'),
            'headerOptions' => ['style' => 'width: 200px;']
        ],
        [
            'attribute' => 'status',
            'format' => 'raw',
            'filter' => Html::dropDownList(
                'MultiprocessesSearch[status]',
                isset($queryParams['MultiprocessesSearch']['status']) ? $queryParams['MultiprocessesSearch']['status'] : '',
                $searchModel->getStatusLabels(),
                ['class' => 'form-control', 'prompt' => '']
            ),
            'label' => Yii::t('app/autobot', 'Terminal status'),
            'value' => function ($item) {
                $style = '';

                // Статус активности терминала
                if ($item['status']) {
                    $class = 'ok';
                } else {
                    // Иконку неактивного статуса красим в красный цвет
                    $style = 'color: red !important;';
                    $class = 'remove';
                }

                return Html::a(
                    "<span class='glyphicon glyphicon-{$class}' style='{$style}'></span>",
                    Url::to(['/autobot/terminals/index', 'id' => $item['id']])
                );
            },
            'headerOptions' => ['style' => 'text-align: center; width: 150px;'],
            'contentOptions' => ['style' => 'text-align: center;']
        ],
        [
            'attribute' => 'hasActiveControllerKeys',
            'format' => 'raw',
            'filter' => Html::dropDownList(
                'MultiprocessesSearch[hasActiveControllerKeys]',
                isset($queryParams['MultiprocessesSearch']['hasActiveControllerKeys']) ? $queryParams['MultiprocessesSearch']['hasActiveControllerKeys'] : '',
                $searchModel->getControllerKeyLabels(),
                ['class' => 'form-control', 'prompt' => '']
            ),
            'label' => Yii::t('app/autobot', 'Controller Key'),
            'value' => function ($item) {
                $style = '';
                $options = [];

                $url = Url::to(['/autobot/terminals/index', 'id' => $item['id'], 'tabMode' => 'tabAutobot']);

                // Статус наличия ключей контролера у терминала
                if ($item['hasActiveControllerKeys']) {
                    $class = 'ok';
                } else {
                    // Иконку отсутствия ключа контроллера у терминала красим в красный цвет
                    $style = 'color: red !important;';
                    $class = 'remove';
                }

                return Html::a(
                    "<span class='glyphicon glyphicon-{$class}' style='{$style}'></span>",
                    $url,
                    $options
                );
            },
            'headerOptions' => ['style' => 'text-align: center; width: 150px;'],
            'contentOptions' => ['style' => 'text-align: center;']
        ],
        [
            'attribute' => 'exchangeStatus',
            'label' => Yii::t('app/autobot', 'Processing exchange status'),
            'filter' => Html::dropDownList(
                'MultiprocessesSearch[exchangeStatus]',
                isset($queryParams['MultiprocessesSearch']['exchangeStatus']) ? $queryParams['MultiprocessesSearch']['exchangeStatus'] : '',
                $searchModel->getExchangeStatusLabels(),
                ['class' => 'form-control', 'prompt' => '']
            ),
            'format' => 'html',
            'value' => function ($item) {
                $style = '';

                // Статус состояния обмена с сервером CyberFT
                if ($item['exchangeStatus']) {
                    $class = 'ok';
                    $url = Url::to(['/autobot/terminals/index', 'id' => $item['id'], 'tabMode' => 'tabProcessing']);
                } else {
                    // Иконку отсутствия соединения красим в красный цвет
                    $style = 'color: red !important;';
                    $class = 'remove';
                    $url = '#';
                }

                return Html::a(
                    "<span class='glyphicon glyphicon-{$class}' style='{$style}></span>",
                    $url
                );
            },
            'headerOptions' => ['style' => 'text-align: center; width: 150px;'],
            'contentOptions' => ['style' => 'text-align: center;']
        ],
        [
            'label' => Yii::t('app/autobot', 'Exchange Status'),
            'format' => 'raw',
            'headerOptions' => ['style' => 'width: 10px;'],
            'value' => function ($item) {
                // Формирование кнопок остановки/запуска процесс обмена
                if ($item['exchangeStatus']) {
                    $class = 'danger';
                    $url = Url::to(['stop-terminal', 'terminalId' => $item['terminalId']]);
                    $label = Yii::t('app/autobot', 'Stop');
                    $data = ['method' => 'post', 'mode' => 'stop-terminal'];
                } else {
                    // Если терминал неактивен
                    // или не сформирован PasswordHash
                    // то обмен для данного терминала
                    if (!$item['status'] || !$item['hasUseForSigningControllerKey']) {
                        $class = 'info';
                        $url = '#';
                        $label = Yii::t('app/autobot', 'Unavailable');
                        $data = [
                            'mode' => 'anv-show-info',
                            'terminal' => $item['terminalId']
                        ];

                        // Для кнопки недоступности записываем информацию о
                        // причинах недоступности возможности запустить обмен с терминалом

                        if (!$item['status']) {
                            $data['error-message'] = Yii::t(
                                'app/autobot',
                                'Terminal {terminalId} is inactive. Processing exchange not available.',
                                ['terminalId' => $item['terminalId']]
                            );
                        } elseif (!$item['hasUseForSigningControllerKey']) {
                            $data['error-message'] = Yii::t(
                                'app/autobot',
                                'Unable to start exchange! Terminal {terminalId} does not have a Controller key defined for signing outgoing documents!',
                                ['terminalId' => $item['terminalId']]
                            );
                        }
                    } else {
                        $class = 'success';
                        $url = '#';
                        $label = Yii::t('app/autobot', 'Start');
                        $data = [
                            'mode' => 'start-terminal',
                            'terminal' => $item['terminalId']
                        ];
                    }
                }

                $data['loading-text'] = "<i class='fa fa-spinner fa-spin'></i>{$label}";

                $html = Html::a(
                    $label,
                    $url,
                    [
                        'class' => "btn btn-{$class} btn-md btn-control",
                        'data' => $data
                    ]
                );

                return $html;
            },
            'headerOptions' => ['style' => 'text-align: center; width: 50px;'],
            'contentOptions' => ['style' => 'text-align: center;']
        ],
        [
            'format' => 'raw',
            'label' => '',
            'headerOptions' => ['style' => 'width: 10px;'],
            'value' => function ($item) {
                $options = ['class' => 'btn btn-default btn-md btn-control'];

                $url = Url::to(['/autobot/terminals/index', 'id' => $item['id']]);

                return Html::a(
                    Yii::t('app', 'Settings'),
                    $url,
                    $options
                );
            },
            'headerOptions' => ['style' => 'text-align: center; width: 50px;'],
            'contentOptions' => ['style' => 'text-align: center;']
        ],
    ],
]);

?>

    <!-- Модальное окно остановки всех процессов обмена -->
    <div class="modal fade" id="modal-stop-exchange" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <?=Yii::t('app/autobot', 'Do you want to stop the CyberFT network exchange for all terminals?')?>
                    </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?=Yii::t('app/autobot', 'Close')?>
                    </button>
                    <a type="button" href="<?=Url::toRoute('multiprocesses/stop-exchange')?>" class="btn btn-danger" data-method="post">
                        <?=Yii::t('app/autobot', 'Stop')?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно остановки автоматических процессов -->
    <div class="modal fade" id="modal-stop-autoprocesses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <?=Yii::t('app/autobot', 'Do you want to stop automatic processes?')?>
                    </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?=Yii::t('app/autobot', 'Close')?>
                    </button>
                    <a type="button" href="<?=Url::toRoute('multiprocesses/stop-jobs')?>" class="btn btn-danger" data-method="post">
                        <?=Yii::t('app/autobot', 'Stop')?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно запуска автоматических процессов -->
    <div class="modal fade" id="modal-start-autoprocesses" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">
                        <?=Yii::t('app/autobot', 'Do you want to start automatic processes?')?>
                    </h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?=Yii::t('app/autobot', 'Close')?>
                    </button>
                    <a type="button" href="<?=Url::toRoute('multiprocesses/start-jobs')?>" class="btn btn-success" data-method="post">
                        <?=Yii::t('app/autobot', 'Start')?>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Модальное окно с информацией о невозможности создания ключа для неактивного терминала -->
    <div class="modal fade warning-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">
                        <?=Yii::t('app/autobot', 'Close')?>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php

// JS
$script = <<< JS
    $('a[data-mode=start-terminal]').on('click', function(e) {
        e.preventDefault();
        
        $(this).button('loading');

        // Id указанного терминала
        var terminalId = $(this).data('terminal');

        $.ajax({
            url: '/autobot/terminals/terminal-control',
            type: 'post',
            data: {terminalId: terminalId, action: 'start'},
            success: function(res) {}
        });
    });

    $('a[data-mode=stop-terminal]').on('click', function(e) {
        $(this).button('loading');
    });

    // Событие клика по кнопке недоступности терминала
    $('a[data-mode=anv-show-info]').on('click', function() {
        $('.warning-modal .modal-title').html($(this).data('error-message'));
        $('.warning-modal').modal();
    });
JS;
$this->registerJs($script, View::POS_READY);

$this->registerCss('
    .btn-page-actions {
        margin-top: 13px;
    }

    .btn-control {
        padding: 5px !important;
        padding-left: 12px !important;
        padding-right: 12px !important;
        width: 120px;
        position: relative;
    }
    
    .btn-control .fa.fa-spinner {
        position: absolute;
        left: 3px;
        top: 25%;
    }
 
    .multiprocesses-index-row td {
        vertical-align: middle !important;
    }

    .table > tbody > tr.multiprocesses-index-row td {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
');
