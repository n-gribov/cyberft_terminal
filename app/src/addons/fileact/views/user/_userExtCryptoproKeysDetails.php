<?php
use common\models\CryptoproKeySearch;
use yii\helpers\Html;
use yii\helpers\Url;
use common\widgets\GridView;

$searchModel = new CryptoproKeySearch();
$dataProvider = $searchModel->searchUserKeys(Yii::$app->user->id);
?>

<div class="panel panel-gray">
    <div class="panel-heading">
        <h4><?= Yii::t('app/fileact', 'Available keys') ?></h4>
    </div>
    <div class="panel-body">
        <?= GridView::widget([
            'summary' => '',
            'dataProvider' => $dataProvider,
            'columns' => [
                'keyId',
                [
                    'attribute'     => 'status',
                    'format'        => 'html',
                    'value'         => function ($item, $params) {
                        return $item->getStatusLabel();
                    }
                ],
                [
                    'attribute'     => '',
                    'format'        => 'html',
                    'value'         => function ($item, $params) {
                        return ($item->active
                            ? Yii::t('app/fileact', 'Enabled')
                            : Yii::t('app/fileact', 'Disabled'));
                    }
                ],
                [
                    'attribute' => '',
                    'format' => 'html',
                    'value'	=> function ($item, $params) {
                        return Html::a('<span class="glyphicon glyphicon-cog"></span>', Url::toRoute(['/cryptopro-keys/update', 'id' => $item->id]));
                    }
                ],
            ],
        ]); ?>
    </div>
</div>
