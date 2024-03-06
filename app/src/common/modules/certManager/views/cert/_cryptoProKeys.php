<?php

use common\widgets\GridView;
use yii\jui\DatePicker;
use yii\helpers\ArrayHelper;
use common\helpers\Html;
use common\models\CryptoproKeySearch;
use yii\bootstrap\Modal;
use common\helpers\CryptoProHelper;
use common\models\UserColumnsSettings;
use common\widgets\ColumnsSettings\ColumnsSettingsWidget;

// Имя журнала для виджета настроек
$listType = 'crKeysSettings';

// Получение текушего состояния лицензии КриптоПро
$cpLicense = CryptoProHelper::checkCPLicense();

if (!$cpLicense) {
    // Поместить в сессию флаг сообщения об истёкшей лицензии Криптопро
    Yii::$app->session->setFlash('error', Yii::t('app/cert', 'CryptoPro license is expired'));
}

// Вывод кнопки управления списком полей
echo Html::a('',
    '#',
    [
        'class' => 'btn-columns-settings glyphicon glyphicon-cog pull-right',
        'title' => Yii::t('app', 'Columns settings')
    ]
);

?>

<?php

// Текущая дата
$now = strtotime(date('c'));

$columns = [];

$columns['id'] = [
    'attribute' => 'id',
    'filter' => false
];

$columns['organization'] = [
    'attribute' => 'organization',
    'label' => Yii::t('app/fileact', 'Terminals'),
    'filter' => CryptoproKeySearch::getAvailableOrganizations(),
    'value' => function($item) {
        // Получение результатов в виде массива
        $terminals = $item->getTerminals()->select('title')->asArray()->all();

        // Преобразование в более удобный массив для конвертации в строку
        $terminals = ArrayHelper::getColumn($terminals, 'title');

        return implode(", ", $terminals);
    },
    'headerOptions' => [
        'style' => 'width: 200px'
    ]
];

$columns['ownerName'] = [
    'attribute' => 'ownerName',
    'filter' => true,
    'headerOptions' => [
        'style' => "width: 200px",
    ],
];

$columns['keyId'] = [
    'attribute' => 'keyId',
    'filter' => true,
    'value' => function($model) {
        return strtoupper($model->keyId);
    },
    'filterInputOptions' => [
        'maxlength' => '40'
    ]
];

$columns['serialNumber'] = [
    'attribute' => 'serialNumber',
    'filter' => true,
    'filterInputOptions' => [
        'maxlength' => '38'
    ]
];

$columns['expireDate'] = [
    'attribute' => 'expireDate',
    'format' => 'date',
    'headerOptions' => [
        'style' => "width: 140px",
    ],
    'filter' => DatePicker::widget(
        [
            'model' => $cryptoproKeysSearch,
            'attribute' => 'expireDate',
            'options' => [
                'class' => 'form-control'
            ],
            'clientOptions' => [
                'dateFormat' => 'dd.mm.yy',
            ]
        ]
    )
];

$columns['status'] = [
    'attribute'     => 'status',
    'filter'		=> $cryptoproKeysSearch->getActiveLabels(),
    'format'        => 'html',
    'value'         => function ($item, $params) {
        return $item->getActiveLabel();
    },
    'headerOptions' => [
        'style' => "width: 140px",
    ],
];

// Получение колонок, которые могут быть отображены
$columnsEnabled = UserColumnsSettings::getEnabledColumnsByType($columns, $listType, Yii::$app->user->id);

$columnsEnabled['manage'] = [
    'attribute' => '',
    'format' => 'raw',
    'value' => function ($item, $params) use ($now) {
        if ($item->active) {
            return Html::a(Yii::t('app/cert', 'Deactivate'), null,
                [
                    'class' => 'btn btn-danger btn-deactivate',
                    'data' => [
                        'id' => $item->id
                    ]
                ]
            );
        } else {

            // Если сертификат просрочен, его нельзя активировать
            $expirationDate = strtotime($item->expireDate);

            if ($expirationDate > $now) {
                return Html::a(Yii::t('app/cert', 'Activate'), null, [
                    'class' => 'btn btn-success btn-activate',
                    'data' => [
                        'id' => $item->id
                    ]
                ]);
            } else {
                return '';
            }
        }
    }
];

?>

<div class="row">
    <div class="col-sm-12">
    <?php
        // Создать таблицу для вывода
        echo GridView::widget([
            'summary' => '',
            'dataProvider' => $cryptoproKeys,
            'filterModel' => $cryptoproKeysSearch,
            'columns' => $columnsEnabled,
            'rowOptions' => function($model, $key, $index, $grid) use ($now) {
                // Выделение строк сертификатов по условиям
                if ($model->expireDate == '0000-00-00 00:00:00') {
                    // Если дата истечения сертификата не указана, это ошибка
                    return ['class' => 'danger'];
                } else {
                    // Если дата истечения просрочена, это ошибка
                    $expirationDate = strtotime($model->expireDate);
                    if ($expirationDate < $now) {
                        return ['class' => 'danger'];
                    }
                }
            }
        ]);
    ?>
    </div>
</div>

<?php

$this->registerCss('.btn-columns-settings { margin-top: 6px; }');

$script = <<<JS
    $('.btn-activate').on('click', function(e) {
        e.preventDefault();

        var id = $(this).data('id');

        // Получение формы активации сертификата
        $.ajax({
            url: '/certManager/cert/get-activation-form',
            data: { id: id },
            type: 'get',
            success: function(answer){
                $('#certModal .modal-body').html(answer);
            }
        });

        $('#certModal').modal('show');
    });

    $('.btn-deactivate').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');

        var formData = new FormData();
        formData.append('CryptoproKey[password]', '');
        formData.append('CryptoproKey[active]', 0);
        // Запрос на деактивацию сертификата
        $.ajax({
            url: '/cryptopro-keys/update?id=' + id + '&redirect=1',
            data: formData,
            type: 'post',
            processData: false,
            contentType: false
        });
    });

    $('#cryptoprokeyssearch-expiredate').datepicker('option', 'dateFormat', 'dd.mm.yy');
    $('#cryptoprokeyssearch-expiredate').inputmask('99.99.9999', { placeholder: 'дд.мм.гггг' });
JS;

$this->registerJs($script, yii\web\View::POS_READY);


$header = '<h4 class="modal-title" id="myModalLabel">' . Yii::t('app/cert', 'Key activation') . '</h4>';
$footer = '<button type="button" class="btn btn-default" data-dismiss="modal">' . Yii::t('app', 'Close') . '</button>
            <button type="submit" class="btn btn-primary btn-submit-activate-form">' . Yii::t('app', 'Save') . '</button>';

$modal = Modal::begin([
    'id' => 'certModal',
    'header' => $header,
    'footer' => $footer
]);
$modal::end();

echo ColumnsSettingsWidget::widget(
    [
        'listType' => $listType,
        'columns' => array_keys($columns),
        'model' => $cryptoproKeysSearch
    ]
);
