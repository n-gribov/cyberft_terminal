<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model addons\swiftfin\models\SwiftfinTemplate */

$this->title = Yii::t('doc', 'Document Template').' "'.$model->title.'"';
$this->params['breadcrumbs'][] = ['label' => Yii::t('doc', 'Document Templates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<p>
    <?= Html::a(Yii::t('app', 'Create document'), ['create-swiftfin', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
    <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
            'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
            'method' => 'post',
        ],
    ]) ?>
    <?= Html::a(Yii::t('doc', 'Go to template registry'), Url::toRoute('index'), ['class' => 'btn']) ?>
</p>

<?php
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'title',
        'docType',
        'sender',
        'recipient',
        'terminalCode',
        'bankPriority',
        'text:ntext',
    ],
]);
