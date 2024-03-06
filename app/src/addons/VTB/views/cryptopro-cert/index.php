<?php

use yii\helpers\Html;
use common\widgets\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel addons\VTB\models\VTBCryptoproCertSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/iso20022', 'Cryptopro certificates');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'VTB'), 'url' => Url::toRoute(['/VTB/documents'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-xs-12">
    <p>
        <?= Html::a(Yii::t('app/iso20022', 'Create certificate'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<?php
    // Создать таблицу для вывода
    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'terminalId',
            [
                'attribute'     => 'status',
                'format'        => 'html',
                'value'         => function ($item, $params) {
                    return $item->getStatusLabel();
                }
            ],
            'keyId',
            'ownerName',
            'validBefore',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]);
?>
    </div>
</div>
