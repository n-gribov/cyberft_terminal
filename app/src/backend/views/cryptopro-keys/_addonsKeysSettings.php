<?php

use common\widgets\GridView;
use common\models\Terminal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

?>

<div class="row" style="margin-top: 15px;">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-2">
                <?=Html::a(Yii::t('app/settings', 'Add key'), '#',
                    [
                        'class' => 'btn btn-primary btn-add-key',
                        'data' => [
                            'toggle' => 'modal',
                            'target' => '#modalCryptoProCertUpload'
                        ]
                    ]
                );
                ?>
            </div>
        </div>

        <?php
        // Создать таблицу для вывода
        echo GridView::widget([
            'summary' => '',
            'dataProvider' => $cryptoproKeys,
            'filterModel' => $cryptoproKeysSearch,
            'rowOptions'	=> function($model, $key, $index, $grid) {

                // Выделение строк сертификатов по условиям

                if ($model->expireDate == '0000-00-00 00:00:00') {
                    // Если дата истечения сертификата не указана, это ошибка
                    return ['class'=>'danger'];
                } else {
                    // Если дата истечения просрочена, это ошибка
                    $now = strtotime(date('c'));
                    $expirationDate = strtotime($model->expireDate);

                    if ($expirationDate < $now) {
                        return ['class'=>'danger'];
                    }

                    // Если до просрочки даты истечения осталось менее 30 дней,
                    // это предупреждение
                    $datetimeNow = new DateTime(date('Y-m-d H:i:s'));
                    $datetimeExpiration = new DateTime($model->expireDate);
                    $interval = $datetimeNow->diff($datetimeExpiration);
                    if ($interval->days <= 30) {
                        return ['style'=>'background-color: #FFCC33'];
                    }
                }
            },
            'columns' => [
                [
                    'attribute' => 'id',
                    'filter' => false
                ],
                [
                    'attribute' => 'ownerName',
                    'filter' => true,
                    'contentOptions' => [
                        'style' => "width: 100px; word-wrap: break-word;",
                    ],
                ],
                [
                    'attribute' => 'keyId',
                    'filter' => true,
                    'value' => function($model) {
                        return strtoupper($model->keyId);
                    }
                ],
                [
                    'attribute' => 'serialNumber',
                    'filter' => true,
                ],
                [
                    'attribute' => 'expireDate',
                    'format' => 'date',
                    'filter' => kartik\widgets\DatePicker::widget([
                        'model' => $cryptoproKeysSearch,
                        'attribute' => 'expireDate',
                        'type' => \kartik\widgets\DatePicker::TYPE_INPUT,
                        'pluginOptions' => [
                            'autoclose' => true,
                            'format' => 'yyyy-mm-dd', // 'dd.mm.yy',
                            'todayHighlight' => true,
                            'orientation' => 'bottom'
                        ],
                        'options' => [
                            'class' => 'form-control'
                        ],
                    ])
                ],
                [
                    'attribute' => 'terminal',
                    'format' => 'html',
                    'filter' => Terminal::getList('id', 'terminalId'),
                    'label' => Yii::t('app/fileact', 'Terminals'),
                    'contentOptions' => [
                        'style' => "max-width: 150px; word-wrap: break-word;",
                    ],
                    'value' => function($item) {
                        // Получение результатов в виде массива
                        $terminals = $item->getTerminals()->select('terminalId')->asArray()->all();

                        // Преобразование в более удобный массив для конвертации в строку
                        $terminals = ArrayHelper::getColumn($terminals, 'terminalId');

                        return implode(", ", $terminals);
                    }
                ],
                [
                    'attribute' => 'beneficiary',
                    'format' => 'html',
                    'filter' => Terminal::getList('id', 'terminalId'),
                    'label' => Yii::t('app/fileact', 'Beneficiaries'),
                    'contentOptions' => [
                        'style' => "max-width: 150px; word-wrap: break-word;",
                    ],
                    'value' => function($item) {
                        // Получение результатов в виде массива
                        $terminals = $item->getBeneficiaries();
                        // Преобразование в более удобный массив для конвертации в строку
                        $terminals = ArrayHelper::getColumn($terminals, 'terminalId');

			if (empty($terminals)) {
				return 'Любой';
			}

                        return implode(', ', $terminals);
                    }
                ],
                [
                    'attribute' => 'email',
                    'value' => 'user.email',
                    'label' => Yii::t('app/user', 'User'),
                    'filter' => true
                ],
                [
                    'attribute' => 'status',
                    'filter' => $cryptoproKeysSearch->getActiveLabels(),
                    'format' => 'html',
                    'value' => function ($item, $params) {
                        return $item->getActiveLabel();
                    }
                ],
                [
                    'attribute' => '',
                    'format' => 'html',
                    'nowrap'  => true,
                    'value' => function ($item, $params) {
                        if (!empty($item->certData)) {
                            return Html::a('<i class="glyphicon glyphicon-download"></i> ', Url::toRoute(['/cryptopro-keys/download', 'id' => $item->id]));
                        }

                        return '';
                    }
                ],
                [
                    'attribute' => '',
                    'format' => 'html',
                    'value' => function ($item, $params) {

                        // Если сертификат активен, то его нельзя редактировать
                        if ($item->active) {
                            return '';
                        } else {
                            return Html::a('<span class="glyphicon glyphicon-cog"></span>',
                                Url::toRoute(['/cryptopro-keys/update', 'id' => $item->id]));
                        }

                    }
                ],
                [
                    'attribute' => '',
                    'format' => 'raw',
                    'value' => function ($item, $params) {
                        // Если сертификат активен, то его нельзя удалить
                        if ($item->active) {
                            return '';
                        } else {
                            return Html::a('<span class="glyphicon glyphicon-trash"></span>',
                                Url::toRoute(['/cryptopro-keys/delete', 'id' => $item->id]), [
                                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                                    'data-method' => 'post'
                                ]);
                        }

                    }
                ]
            ],
        ]);
        ?>
    </div>
</div>