<?php

use addons\swiftfin\models\DictBankSearch;
use common\models\User;
use kartik\form\ActiveForm;
use yii\data\ActiveDataProvider;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DictBankSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app/menu', 'SWIFT code directory');
$this->params['breadcrumbs'][] = $this->title;

// Получить роль пользователя из активной сессии
 if (Yii::$app->user->identity->role == User::ROLE_ADMIN) : ?>
    <div class="panel-body">
		<?php $form = ActiveForm::begin([
			'enableClientValidation' => false,
			'enableAjaxValidation'   => false,
			'method' => 'post',
			'action' => Url::to(['upload']),
			'options' => [
				'enctype'=>'multipart/form-data'
			],
			'formConfig'             => [
				'labelSpan'  => 3,
				'deviceSize' => ActiveForm::SIZE_TINY,
				'showErrors' => true,
			]
		]) ?>
		<table class="table" style="margin: 0">
			<thead>
            <td width="20%">
                <?php if (!empty($lastUpload)) :
                    $event = $lastUpload->getEvent();
                ?>
                    Последняя загрузка: <?=date('d.m.Y H:i:s', $lastUpload->dateCreated)?><br/>
                    Файл <?=$event->fileName?>, тип <?=$event->type?>, <?=$event->fileSize?> байт<br/>
                    Загружен пользователем <?=Html::a(
                        ($event->user->name ? $event->user->name : $event->user->email),
                        Url::to(['/user/view', 'id' => $event->entityId])
                    )?><br/>
                    Статус: <?=$event->getStatusLabel()?><br/>
                    Обновлено <?=$event->changedCount?> записей из <?=$event->recordCount?>
                <?php else : ?>
                    Нет данных о последней загрузке
                <?php endif ?>
            </td>
				<td width="10%"><?=Html::label(Yii::t('app', 'Choose file for upload'), 'file')?></td>
				<td width="70%"<?=isset($errors['file']) ? ' class="has-error"' : null ?>>
                    <?=kartik\file\FileInput::widget([
                        'name'  => 'file',
                        'pluginOptions' => [
                            'showPreview' => false
                        ]
                    ]) ?>
					<?php if (!empty($errors)) : ?>
						<div class="help-block">
							<?=implode('<br/>', (array)$errors['file']) ?>
						</div>
					<?php endif ?>
				</td>
				<td></td>
			</thead>
		</table>
		<?php $form->end() ?>
	</div>
<?php endif ?>
<?php
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'swiftCode',
            'filterInputOptions' => [
                'maxLength' => 9,
                'style' => 'width: 100%'
            ],
        ],
        [
            'attribute' => 'branchCode',
            'filterInputOptions' => [
                'maxLength' => 20,
                'style' => 'width: 100%'
            ],
        ],
        [
            'attribute' => 'name',
            'filterInputOptions' => [
                'maxLength' => 255,
                'style' => 'width: 100%'
            ],
        ],
//				[
//					'attribute' => 'address',
//					'filterInputOptions' => [
//						'maxLength' => 20,
//						'style' => 'width: 100%'
//					],
//				],
//				'terminalId',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view}'
        ],
    ],
]);?>


