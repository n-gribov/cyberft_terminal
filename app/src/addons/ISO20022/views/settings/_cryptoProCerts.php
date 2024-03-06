<?php

use common\helpers\DateHelper;
use common\models\CryptoproCert;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="row" style="margin-top: 15px;">
    <div class="col-sm-12">

        <div class="row">
            <div class="col-sm-2">
                <?=Html::a(Yii::t('app/iso20022', 'Add certificate'), ['/cryptopro-certs-iso-20022/create'], ['class' => 'btn btn-primary']);?>
            </div>
        </div>

        <?= GridView::widget([
            'summary' => '',
            'dataProvider' => $cryptoproCert,
            'filterModel' => $cryptoproCertSearch,
            'rowOptions'	=> function($model, $key, $index, $grid) {

                // Выделение строк сертификатов по условиям

                if ($model->validBefore == '0000-00-00 00:00:00') {
                    // Если дата истечения сертификата не указана, это ошибка
                    return ['class'=>'danger'];
                } else {
                    // Если дата истечения просрочена, это ошибка
                    $now = strtotime(date('c'));
                    $expirationDate = strtotime($model->validBefore);

                    if ($expirationDate < $now) {
                        return ['class'=>'danger'];
                    }

                    // Если до просрочки даты истечения осталось менее 30 дней,
                    // это предупреждение
                    $datetimeNow = new DateTime(date('Y-m-d H:i:s'));
                    $datetimeExpiration = new DateTime($model->validBefore);
                    $interval = $datetimeNow->diff($datetimeExpiration);
                    if ($interval->days <= 30) {
                        return ['style'=>'background-color: #FFCC33'];
                    }
                }
            },
            'columns' => [
                [
                    'attribute' => 'id',
                    'filter' => false,
                    'contentOptions' => [
                        'style' => "width: 45px; word-wrap: break-word;",
                    ],
                    'headerOptions' => [
                        'style' => "width: 45px; word-wrap: break-word;",
                    ],
                ],
                [
                    'attribute' => 'ownerName',
                    'filter' => true,
                    'contentOptions' => [
                        'style' => "width: 91px; word-wrap: break-word;",
                    ],
                    'headerOptions' => [
                        'style' => "width: 91px; word-wrap: break-word;",
                    ],
                ],
                [
                    'attribute' => 'keyId',
                    'filter' => true,
                    'contentOptions' => [
                        'style' => "width: 341px; word-wrap: break-word;",
                    ],
                    'headerOptions' => [
                        'style' => "width: 341px; word-wrap: break-word;",
                    ],
                ],
                [
                    'attribute' => 'serialNumber',
                    'filter' => true,
                    'contentOptions' => [
                        'style' => "width: 306px; word-wrap: break-word;",
                    ],
                    'headerOptions' => [
                        'style' => "width: 306px; word-wrap: break-word;",
                    ],
                ],
                [
                    'attribute' => 'validBefore',
                    'contentOptions' => [
                        'style' => "width: 124px; word-wrap: break-word;",
                    ],
                    'headerOptions' => [
                        'style' => "width: 124px; word-wrap: break-word;",
                    ],
                    'value' => function($model) {
                        return DateHelper::formatDate($model->validBefore, 'date');
                    },
                ],
                [
                    'attribute' => 'terminalName',
                    'label' => Yii::t('app/cert', 'Recipient terminal'),
                    'value' => 'terminal.terminalId',
                    'filter' => true,
                    'contentOptions' => [
                        'style' => "width: 124px; word-wrap: break-word;",
                    ],
                    'headerOptions' => [
                        'style' => "width: 124px; word-wrap: break-word;",
                    ],
                ],
                [
                    'attribute' => 'senderTerminalAddress',
                    'contentOptions' => [
                        'style' => "width: 124px; word-wrap: break-word;",
                    ],
                    'headerOptions' => [
                        'style' => 'width: 124px; word-wrap: break-word',
                    ],
                ],
                [
                    'attribute'     => 'status',
                    'filter'		=> $cryptoproCertSearch->getActiveLabels(),
                    'format'        => 'html',
                    'value'         => function ($item, $params) {
                        return $item->getActiveLabel();
                    },
                    'contentOptions' => [
                        'style' => 'width: 69px; word-wrap: break-word',
                    ],
                    'headerOptions' => [
                        'style' => 'width: 69px; word-wrap: break-word',
                    ],
                ],
                [
                    'attribute' => '',
                    'format' => 'html',
                    'nowrap'  => true,
                    'value' => function ($item, $params) {
                        if (!empty($item->certData)) {
                            return Html::a('<i class="glyphicon glyphicon-eye-open">
                                        </i> ', Url::toRoute(['/cryptopro-certs-iso-20022/view', 'id' => $item->id]));
                        }

                        return '';
                    },
                    'contentOptions' => [
                        'style' => 'width: 43px; word-wrap: break-word',
                    ],
                    'headerOptions' => [
                        'style' => 'width: 43px; word-wrap: break-word',
                    ],
                ],
                [
                    'attribute' => '',
                    'format' => 'html',
                    'value' => function ($item, $params) {
                        // Если сертификат активен, то его нельзя редактировать
                        if ($item->status == CryptoproCert::STATUS_READY) {
                            return '';
                        } else {
                            return Html::a('<span class="glyphicon glyphicon-cog"></span>',
                                Url::toRoute(['/cryptopro-certs-iso-20022/update', 'id' => $item->id]));
                        }

                    },
                    'contentOptions' => [
                        'style' => 'width: 43px; word-wrap: break-word',
                    ],
                    'headerOptions' => [
                        'style' => 'width: 43px; word-wrap: break-word',
                    ],
                ],
                [
                    'class' => '\common\widgets\ActionColumn',
                    'template' => '{delete}',
                    'contentOptions' => [
                        'style' => 'width: 43px; word-wrap: break-word',
                    ],
                    'headerOptions' => [
                        'style' => 'width: 43px; word-wrap: break-word',
                    ],
                    'buttons'=>[
                        'delete' => function ($url, $model) {
                            // Если сертификат активен, то его нельзя удалить
                            if ($model->status == CryptoproCert::STATUS_READY) {
                                return '';
                            } else {
                                return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                    Url::toRoute(['/cryptopro-certs-iso-20022/delete', 'id' => $model->id]), [
                                        'title' => Yii::t('yii', 'Delete'),
                                        'aria-label' => Yii::t('yii', 'Delete'),
                                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                        'data-method' => 'post',
                                        'data-pjax' => '0'
                                    ]);
                            }
                        }
                    ]
                ]
            ],
        ]); ?>
    </div>
</div>