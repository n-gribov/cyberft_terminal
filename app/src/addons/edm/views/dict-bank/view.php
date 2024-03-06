<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model addons\edm\models\DictBank */

$this->title					 = $model->name;
$this->params['breadcrumbs'][]	 = ['label' => Yii::t('edm', 'Banks Directory'), 'url' => ['index']];
$this->params['breadcrumbs'][]	 = $this->title;

// Создать детализированное представление
echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        'bik',
        'account',
        'name',
        'city',
        'terminalId',
    ],
]);

echo Html::a(Yii::t('app', 'Back'), Yii::$app->request->getReferrer(),
    ['class' => 'btn btn-primary']);

