<?php

use common\helpers\Currencies;
use common\models\ImportErrorSearch;
use common\models\UserColumnsSettings;
use common\widgets\GridView;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;
use common\helpers\Html;
use common\models\ImportError;
use common\helpers\DateHelper;

/** @var ImportErrorSearch $searchModel */

$this->title = Yii::t('app/menu', 'Import errors');

?>

<p class="pull-right">
    <?php
    echo Html::a('',
        '#',
        [
            'class' => 'btn-columns-settings glyphicon glyphicon-cog',
            'title' => Yii::t('app', 'Columns settings')
        ]
    );
    ?>
</p>


<?php
$columns['id'] = [
    'attribute' => 'id',
    'headerOptions' => [
        'style' => 'width: 5%'
    ]
];

$columns['type'] = [
    'attribute' => 'type',
    'filter' => ImportError::typeLabels(),
    'value' => function($model) {
        return $model->getTypeLabel();
    }
];

$columns['dateCreate'] = [
    'attribute' => 'dateCreate',
    'value' => function($model) {
        return DateHelper::formatDate($model->dateCreate, 'datetime');
    },
    'filter' => kartik\widgets\DatePicker::widget([
        'model' => $searchModel,
        'attribute' => 'dateCreate',
        'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'dd.mm.yyyy',
            'todayHighlight' => true,
            'orientation' => 'bottom'
        ],
        'options' => [
            'class' => 'form-control',
        ]
    ]),
    'headerOptions' => [
        'style' => 'width:180px'
    ]
];

$columns['identity'] = [
    'attribute' => 'identity',
];

$columns['filename'] = [
    'attribute' => 'filename',
];

$columns['documentType'] = [
    'attribute' => 'documentTypeName',
    'filter' => false,
];

$columns['documentNumber'] = [
    'attribute' => 'documentNumber',
];

$columns['senderTerminalAddress'] = [
    'attribute' => 'senderTerminalAddress',
    'value'     => function (ImportError $importError) {
        return $importError->senderName;
    },
    'filter'    => $searchModel->getSenderFilter(),
];

$columns['documentCurrency'] = [
    'attribute' => 'documentCurrency',
    'filter' => Currencies::getCodeLabels(),
    'filterInputOptions' => [
        'data-width' => '75px',
        'class' => 'form-control selectpicker',
        'data-none-selected-text' => ''
    ],
];

$columns['errorDescription'] = [
    'attribute' => 'errorDescription',
    'filter' => false,
    'value' => function($model) {
        $data = $model->errorDescriptionData;

        $text = '';
        $params = [];

        if (isset($data['text'])) {
            $text = $data['text'];
        }

        if (isset($data['params'])) {
            $params = $data['params'];
        }

        return Yii::t('other', $text, $params);
    }
];

// Получение колонок, которые могут быть отображены
$columnsEnabled = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

$today = new DateTime;
$todayFormat = $today->format('Y-m-d');
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns' => $columnsEnabled,
    'rowOptions' => function ($model) use ($todayFormat) {
        $options = [];
        // Выделение непросмотренных
        // документов за сегодняшний день
        $date = new DateTime($model->dateCreate);
        $dateFormat = $date->format('Y-m-d');

        if ($todayFormat == $dateFormat) {
            $options['style'] = "font-weight: bold;";
        }

        return $options;
    },
    'formatter' => [
        'class' => 'yii\i18n\Formatter',
        'nullDisplay' => '',
    ],
]);

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $model
    ]
);

$script = <<<JS
    // Маска для ввода значения даты
    $("#importerrorsearch-datecreate").inputmask("99.99.9999", {placeholder:"дд.мм.гггг"});
JS;

$this->registerJs($script, yii\web\View::POS_READY);