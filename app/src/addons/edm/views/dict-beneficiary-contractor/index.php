<?php

use addons\edm\EdmModule;
use addons\edm\models\DictContractorSearch;
use addons\edm\models\EdmDocumentTypeGroup;
use common\document\DocumentPermission;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\widgets\GridView;
use common\widgets\InlineHelp\InlineHelp;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DictContractorSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title                   = Yii::t('app/menu', 'Beneficiary Contractors Directory');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;

$userCanDeleteBeneficiaries = Yii::$app->user->can(
    DocumentPermission::DELETE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
    ]
);
$userCanEditBeneficiaries = Yii::$app->user->can('admin')
    || Yii::$app->user->can(
        DocumentPermission::CREATE,
        [
            'serviceId' => EdmModule::SERVICE_ID,
            'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT,
        ]
    );

?>

<div class="pull-right">
    <?php
    echo Html::a('',
        '#',
        [
            'class' => 'btn-columns-settings glyphicon glyphicon-cog',
            'title' => Yii::t('app', 'Columns settings')
        ]
    );

    echo InlineHelp::widget(['widgetId' => 'edm-beneficiary-contractor-journal', 'setClassList' => ['edm-journal-wiki-widget']]);
    ?>
</div>

<?php

    $columns['bankName'] = [
        'attribute' => 'bankName',
        'value' => 'bank.name',
        'filterInputOptions' => [
            'style'     => 'margin-left: -7px',
        ],
        'headerOptions' => [
            'style'     => 'padding-left: 1px',
        ],
    ];

    $columns['kpp'] = [
        'attribute' => 'kpp',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 110px;'
        ],
        'filterInputOptions' => [
            'maxLength' => 10,
            'style'     => 'float:right; width: 110px;',
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 110px;'
        ],
    ];

    $columns['inn'] = [
        'attribute' => 'inn',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 125px;'
        ],
        'filterInputOptions' => [
            'maxLength' => 12,
            'style'     => 'float:right; width: 125px;'
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 125px;'
        ],
    ];

    $columns['account'] = [
        'attribute' => 'account',
        'filterInputOptions' => [
            'maxLength' => 20,
        ],
    ];

    $columns['bankBik'] = [
        'attribute' => 'bankBik',
        'headerOptions' => [
            'class' => 'text-right',
            'style' => 'width: 105px;'
        ],
        'filterInputOptions' => [
            'maxLength' => 9,
            'style'     => 'float:right; width: 105px;'
        ],
        'contentOptions' => [
            'class' => 'text-right',
            'style' => 'width: 105px;'
        ],
    ];

    $columns['name'] = [
        'attribute' => 'name',
    ];

    $columns['type'] = [
        'attribute' => 'type',
        'filter'		 => $searchModel->typeValues(),
        'value' => function($item) {
            return $item->getTypeLabel();
        },
        'headerOptions' => [
            'style' => 'width: 175px;'
        ],
        'filterInputOptions' => [
            'maxLength' => 9,
            'style'     => 'width: 175px;'
        ],
        'contentOptions' => [
            'style' => 'width: 175px;'
        ],
    ];

    // Получение колонок, которые могут быть отображены
    $columnsSettings = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

    // Обязательные колонки, которые должны отображаться в любом случае
    $columnsSettings['actions'] = [
        'class'    => 'yii\grid\ActionColumn',
        'contentOptions' => [
            'style' => 'min-width: 100px;',
            'class' => 'text-right',
        ],
        'visibleButtons' => [
            'view' => true,
            'update' => $userCanEditBeneficiaries,
            'delete' => $userCanDeleteBeneficiaries,
        ],
    ];

    $gridOptions = [
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'rowOptions' => function ($model){
            $options['ondblclick'] = "window.location='".
                Url::toRoute(['view', 'id' => $model->id]) ."'";

            return $options;
        },
        'columns' => $columnsSettings,
];

// Создать таблицу для вывода
echo GridView::widget($gridOptions);

echo ColumnsSettingsWidget::widget([
    'listType' => $listType,
    'columns' => array_keys($columns),
    'model' => $searchModel
]);
