<?php

use addons\VTB\models\VTBCryptoproCert;
use common\models\CryptoproCert;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\DetailView;

/* @var $this View */
/* @var $model VTBCryptoproCert */

$this->title = Yii::t('app/iso20022', 'Certificate #{id}', ['id'=>$model->id]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/menu', 'VTB'), 'url' => Url::toRoute(['/VTB/documents'])];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/iso20022', 'Cryptopro certificates'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-xs-12">
    <p>
        <?= Html::a(Yii::t('app/iso20022', 'Download'), ['download', 'id' => $model->id], ['class' => 'btn btn-success']) ?>

        <?php

            // Если пользователь обладает возможностью управлять сертификатами,
            // выводим соответствующие кнопки
            if (Yii::$app->user->can('commonCertificatesStatusManagement')) {
                if ($model->status == CryptoproCert::STATUS_READY) {

                    // если сертификат активен, то заблокировать
                    // его может главный или дополнительный администратор
                    echo Html::a(
                        Yii::t('app/iso20022', 'Deactivate'),
                        ['change-cert-status', 'id' => $model->id, 'status' => CryptoproCert::STATUS_NOT_READY],
                        ['class' => 'btn btn-danger']
                    );
                } elseif ($model->status == CryptoproCert::STATUS_NOT_READY) {

                    // если сертификат заблокирован, то разблокировать
                    // его может только главный администратор
                    if (Yii::$app->user->can('admin')) {
                        echo Html::a(
                            Yii::t('app/iso20022', 'Activate'),
                            ['change-cert-status', 'id' => $model->id, 'status' => CryptoproCert::STATUS_READY],
                            ['class' => 'btn btn-success']
                        ) . '&nbsp';
                    }
                }
            }

            // Если сертификат активирован,
            // то невозможно его удалять/изменять
            if ($model->status != CryptoproCert::STATUS_READY) {
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

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'terminal.terminalId',
            'ownerName',
            'serialNumber',
            'keyId',
            'certData:ntext',
            'validBefore',
        ],
    ]) ?>

    </div>
</div>
