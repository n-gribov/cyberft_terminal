<?php
use yii\helpers\Url;
use common\widgets\GridView;
use common\document\Document;
use yii\helpers\Html;

$this->title = Yii::t('app/menu', 'Payment orders');

$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking'), 'url' => Url::toRoute(['/edm'])];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php echo $this->render('_search', [
    'model' => $model,
    'filterStatus' => $filterStatus,
]); ?>

<?php
$urlParams['from'] = 'paymentOrder';

$myGridWidget = GridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $model,
    'actions' => '{view}',
    'rowOptions' => function ($model) use ($urlParams) {
        $options['ondblclick'] = "window.location='".
                Url::toRoute(array_merge(['view', 'id' => $model->id], $urlParams)) ."'";

        if (in_array($model->status, array_merge($model->getErrorStatus(),['']))) {
            $options['class'] = 'bg-alert-danger';
        } elseif (in_array($model->status, $model->getProcessingStatus())) {
            $options['class'] = 'bg-alert-warning';
        }
        return $options;
    },
    'columns'      => [
        [
            'attribute'          => 'id',
            'filterInputOptions' => [
                'maxLength' => 10,
                'style'     => 'width: 40px'
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
        ],
        [
            'attribute' => 'direction',
            'format' => 'html',
            'filter' => Document::getDirectionLabels(),
            'enableSorting' => true,
            'value' => function ($item, $params) {
                return "<span title=\"{$item->direction}\">" . Document::directionLabel($item->direction) . "</span>";
            },
            'filterInputOptions' => [
                'style' => 'width: 80px',
                'class' => 'form-control',
            ],
        ],
        [
            'attribute'     => 'status',
            'format'        => 'html',
            'filter'		=> $model->getStatusLabels(),
            'filterInputOptions' => [
                'style' => 'width: 100px',
                'class' => 'form-control',
            ],
            'value'         => function ($item, $params) {
                return "<span title=\"Status: {$item->status}\">{$item->getStatusLabel()}</span>";
            }
        ],
        [
            'attribute' => 'payerName',
            'value' => 'documentExtEdmPaymentOrder.payerName',
            'filter' => true,
            'filterInputOptions' => [
                'style' => 'width: 100%'
            ],
        ],
        [
            'attribute' => 'beneficiaryName',
            'value' => 'documentExtEdmPaymentOrder.beneficiaryName',
            'filterInputOptions' => [
                'style' => 'width: 100%'
            ],
        ],
        [
            'attribute'          => 'number',
            'value'          => 'documentExtEdmPaymentOrder.number',
            'filterInputOptions' => [
                'maxLength' => 10,
                'style'     => 'width: 50px'
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
        ],
        [
            'attribute'          => 'sum',
            'value'          => 'documentExtEdmPaymentOrder.sum',
            'format'        => 'decimal',
            'filterInputOptions' => [
                'maxLength' => 10,
                'style'     => 'width: 60px'
            ],
            'contentOptions' => [
                'class' => 'text-right',
                'nowrap' => 'nowrap'
            ],
            'value' => function($model) {
                return Yii::$app->formatter->asDecimal($model->sum, 2);
            }
        ],
        [
            'attribute'          => 'payerAccount',
            'value'          => 'documentExtEdmPaymentOrder.payerAccount',
            'filterInputOptions' => [
                'style'     => 'width: 140px'
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
        ],
        [
            'attribute'          => 'currency',
            'value'          => 'documentExtEdmPaymentOrder.currency',
            'filterInputOptions' => [
                'style'     => 'width: 40px'
            ],
        ],
        [
            'attribute'          => 'paymentPurpose',
            'value'          => 'documentExtEdmPaymentOrder.paymentPurpose',
            'filterInputOptions' => [
                'style'     => 'width: 100%'
            ],
        ],

        [
            'attribute'          => 'dateCreate',
            'filterInputOptions' => [
                'maxLength' => 64,
            ],
            'filter'             => false
        ]
    ],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();
?>
