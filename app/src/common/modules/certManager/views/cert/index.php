<?php

use common\helpers\Html;
use common\modules\certManager\models\Cert;
use common\modules\certManager\models\CertSearch;
use common\widgets\GridView;
use common\widgets\tabs\Tabs;
use yii\data\ActiveDataProvider;
use yii\web\View;
use kartik\widgets\Select2;
use yii\jui\DatePicker;
use yii\helpers\Url;

/* @var $this View */
/* @var $searchModel CertSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app/cert', 'Certificates');
$this->params['breadcrumbs'][] = $this->title;

// Добавлять сертификаты могут только уполномоченные пользователи
$canManage = Yii::$app->user->can('commonCertificates');

if ($canManage) {
    $this->beginBlock('pageActions');
    echo Html::a(Yii::t('app/cert', 'Add certificate', [
        'modelClass' => 'Cert',
    ]),
        ['create'],
        ['class' => 'btn btn-success'],
        'ic-plus');


    $checkCertsExport = \common\modules\certManager\Module::checkCertsExports();

    if ($checkCertsExport) {

        echo '&nbsp' . Html::a('Выгрузка сертификата в ДБО', '/certManager/cert/export-certs-dbo', ['class' => 'btn btn-primary'], 'ic-folder');
    }

    $this->endBlock('pageActions');
}

echo Tabs::widget([
    'items' => [
        [
            'title' => Yii::t('app/cert', 'Signers'),
            'url' => Url::to(['/certManager/cert/index', 'role' => Cert::ROLE_SIGNER]),
            'isActive' => $searchModel->role == Cert::ROLE_SIGNER,
        ],
        [
            'title' => Yii::t('app/cert', 'Controllers'),
            'url' => Url::to(['/certManager/cert/index', 'role' => Cert::ROLE_SIGNER_BOT]),
            'isActive' => $searchModel->role == Cert::ROLE_SIGNER_BOT,
        ],
    ]
]);
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options['ondblclick'] = "window.location='". Url::toRoute(['/certManager/cert/view', 'id'=>$model->id]) ."'";

        // Выделение цветом истекающих и истекших сертификатов

        if (!$model->isActive) {
            $options['class'] = 'danger';
        } else if ($model->isExpiringSoon()) {
            $options['class'] = 'cert-expire-soon';
        }

        return $options;
    },
    'columns' => [
        [
            'attribute' => 'id',
            'width' => 'narrow',
        ],
        [
            'attribute' => 'participantName',
            'label' => Yii::t('edm', 'Organization'),
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'participantName',
                'data' => $participants,
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'prompt' => '',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'containerCssClass' => 'select2-cyberft'
                ],
            ]),
            'value' => 'participant.name',
        ],
        [
            'attribute' => 'participantBIC',
            'label' => Yii::t('app/cert', 'Participant Id'),
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'participantBIC',
                'data' => $participantIds,
                'theme' => Select2::THEME_BOOTSTRAP,
                'options' => [
                    'prompt' => '',
                ],
                'pluginOptions' => [
                    'allowClear' => true,
                    'containerCssClass' => 'select2-cyberft',
                ],
            ]),
            'value' => 'terminalAddress'
        ],
        'fingerprint',
        [
            'attribute' => 'status',
            'filter' => CertSearch::getStatusLabels(),
            'value' => function($model) {
                return $model->getStatusLabel();
            }
        ],
        [
            'attribute' => 'useBefore',
            'format' => ['date', 'dd.MM.Y'],
            'filter' => DatePicker::widget(
                [
                    'model' => $searchModel,
                    'attribute' => 'useBefore',
                    'options' => [
                        'class' => 'form-control',
                        'style'     => 'width: 100px'
                    ]
                ]
            ),
        ],
        [
            'attribute' => 'fullName',
            'label' => Yii::t('app/cert', 'Owner Name'),
            'contentOptions' => [
                'style' => 'width: 300px'
            ]

        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'visibleButtons' => [
                'view' => function (Cert $model, $key, $index) {
                    return !$model->autoUpdate;
                },
                'update' => function (Cert $model, $key, $index) use ($canManage) {
                    return !$model->autoUpdate && $canManage;
                },
                'delete' => function (Cert $model, $key, $index) use ($canManage) {
                    return !$model->autoUpdate && $canManage;
                }
            ],
            'contentOptions' => [
                'style' => 'min-width: 96px'
            ]
        ]
    ],
]);

$script = <<<JS
    // Формат поля выбора дат
    $('#certsearch-usebefore').datepicker('option', 'dateFormat', 'dd.mm.yy');
JS;

$this->registerJs($script, yii\web\View::POS_READY);


