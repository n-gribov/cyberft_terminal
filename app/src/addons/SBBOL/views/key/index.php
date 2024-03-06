<?php

use addons\SBBOL\models\SBBOLKey;
use common\helpers\Html;
use common\widgets\GridView;
use yii\helpers\Url;

/** @var $this \yii\web\View */
/** @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = Yii::t('app/menu', 'Keys');

echo Html::button(
    Yii::t('app/sbbol', 'Register key'),
    [
        'id'    => 'register-key-button',
        'class' => 'btn btn-success',
        'style' => 'margin-bottom: 15px',
        'data'  => ['toggle' => 'modal', 'target' => '#register-key-modal']
    ]
);
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => \yii\grid\SerialColumn::class],
        'bicryptId',
        [
            'attribute' => 'keyOwnerId',
            'value' => function (SBBOLKey $model) {
                return $model->keyOwner->fullName;
            },
        ],
        [
            'label' => Yii::t('app/sbbol', 'Customer'),
            'value' => function (SBBOLKey $model) {
                return $model->keyOwner->customer->shortName;
            },
        ],
        'statusLabel',
        'certificateFingerprint',
        [
            'class' => \yii\grid\ActionColumn::class,
            'template' => '{downloadPrintableCertificate}',
            'buttons' => [
                'downloadPrintableCertificate' => function ($url, $model, $key) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-file" title="' . Yii::t('app/sbbol', 'Download printable certificate') . '"></span>',
                        Url::to(['download-printable-certificate', 'id' => $model->id])
                    );
                },
            ],
            'contentOptions' => ['class' => 'text-right'],
        ]
    ],
]);
// Вывести модальное окно с формой регистрации ключа
echo $this->render('_registerKeyModal');
$this->registerJs(<<<JS
    $('#register-key-button').click(
        function () {
            RegisterKeyForm.showGenerateCertificateRequestParamsForm();
        }
    )
JS);
