<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\CryptoproKey */

$this->title = Yii::t('app/fileact', 'Key #{id}', ['id'=>$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'FileAct'), 'url' => Url::toRoute(['/fileact'])];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
        <p>

            <?= Html::a(Yii::t('app/fileact', 'Download'), Url::toRoute(['/cryptopro-keys/download', 'id' => $model->id]),
                ['class' => 'btn btn-success']) ?>

            <?php
                // Кнопка удаления и изменения доступна только для неактивных сертификатов
                if (!$model->active) {

                    echo Html::a(Yii::t('app/fileact', 'Update'), ['update', 'id' => $model->id],
                        ['class' => 'btn btn-primary']) . "&nbsp";

                    echo Html::a(Yii::t('app/fileact', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app/fileact', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]);
                }
            ?>
        </p>

        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'keyId',
                'ownerName',
                'expireDate',
                'certData:ntext',
            ],
        ]) ?>
    </div>
</div>
