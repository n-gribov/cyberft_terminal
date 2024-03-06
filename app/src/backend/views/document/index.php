<?php

use common\document\Document;
use common\document\DocumentSearch;
use common\helpers\DocumentHelper;
use common\helpers\Html;
use common\models\User;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\documents\DeleteSelectedDocumentsButton;
use common\widgets\documents\SelectedDocumentsCountLabel;
use common\widgets\InfiniteGridView;
use common\widgets\ToTopButton\ToTopButtonWidget;
use kartik\widgets\Select2;
use yii\data\ActiveDataProvider;
use yii\helpers\Url;
use yii\web\JsExpression;

/* @var $this View */
/* @var $searchModel DocumentSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app', 'Documents');
$this->params['breadcrumbs'][] = $this->title;

// Получить роль пользователя из активной сессии
$isAdmin = in_array(Yii::$app->user->identity->role, [User::ROLE_ADMIN, User::ROLE_ADDITIONAL_ADMIN]);
$userCanDeleteDocuments = $isAdmin;
$deletableDocumentsIds = array_reduce(
    $dataProvider->models,
    function ($carry, Document $document) {
        if ($document->isDeletable()) {
            $carry[] = $document->id;
        }

        return $carry;
    },
    []
);

if (Yii::$app->user->can('admin')) {
    $sender = 'sender';
    $receiver = 'receiver';
} else {
    $sender = 'senderParticipantName';
    $receiver = 'receiverParticipantName';
}

$columns = [];

$columns['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'style' => 'width: 5%'
    ]
];

$columns['typePattern'] = [
    'attribute' => 'typePattern',
    'label' => Document::attributeLabels()['type'],
    'value' => function($item) {
        return $item->type;
    }
];

$columns['direction'] = [
    'attribute' => 'direction',
    'format' => 'html',
    'filter' => Document::getDirectionLabels(),
    'enableSorting' => true,
    'value' => function ($item, $params) {
        return Html::tag('span', Document::directionLabel($item->direction), ['title' => $item->direction]);
    },
    'filterInputOptions' => [
        'style' => 'width: 100%',
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
];

$columns['sender'] = [
    'attribute' => $sender,
    'filter' => true,
    'filterInputOptions' => [
        'maxLength' => 12,
    ],
    'format' => 'html',
];

$columns['receiver'] = [
    'attribute' => $receiver,
        'filter' => true,
        'filterInputOptions' => [
            'maxLength' => 12,
        ],
        'format' => 'html'
];

$columns['status'] = [
    'attribute'     => 'status',
    'format'        => 'html',
    'filter' => Select2::widget([
        'model' => $searchModel,
        'attribute' => 'status',
        'data' => DocumentHelper::getStatusLabelsAll(),
        'theme' => Select2::THEME_BOOTSTRAP,
        'options' => [
            'prompt' => '',
        ],
        'pluginOptions' => [
            'allowClear' => true,
            'containerCssClass' => 'select2-cyberft'
        ],
    ]),
    'value' => function ($item, $params) {
        $status = DocumentHelper::getStatusLabel($item);

        return Html::tag('span', $status['label'], ['title' => 'Status: ' . $status['name']]);
    },
];

$columns['dateCreate'] = [
    'attribute' => 'dateCreate',
    'filterInputOptions' => [
        'maxLength' => 64,
    ],
    'filter' => $isAdmin
        ? kartik\widgets\DateTimePicker::widget([
            'model' => $searchModel,
            'attribute' => 'dateCreate'
        ])
        : false
];

if ($searchModel->hasHighlights()) {
    $columns['body'] = [
        'label'     => Yii::t('doc', 'Document body'),
        'format'        => 'html',
        'value'         => function($item, $params) use($searchModel) {
            return $searchModel->getHighlights($item, 'body', '');
        }
    ];
}

if ($userCanDeleteDocuments) {
    echo DeleteSelectedDocumentsButton::widget(['checkboxesSelector' => '.delete-checkbox, .select-on-check-all']);
    echo SelectedDocumentsCountLabel::widget(['checkboxesSelector' => '.delete-checkbox, .select-on-check-all']);
}

// Вывести форму поиска
echo $this->render('_search', [
    'model' => $searchModel,
    'filterStatus' => $filterStatus,
    'enableDeletableDocumentsFilter' => $userCanDeleteDocuments,
    'deletableDocumentsFilterValue' => $searchModel->showDeletableOnly
]);

if ($userCanDeleteDocuments && count($deletableDocumentsIds) > 0) {
    $deleteColumn = [
        'delete' => [
            'class'           => 'yii\grid\CheckboxColumn',
            'checkboxOptions' => function ($model, $key, $index, $column) use ($deletableDocumentsIds) {
                $hidden = !in_array($model->id, $deletableDocumentsIds);

                return [
                    'style'    => 'display: ' . ($hidden ? 'none' : 'block'),
                    'disabled' => $hidden,
                    'class'    => 'delete-checkbox',
                    'value'    => $key,
                    'data-id'  => (string) $model->id
                ];
            }
        ]
    ];
} else {
    $deleteColumn = [
        'delete' => [
            'attribute' => '',
            'value' => function() {
                return '';
            }
        ]
    ];
}

// Получение колонок, которые могут быть отображены
$columnsEnabled = array_merge(
    $deleteColumn,
    UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id),
    [
        'view' => [
            'attribute' => '',
            'format' => 'html',
            'filterInputOptions' => [
                'style'     => 'width: 20px'
            ],
            'value' => function ($item, $params) use ($urlParams) {
                return Html::a('<span class="ic-eye"></span>',
                    Url::toRoute(array_merge(['view', 'id' => $item->id, 'redirectUrl' => '/document/index'], $urlParams)), ['title' => 'Просмотр']);
            }
        ]
    ]
);
// Создать таблицу для вывода
echo InfiniteGridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'highlightsByStatus' => true,
    'rowOptions' => function ($model, $key, $index, $grid) use ($urlParams) {
        $options['ondblclick'] = "window.location='" . Url::toRoute(array_merge(['view', 'id' => $model->id, 'redirectUrl' => '/document/index'], $urlParams)) . "'";
        $options['data']['status'] = $model->status;
        $options['data']['document-type'] = $model->type;

        // т.к. это общая свалка, обрабатываем бизнес-статусы для определенных документов здесь

        if ($model->type == 'auth.024') {
            $extModel = $model->extModel;
            if (!$extModel) {
                Yii::error('No extmodel found for Auth.024 id ' . $model->id);
                $options['class'] = 'disabled';
            } else {
                if ($extModel->statusCode == 'RJCT') {
                    $options['class'] = 'danger';
                }
            }
        }

        return $options;
    },
    'onPageRendered' => new JsExpression('function () { checkForSelectableDocument(); }'),
    'columns' => $columnsEnabled,
    'options' => ['class' => 'grid-view documents-journal-grid'],
]);

echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($columns),
    'model' => $searchModel
]);

echo ToTopButtonWidget::widget();

// Модальное окно формы поиска
echo $this->render('@addons/edm/views/documents/_searchModal', ['model' => $searchModel]);

$this->registerCss('#delete-selected-documents-button {display: none;}');

$this->registerJS(<<<JS
    stickyTableHelperInit();
JS);
