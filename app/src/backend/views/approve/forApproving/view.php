<?php

use common\models\form\CommandRejectForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model CommandAR */

$this->title = $model->code;
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app/menu', 'For approving'),
    'url'   => ['for-approving']
];
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
<?php
    echo Html::a(Yii::t('app', 'Accept'), ['accept', 'id' => $model->id],
        ['class' => 'btn btn-primary']);

    $rejectModel = new CommandRejectForm();

    // Вывести форму отказа
    echo $this->render('_rejectForm', [
        'rejectModel' => $rejectModel,
        'commandId'   => $model->id,
    ]);
?>
</p>
<?php
// Создать детализированное представление
echo DetailView::widget([
    'model'      => $model,
    'attributes' => [
        'id',
        'code',
        'entity',
        'entityId',
        'dateCreate:datetime',
    ],
]);
