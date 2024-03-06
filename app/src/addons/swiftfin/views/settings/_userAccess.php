<?php
use common\widgets\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="row">
    <div class="col-sm-6">
        <?php
        $myGridWidget = GridView::begin([
            'emptyText'    => Yii::t('other', 'No enabled users found'),
            'summary'      => Yii::t('other', 'Shown from {begin} to {end} out of {totalCount} found'),
            'dataProvider' => $dataProvider,
            'columns' => [
                'user.name',
                'user.email',
                'roleLabel',
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{view}',
                    'buttons'  => [
                        'view' => function ($url, $model, $key) {
                            return Html::a(
                                '<span class="glyphicon glyphicon-pencil"></span>',
                                Url::toRoute(['/swiftfin/user-ext', 'id' => $model->userId])
                            );
                        }
                    ],
                ],
            ],
        ]);
        $myGridWidget->end();
        ?>
	</div>
</div>


