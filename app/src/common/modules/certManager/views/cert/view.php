<?php

use common\helpers\Countries;
use common\modules\certManager\models\Cert;
use yii\helpers\Html;
use yii\web\View;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this View */
/* @var $model Cert */

$this->title = $model->getCertId();
$this->params['breadcrumbs'][] = ['label' => Yii::t('app/cert', 'Certificates'), 'url' => ['index', 'role' => $model->role]];
$this->params['breadcrumbs'][] = $this->title;

$certData = $model->getCertificate();
?>
<?php if (!$model->isActive) : ?>
    <div class="alert alert-danger">
        <?=Yii::t('app/cert', 'Certificate expired')?>
    </div>
<?php endif ?>
<p>
<?= Html::a(Yii::t('app/cert', 'Back'), ['cert/index', 'role' => $model->role], ['class' => 'btn btn-default']) ?>
<?php

    $certModalButtonId = false;

    // Проверяем возможность текущего пользователя управлять статусом сертификата
    if (!$model->autoUpdate && Yii::$app->user->can('commonCertificatesStatusManagement')) {
        echo ' ' . Html::a(Yii::t('app/cert', 'Edit'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
        echo ' ' . Html::a(Yii::t('app/cert', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app/cert', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]);

        // Определяем текущий статус сертификата
        // Пустой статус считаем активым
        if ($model->status == Cert::STATUS_C10) {
            echo ' ' . Html::a('Заблокировать', ['#'], [
                'class' => 'btn btn-danger btn-cert-status',
                'data-toggle' => 'modal',
                'data-target' => '#statusModal'
            ]);
        } else if ($model->status == Cert::STATUS_C11 || $model->status == Cert::STATUS_C12) {
            $certModalButtonId = 'btnCertActivate';
            echo ' ' . Html::a('Активировать', ['#'], [
                'class' => 'btn btn-success btn-cert-status',
                'id' => $certModalButtonId,
            ]);
        }
    }
?>
</p>

<?php
    // Формируем наименование статуса с описанием к нему
    $status = $model->getStatusLabel();
    $statusDescription = $model->statusDescription ? ' (' . $model->statusDescription . ')' : '';
    $statusFullLabel = $status . $statusDescription;
?>

<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'validFrom',
        'validBefore',
        'useBefore',
        'participantId',
        'terminalId',
        'fingerprint',
        [
            'attribute' => 'status',
            'value' => $statusFullLabel
        ],
        [
            'attribute' => 'country',
            'value' => empty($certData) || !isset($certData->getSubject()['C']) ? '' : Countries::getName($certData->getSubject()['C'])
        ],
        'user',
        'owner',
        [
            'attribute' => 'fullName',
            'label' => Yii::t('app/cert', 'Owner Name'),
        ],
        'email:email',
        'phone',
        'post',
        'roleLabel',
        'signAccess',
    ],
]) ?>

<div class="panel-heading"><?=Yii::t('other', 'Certificate file')?></div>

    <?php if (!empty($certData)) : ?>
        <?= DetailView::widget([
            'model' => $certData,
            'attributes' => [
                'filePath',
                'fingerprint',
                'serialNumber',
                [
                    'attribute' => 'subjectName',
                    'value'     => nl2br(Html::encode(Cert::formatCertAttributes($certData->subjectName))),
                    'format'    => 'html',
                ],
                [
                    'attribute' => 'issuerName',
                    'value'     => nl2br(Html::encode(Cert::formatCertAttributes($certData->issuerName))),
                    'format'    => 'html',
                ],
                'validTo:datetime',
                'validFrom:datetime',
                'version',
                'body:pre'
            ],
        ]) ?>
    <?php else: ?>
        <div class="alert alert-dismissable alert-danger"><?=Yii::t('app/cert', 'Cannot find the file');?></div>
    <?php endif ?>

<div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Смена статуса</h4>
            </div>
            <form action="<?=Url::to(['/certManager/cert/toggle-cert-status'])?>" method="post">
                <div class="modal-body">
                    <textarea rows="5" class="form-control" name="changeReason" id="message-text" placeholder="Укажите причину смены статуса сертификата"></textarea>
                <?=Html::hiddenInput(\Yii::$app->getRequest()->csrfParam, \Yii::$app->getRequest()->getCsrfToken(), []);?>
                    <input type="hidden" name="certId" value="<?=$model->id?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn btn-primary">Поменять статус</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
    if ($certModalButtonId) {
        $this->registerJS(
<<<JS
            function deactivateCertModalConfirm() {
                $('#statusModal').modal('show');
            }

            function getModelRole() {
                return '$model->role';
            }
JS
        , yii\web\View::POS_READY);

        echo $this->render('_deactivateCertModal', ['model' => $model, 'buttonId' => $certModalButtonId]);
    }
?>