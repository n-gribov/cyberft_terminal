<?php
use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\GridView;

$this->title = Yii::t('app/menu', 'Documents for modification');
$this->params['breadcrumbs'][] = ['label' => Yii::t('edm', 'Banking registry'), 'url' => Url::toRoute(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php echo $this->render('_search', [
    'model' => $filterModel,
    'filterStatus' => $filterStatus,
]); ?>
<?php
$myGridWidget = GridView::begin([
    'emptyText'    => Yii::t('other', 'No documents matched your query'),
    'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
    'dataProvider' => $dataProvider,
    'filterModel'  => $filterModel,
    'rowOptions' => function ($model, $key, $index, $grid) {
        $options['ondblclick'] = "window.location='". Url::toRoute(['/edm/wizard/edit', 'id'=>$model->id]) ."'";

        if (in_array($model->status, array_merge($model->getErrorStatus(),['']))) {
            $options['class'] = 'bg-alert-danger';
        } elseif (in_array($model->status, $model->getProcessingStatus())) {
            $options['class'] = 'bg-alert-warning';
        }

        return $options;
    },
    'columns'      => [
        [
            'attribute' => 'id',
            'format' => 'html',
            'headerOptions' => [
                'class' => 'text-right',
            ],
            'filterInputOptions' => [
                'style'     => 'float:right;width: 30px'
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
        ],
        [
            'attribute' => 'type',
            'filter' =>  $filterModel->getDocTypeLabels(),
            'format' => 'html',
            'enableSorting' => false,
            'value' => function ($item, $params) {
                return "<span title=\"{$item->type}\">{$item->getDocTypeLabel()}</span>";
            },
            'filterInputOptions' => [
                'style'     => 'width: 160px',
                'class' => 'form-control',
            ],
        ],
        [
            'attribute' => 'dateCreate',
            'filter' => false,
            'headerOptions' => [
                'class' => 'text-right',
            ],
            'contentOptions' => [
                'class' => 'text-right',
            ],
        ],
        [
            'attribute' => 'sender',
            'filterInputOptions' => [
                'maxLength' => 12,
                'style'     => 'width: 120px',
            ],
        ],
        [
            'attribute' => 'receiver',
            'filterInputOptions' => [
                'maxLength' => 12,
                'style'     => 'width: 120px',
            ],
        ],
        [
            'attribute' => 'correctionReason',
            'filter' => false,
        ],
        [
            'attribute' => '',
            'format' => 'html',
            'filterInputOptions' => [
                'style'     => 'width: 20px'
            ],
            'value'	=> function ($item, $params) {
                return Html::a('<span class="glyphicon glyphicon-eye-open"></span>',
                        Url::toRoute(['/edm/wizard/edit', 'id' => $item->id]));
            }
        ],

    ],
]);

$myGridWidget->formatter->nullDisplay = '';
$myGridWidget->end();
?>
