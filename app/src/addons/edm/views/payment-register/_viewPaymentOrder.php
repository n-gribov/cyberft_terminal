<?php

use addons\edm\EdmModule;
use addons\edm\models\EdmDocumentTypeGroup;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use addons\edm\widgets\CancelVtbDocumentButton;
use common\document\Document;
use common\document\DocumentPermission;
use common\widgets\FastPrint\FastPrint;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\ActiveForm;

/** @var \addons\edm\models\PaymentRegister\PaymentRegisterPaymentOrder $model */

$this->title = Yii::t('edm', 'View payment order #{id}', ['id' => $model->id]);

$from = Yii::$app->request->get('from');
if ($from === 'viewPaymentRegister' && !empty($model->registerId)) {
    $backUrl = Url::toRoute(['/edm/payment-register/view', 'id' => $model->registerId]);
} else if ($from === 'controller-verification' && !empty($model->registerId)) {
    $paymentRegisterExtModel = PaymentRegisterDocumentExt::findOne(['registerId' => $model->registerId]);
    if (!is_null($paymentRegisterExtModel)) {
        $backUrl = Url::toRoute(['/document/view', 'id' => $paymentRegisterExtModel->documentId, 'from' => 'controller-verification']);
        $this->params['breadcrumbs'][] = [
            'label' => Yii::t('edm', 'View document #{id}', ['id' => $paymentRegisterExtModel->documentId]),
            'url' => $backUrl
        ];
    } else {
        $backUrl = Url::toRoute(['/edm/payment-register/view', 'id' => $model->registerId]);
        $this->params['breadcrumbs'][] = [
            'label' => Yii::t('edm', 'View payment register #{id}', ['id' => $model->registerId]),
            'url' => $backUrl
        ];

    }
} else {
    $backUrl = Url::toRoute(['/edm/payment-register/payment-order']);
}

if ($from === 'wizard') {
    $js = <<<JS
        $('#create-and-sign-payment-register-button').click(function(e) {
            $('#create-payment-register-form').trigger('submit');
        });
    JS;
    $this->registerJs($js, View::POS_READY);

    ActiveForm::begin([
        'action'  => Url::toRoute('create', ['method' => 'post']),
        'method'  => 'post',
        'options' => [
            'id'    => 'create-payment-register-form',
        ]
    ]);
    echo Html::hiddenInput('paymentOrdersIds[]', $model->id);
    echo Html::hiddenInput('from', 'wizard');
    ActiveForm::end();
}
?>

<style>
    #action-buttons-group {
        padding-bottom: 1em;
    }
    #action-buttons-group .btn {
        height: 32px;
        margin-right: 5px;
    }
    #action-buttons-group>.btn:last-of-type {
        border-radius: 0 3px 3px 0;
    }
</style>

<div id="action-buttons-group" class="btn-group">
    <a class="btn btn-default" href="<?= $backUrl ?>"><?= Yii::t('app', 'Back') ?></a>
    <?php
    if ($from === 'wizard') {
        echo Html::button(
            Yii::t('doc', 'Sign and send'),
            [
                'id'    => 'create-and-sign-payment-register-button',
                'class' => 'btn btn-default',
            ]
        );
        echo Html::a(
            Yii::t('edm', 'Create one more payment order'),
            Url::toRoute(['/edm/wizard/step2', 'type' => 'PaymentOrder']),
            [
                'class' => 'btn btn-default'
            ]
        );
    }
    ?>
    <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?= Yii::t('app', $from === 'wizard' ? 'Other actions' : 'Actions') ?> <span class="caret"></span>
        </button>
        <ul class="dropdown-menu">
            <li><?= Html::a(Yii::t('app', 'Print'),
                    Url::toRoute(['/edm/documents/print', 'id' => $model->id, 'flagPaymentRegisterPaymentOrder' => 1]), ['target' => '_blank', 'class' => 'print-link']) ?>
            </li>
            <li><?= Html::a(Yii::t('app', 'Export as {format}', ['format' => 'Excel']),
                    Url::toRoute(['/edm/export/export-paymentregister-paymentorder', 'id' => $model->id, 'exportType' => 'excel',])) ?>
            </li>
            <li><?= Html::a(Yii::t('app', 'Export as {format}', ['format' => '1C']),
                    Url::toRoute(['/edm/export/export-paymentregister-paymentorder', 'id' => $model->id, 'exportType' => '1c'])) ?>
            </li>
            <?php if ($model->status != Document::STATUS_DELETED
                && !$model->registerId
                && Yii::$app->user->can(DocumentPermission::CREATE, ['serviceId' => EdmModule::SERVICE_ID, 'documentTypeGroup' => EdmDocumentTypeGroup::RUBLE_PAYMENT])
            ) : ?>
                <li><?= Html::a(Yii::t('app', 'Edit document'),
                        Url::toRoute(['/edm/wizard/step2', 'id' => $model->id, 'type' => 'PaymentOrder'])) ?>
                </li>
            <?php endif ?>
        </ul>
    </div>
    <?= CancelVtbDocumentButton::widget([
        'document' => $model->paymentRegister,
        'documentNumber' => $model->number,
        'documentDate' => $model->date,
    ]) ?>
</div>

<?php
// Вывести страницу
echo $this->render('readable/paymentOrder', ['model' => $model]);

$printUrl = Url::toRoute(['/edm/documents/print', 'id' => $model->id, 'flagPaymentRegisterPaymentOrder' => 1]);
$printBtn = '.print-link';
$documentId = $model->id;
$documentType = \addons\edm\models\PaymentOrder\PaymentOrderType::TYPE;

echo FastPrint::widget([
    'printUrl' => $printUrl,
    'printBtn' => $printBtn,
    'documentId' => $documentId,
    'documentType' => $documentType
]);
