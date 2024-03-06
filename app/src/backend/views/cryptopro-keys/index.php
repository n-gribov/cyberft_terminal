<?php
use common\widgets\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\CryptoproKeySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app/fileact', 'Cryptopro keys');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">

        <?php

            // Регулируем отображение кнопок изменения и
            // удаления в завимости от активности терминала

            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    'keyId',
                    'ownerName',
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'visibleButtons' => [
                            'view' => function ($model, $key, $index) {
                                return true;
                            },
                            'update' => function ($model, $key, $index) {
                                return !$model->active;
                            },
                            'delete' => function ($model, $key, $index) {
                                return !$model->active;
                            }
                        ]
                    ]
                ],
            ]);

        ?>

    </div>
</div>
