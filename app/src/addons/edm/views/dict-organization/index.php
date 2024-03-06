<?php

use addons\edm\models\DictBankSearch;
use yii\data\ActiveDataProvider;
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use common\models\Terminal;

/* @var $this View */
/* @var $searchModel DictBankSearch */
/* @var $dataProvider ActiveDataProvider */

$this->title = Yii::t('app/menu', 'Organizations Directory');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;

$this->beginBlock('pageAdditional');
echo Html::a(Yii::t('edm', 'Add organization', [
    'modelClass' => 'DictOrganization',
]),
    ['create'],
    ['class' => 'btn btn-success'],
    'ic-plus');
$this->endBlock('pageAdditional');

?>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'rowOptions' => function ($model) {
        $options['ondblclick'] = "window.location='". Url::to(['/edm/dict-organization/view', 'id' => $model->id]) . "'";
        return $options;
    },
    'columns'      => [
        [
            'class' => 'yii\grid\SerialColumn',
            'headerOptions' => [
                'style' => 'width: 20px;',
            ],
        ],
        [
            'attribute' => 'name',
            'headerOptions' => [
                'style' => "width: 500px;",
            ],
        ],
        [
            'attribute'     => 'type',
            'filter'		 => $searchModel->typeValues(),
            'format'        => 'html',
            'value'         => function ($item, $params) {
                return $item->getTypeLabel();
            },
            'headerOptions' => [
                'style' => "width: 200px;",
            ],
        ],
        [
            'attribute' => 'inn',
            'headerOptions' => [
                'style' => "width: 220px;",
            ],
        ],
        [
            'attribute' => 'kpp',
            'headerOptions' => [
                'style' => "width: 220px;",
            ],
        ],
        [
            'attribute' => 'terminalName',
            'label' => Yii::t('app/terminal', 'Terminal ID'),
            'filter' => Terminal::getList('id', 'terminalId'),
            'value' => 'terminal.terminalId',
            'headerOptions' => [
                'style' => "width: 200px;",
            ],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'contentOptions' => [
                'style' => 'min-width: 125px;'
            ]
        ]
    ],
]);
?>
