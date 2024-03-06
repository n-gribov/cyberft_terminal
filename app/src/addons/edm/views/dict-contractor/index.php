<?php

use addons\edm\EdmModule;
use addons\edm\models\DictContractorSearch;
use common\document\DocumentPermission;
use yii\data\ActiveDataProvider;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $searchModel DictContractorSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title                   = Yii::t('edm', 'Contractors Directory');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;

$userCanCreateDocuments = Yii::$app->user->can(
    DocumentPermission::CREATE,
    [
        'serviceId' => EdmModule::SERVICE_ID,
        'documentTypeGroup' => '*',
    ]
);
?>

<?php if ($userCanCreateDocuments) { ?>
    <p>
        <?=Html::a(Yii::t('app', 'Create'), ['create'], ['class' => 'btn btn-success'])?>
    </p>
<?php } ?>

<?php
    $gridOptions = [
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'rowOptions' => function ($model){
            $options['ondblclick'] = "window.location='".
                Url::toRoute(['view', 'id' => $model->id]) ."'";

            return $options;
        },
        'columns'      => [
            [
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'attribute' => 'id',
                'options' => [
                    'width' => 40,
                ],
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'filterInputOptions' => [
                    'maxLength' => 5,
                    'style'     => 'width:50px;float:right;'
                ],
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'attribute' => 'bank.name',
                'label' => Yii::t('edm', 'Bank name'),
            ],
            [
                'attribute' => 'terminalId',
                'filterInputOptions' => [
                    'maxLength' => 12,
                ],
            ],
            [
                'attribute' => 'kpp',
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'filterInputOptions' => [
                    'maxLength' => 9,
                    'style'     => 'float:right;'
                ],
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'attribute' => 'inn',
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'filterInputOptions' => [
                    'maxLength' => 12,
                    'style'     => 'float:right;'
                ],
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            [
                'attribute' => 'account',
                'filterInputOptions' => [
                    'maxLength' => 20,
                ],
            ],
            [
                'attribute' => 'bankBik',
                'headerOptions' => [
                    'class' => 'text-right',
                ],
                'filterInputOptions' => [
                    'maxLength' => 10,
                    'style'     => 'float:right;'
                ],
                'contentOptions' => [
                    'class' => 'text-right',
                ],
            ],
            'name',
            [
                'attribute' => 'role',
                'value' => function($item) {
                    return $item->getRoleLabel();
                },
            ],
            [
                'attribute' => 'type',
                'value' => function($item) {
                    return $item->getTypeLabel();
                },
            ],
        ],
    ];

    if ($userCanCreateDocuments) {
        $gridOptions['actions'] = '{view} {update} {delete}';
    } else {
        $gridOptions['actions'] = '{view}';
    }
?>

<?=GridView::widget($gridOptions);?>
