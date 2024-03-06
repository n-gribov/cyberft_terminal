<?php
use addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrderTemplate;
use common\helpers\Html;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\GridView;
use common\widgets\InlineHelp\InlineHelp;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$this->title = Yii::t('app/menu', 'My templates');
$this->beginBlock('pageActions');
$templates = [
    'PaymentOrder' => 'Платежное поручение',
    'ForeignCurrencyPayment' => 'Валютный платеж'
];
?>
<div class="dropdown">
    <button class="btn btn-success dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        <span class="ic-plus"></span><?= Yii::t('edm', 'Add template') ?><span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
        <li>
            <?=Html::a($templates['PaymentOrder'], '#', ['class' => 'edm-template-new-po-modal-btn']); ?>
        </li>
        <li>
            <?=Html::a($templates['ForeignCurrencyPayment'], '#', ['class' => 'edm-template-new-fcp-modal-btn']); ?>
        </li>
    </ul>
</div>
<?php $this->endBlock('pageActions'); ?>
<p class="pull-right">
    <?php
        echo Html::a('',
            '#',
            [
                'class' => 'btn-columns-settings glyphicon glyphicon-cog',
                'title' => Yii::t('app', 'Columns settings')
            ]
        );

        echo InlineHelp::widget(['widgetId' => 'edm-payment-order-templates-journal', 'setClassList' => ['edm-journal-wiki-widget']]);
    ?>
</p>
<?php
$columns['type'] = [
    'attribute' => 'type',
    'label' => $model->getAttributeLabel('type'),
    'filter' => Html::dropDownList('type', isset($queryParams['type']) ? $queryParams['type'] : null, [
        'paymentOrder' => 'Платежное поручение',
        'foreignCurrencyPayment' => 'Валютный платеж'
    ], ['class' => 'form-control', 'prompt' => '']),
    'headerOptions' => [
        'style' => 'width: 170px;'
    ],
    'value' => function($model) use ($templates) {
        if (isset($templates[$model['type']])) {
            return $templates[$model['type']];
        } else {
            return '';
        }
    }
];

$columns['name'] = [
    'attribute' => 'name',
    'label' => $model->getAttributeLabel('name'),
    'filter' => Html::textInput(
        'name', isset($queryParams['name']) ? $queryParams['name'] : '', ['class' => 'form-control']
    ),
];

$columns['payerName'] = [
    'attribute' => 'payerName',
    'label' => $model->getAttributeLabel('payerName'),
    'filter' => Html::textInput(
        'payerName', isset($queryParams['payerName']) ? $queryParams['payerName'] : '', ['class' => 'form-control']
    ),
];

$columns['payerAccount'] = [
    'attribute' => 'payerAccount',
    'label' => $model->getAttributeLabel('payerAccount'),
    'filter' => Select2::widget([
        'model' => $model,
        'attribute' => 'payerAccount',
        'data' => ArrayHelper::map($dataProvider->getModels(), 'payerAccount', 'payerAccount'),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'prompt' => '',
    ],
        'pluginOptions' => [
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft',
            'width' => '180px'
        ],
    ]),
];

$columns['beneficiaryName'] = [
    'attribute' => 'beneficiaryName',
    'label' => $model->getAttributeLabel('beneficiaryName'),
    'filter' => Html::textInput(
        'beneficiaryName', isset($queryParams['beneficiaryName']) ? $queryParams['beneficiaryName'] : '', ['class' => 'form-control']
    ),
];

$columns['sum'] = [
    'attribute' => 'sum',
    'format' => ['decimal', 2],
    'contentOptions' => [
        'class' => 'text-right',
    ],
    'headerOptions' => [
        'class' => 'text-right',
    ],
    'label' => $model->getAttributeLabel('sum'),
    'filter' => Html::textInput(
        'sum', isset($queryParams['sum']) ? $queryParams['sum'] : '', ['class' => 'form-control']
    ),
];

$columns['currency'] = [
    'attribute' => 'currency',
    'label' => $model->getAttributeLabel('currency'),
    'filter' => Html::textInput(
        'currency', isset($queryParams['currency']) ? $queryParams['currency'] : '', ['class' => 'form-control']
    ),
];

$columns['paymentPurpose'] = [
    'attribute' => 'paymentPurpose',
    'label' =>  $model->getAttributeLabel('paymentPurpose'),
    'filter' => Html::textInput(
        'paymentPurpose', isset($queryParams['paymentPurpose']) ? $queryParams['paymentPurpose'] : '', ['class' => 'form-control']
    ),
];

