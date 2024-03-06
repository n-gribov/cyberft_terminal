<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

?>
<div class="row">
    <div class="col-xs-12">
        <p>
            <?= Html::a(Yii::t('app/iso20022', 'Download'), ['download', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <?php
            // Если пользователь может управлять сертификатами, выводим соответствующие кнопки
            if (Yii::$app->user->can('commonCertificatesStatusManagement')) {
                if ($model->status == $model::STATUS_READY) {

                    // если сертификат активен, то заблокировать его
                    // может главный или дополнительный администратор
                    echo Html::a(
                        Yii::t('app/iso20022', 'Deactivate'),
                        ['change-cert-status', 'id' => $model->id, 'status' => $model::STATUS_NOT_READY],
                        ['class' => 'btn btn-danger']
                    );
                } else if ($model->status == $model::STATUS_NOT_READY) {

                    // если сертификат заблокирован, то разблокировать
                    // его может только главный администратор
                    if (Yii::$app->user->can('admin')) {
                        echo Html::a(
                                Yii::t('app/iso20022', 'Activate'),
                                ['change-cert-status', 'id' => $model->id, 'status' => $model::STATUS_READY],
                                ['class' => 'btn btn-success']
                            ) . '&nbsp';
                    }
                }
            }

            // Если сертификат активирован, то его нельзя удалять/изменять
            if ($model->status != $model::STATUS_READY) {
                echo Html::a(Yii::t('app/iso20022', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) . '&nbsp';
                echo Html::a(Yii::t('app/iso20022', 'Delete'), ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => Yii::t('app/iso20022', 'Are you sure you want to delete this item?'),
                            'method' => 'post',
                        ],
                    ]) . '&nbsp';
            }
            ?>
        </p>

        <?php
        // Создать детализированное представление
        echo DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'terminal.terminalId',
                'senderTerminalAddress',
                'ownerName',
                'serialNumber',
                'keyId',
                'certData:ntext',
                'validBefore',
            ],
        ]) ?>

    </div>
</div>