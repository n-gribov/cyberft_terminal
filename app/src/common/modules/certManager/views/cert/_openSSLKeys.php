<?php

use backend\models\forms\UploadUserAuthCertForm;
use common\models\User;
use common\widgets\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $model User */
/* @var $uploadCertForm UploadUserAuthCertForm */
?>

<p>
    <?= $this->render('@backend/views/user/certs/_uploadCertForm', ['model' => $uploadCertForm, 'userId' => $model->id]) ?>
</p>
<?php
echo GridView::widget([
    'emptyText' => Yii::t('app/user', 'No certificates for selected user'),
    'summary' => Yii::t('other',
        'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $certDataProvider,
    'filterModel' => $certSearchModel,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options = [];
        // Выделение цветом истекающих и истекших сертификатов

        if (!$model->isActive) {
            $options['class'] = 'danger';
        } else if($model->isExpiringSoon()) {
            $options['class'] = 'cert-expire-soon';
        }

        $options['ondblclick'] = "window.location='".
                Url::toRoute(['/user-auth-cert/view', 'id' => $key]) . "'";

        return $options;
    },
    'columns' => [
//        [
//            'attribute' => 'id',
//            'label' => Yii::t('app/user', 'ID'),
//            'enableSorting' => false,
//        ],
        [
            'attribute' => 'subject',
            'format' => 'raw',
            'value' => function($model) {
                $out = $model->getMappedAttributes(
                    ['CN', 'OU', 'O', 'ST', 'C'],
                    $model->getSubject(true)
                );

                return implode(Html::tag('br'), $out);
            }
        ],
        [
            'attribute' => 'issuer',
            'format' => 'raw',
            'value' => function($model) {
                $out = $model->getMappedAttributes(
                    ['CN', 'OU', 'O', 'ST', 'C'],
                    $model->getIssuer(true)
                );

                return implode(Html::tag('br'), $out);
            }
        ],
        [
            'attribute' => 'fingerprint',
            'label' => Yii::t('app', 'Certificate fingerprint'),
        ],
        'serialNumber',
        [
            'attribute' => 'expiryDate',
            'filter' => false
        ],
        [
            'label' => Yii::t('app', 'Beneficiaries'),
            'format' => 'html',
            'filter' => false,
            'value' => function($model) {
                return implode(',<br/>', $model->beneficiaryList);
            }
        ],
        [
            'label' => Yii::t('app', 'Status'),
            'format' => 'html',
            'filter' => false,
            'value' => function ($item) {
                $style = '';

                // Статус активности терминала
                if ($item['status'] === 'active') {
                    $class = 'ic-key';
                } else {
                    $style = 'color: red !important;';
                    $class = 'glyphicon glyphicon-remove';
                }

                return "<span class='{$class}' style='{$style}'></span>";
            },
        ],
        [
            'class' => ActionColumn::class,
            'template' => '{replace} {view} {download} {delete}',
            'buttons' => [
                'replace' => function($url, $model, $key) {
                    if ($model->status !== 'active') {
                        return;
                    }

                    return Html::button(
                        Yii::t('app', 'Replace'),
                        [
                            'class' => 'btn btn-success replace-certificate-button',
                            'style' => 'margin-right: 10px;',
                            'data-id' => $model->id,
                            'type'  => 'button',
                        ]
                    );
                },
                'view' => function($url, $model, $key) {
                    return Html::a(
                        Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-open']),
                        ['/user-auth-cert/view', 'id' => $key],
                        ['title' => Yii::t('app', 'View')]
                    );
                },
                'download' => function($url, $model, $key) {
                    return Html::a(
                        Html::tag('span', '', ['class' => 'glyphicon glyphicon-download-alt']),
                        ['/user-auth-cert/download', 'id' => $key],
                        ['title' => Yii::t('app', 'Download')]
                    );
                },
                'delete' => function($url, $model, $key) {
                    return Html::a(
                        Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']),
                        ['/user-auth-cert/delete', 'id' => $key],
                        [
                            'title' => Yii::t('app', 'Delete'),
                            'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        ]
                    );
                },
            ],
            'contentOptions' => ['class' => 'text-right text-nowrap']
        ],
    ],
]);

$js = <<<JS
    $('.replace-certificate-button').click(function() {
        $('#uploaduserauthcertform-certid').val($(this).data('id'));
        certificateFileInput.trigger('click');
    });
JS;

$this->registerJs($js, View::POS_READY);
?>