$columnsEnabled = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

// Обязательные колонки, которые должны отображаться в любом случае
$columnsEnabled['actions'] = [
    'class' => 'yii\grid\ActionColumn',
    'template' => '{view} {update} {delete} {create-from-template}',
    'buttons' => [
        'create-from-template' => function($url, $model, $key) {
            $options = [
                'class' => 'template-load-link',
                'title' => Yii::t('app', 'Create document'),
            ];
            if ($model['type'] == 'PaymentOrder') {
                $createUrl = ['/edm/payment-order-templates/create-payment-order', 'id' => $model['id']];
                $template = PaymentRegisterPaymentOrderTemplate::findOne($model['id']);
                $options['data']['is-outdated'] = (int)$template->isOutdated;
            } else if ($model['type'] == 'ForeignCurrencyPayment') {
                $createUrl = ['/edm/currency-payment/payment-index', 'template' => $model['id']];
            }

            return Html::a(
                '<span class="glyphicon glyphicon-open-file">',
                Url::to($createUrl),
                $options
            );
        },
        'update' => function($model, $key, $index) {
            if ($key['type'] == 'PaymentOrder') {
                $updateBtnClass = 'edm-template-po-modal-update';
            } else if ($key['type'] == 'ForeignCurrencyPayment') {
                $updateBtnClass = 'edm-template-fcp-modal-update';
            }

            return Html::a(
                '<span class="glyphicon glyphicon-pencil">',
                Url::to(['payment-order-templates/create-payment-order', 'id' => $key['id']]),
                [
                    'title' => Yii::t('app', 'Update'),
                    'class' => $updateBtnClass,
                    'data' => [
                        'id' => $key['id']
                    ]
                ]
            );
        },
        'delete' => function($model, $key, $index) {
            if ($key['type'] == 'PaymentOrder') {
                $deleteUrl = 'payment-order-templates/payment-order-delete';
            } else if ($key['type'] == 'ForeignCurrencyPayment') {
                $deleteUrl = 'payment-order-templates/fcp-delete';
            }

            return Html::a(
                '<span class="glyphicon glyphicon-trash">',
                Url::to([$deleteUrl, 'id' => $key['id']]),
                [
                    'title' => Yii::t('app', 'Delete'),
                    'data' => [
                        'confirm' => Yii::t('app/cert', 'Are you sure you want to delete this item?'),
                    ]
                ]
            );
        },
        'view' => function($url, $model, $key) {
            if ($model['type'] == 'PaymentOrder') {
                $viewBtnClass = 'edm-template-po-view-modal-btn';
            } else if ($model['type'] == 'ForeignCurrencyPayment') {
                $viewBtnClass = 'edm-template-fcp-view-modal-btn';
            }

            return Html::a(
                '<span class="glyphicon glyphicon-eye-open">',
                '#',
                [
                    'title' => Yii::t('app', 'View'),
                    'class' => $viewBtnClass,
                    'data' => [
                        'id'          => $model['id'],
                        'name'        => $model['name'],
                    ]
                ]
            );
        },

    ],
    'contentOptions' => [
        'style' => 'min-width: 125px;'
    ]
];
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $model,
    'columns' => $columnsEnabled,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $isOutdated = false;
        if ($model['type'] == 'PaymentOrder') {
            $template = PaymentRegisterPaymentOrderTemplate::findOne($model['id']);
            $isOutdated = $template->isOutdated;
        }
        $options['data-is-outdated'] = (int)$isOutdated;
        $options['ondblclick'] = "showEdmTemplateViewModal(" . $model['id'] . ", '" . $model['name']  . "', '" . $model['type'] . "', " . (int)$isOutdated . ")";

        return $options;
    },
]);
    
echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($columns),
    'model' => $model
]);

// (c) kovalenko: MODAL SPIK'ище, кровососка гадская, подкостыливаем отжатие фокуса на модалку у Select2
$script = <<<JS
$.fn.modal.Constructor.prototype.enforceFocus = function () {};
JS;
$this->registerJs($script, yii\web\View::POS_READY);

// Вывести модальное окно с просмотром
echo $this->render('payment-order/_modalView');
// Вывести модальное окно с формой редактирования ПП
echo $this->render('payment-order/_modalForm');
// Вывести модальное окно с просмотром
echo $this->render('foreign-currency-payment/_modalView');
// Вывести модальное окно с формой редактирования ВП
echo $this->render('foreign-currency-payment/_modalForm');
// Добавить скрипт для редактирования
echo $this->render('_update-template-js');
