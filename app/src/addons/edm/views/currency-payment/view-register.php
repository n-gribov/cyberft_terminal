<?php

use addons\edm\EdmModule;
use addons\edm\models\CurrencyPayment\CurrencyPaymentSearch;
use addons\edm\models\CurrencyPaymentRegister\CurrencyPaymentRegisterDocumentExt;
use addons\edm\models\DictOrganization;
use addons\edm\models\EdmPayerAccount;
use addons\edm\models\PaymentRegister\PaymentRegisterDocumentExt;
use addons\edm\models\VTBRegisterCur\VTBRegisterCurType;
use common\document\Document;
use common\modules\certManager\models\Cert;
use common\modules\monitor\models\MonitorLogAR;
use common\widgets\documents\SignDocumentsButton;
use common\widgets\GridView;
use yii\data\ArrayDataProvider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\i18n\Formatter;
use yii\web\View;
use yii\widgets\DetailView;

/** @var View $this */
/** @var Document $document */
/** @var ArrayDataProvider $paymentsDataProvider */
/** @var MonitorLogAR|null $signingRejectionEvent */

$this->title = Yii::t('edm', 'Foreign currency payment register');

/** @var CurrencyPaymentRegisterDocumentExt $extModel */
$extModel = $document->extModel;

$organizationsCache = [];
$getOrganizationByAccountNumber = function (?string $accountNumber) use ($organizationsCache): ?DictOrganization {
    if (empty($accountNumber)) {
        return null;
    }
    if (!array_key_exists($accountNumber, $organizationsCache)) {
        $account = EdmPayerAccount::findOne(['number' => $accountNumber]);
        $organizationsCache[$accountNumber] = $account && $account->edmDictOrganization ? $account->edmDictOrganization : null;
    }
    return $organizationsCache[$accountNumber];
};
$documentPayerOrganization = $getOrganizationByAccountNumber($extModel->debitAccount);
$documentAccount = EdmPayerAccount::findOne(['number' => $extModel->debitAccount]);
$isRealRegister = $extModel instanceof CurrencyPaymentRegisterDocumentExt;

?>

<?php if ($signingRejectionEvent) : ?>
    <div class="alert alert-warning">
        <p><?= $signingRejectionEvent->label ?></p>
        <?= Html::encode($signingRejectionEvent->reason) ?>
    </div>
<?php endif ?>

<div id="buttons-block">
<?php
    echo Html::a(
        Yii::t('app','Back'),
        Yii::$app->request->get('backUrl', Url::to(['/edm/currency-payment/register-index'])),
        ['class' => 'btn btn-primary']
    );
    if ($document->isSignableByUserLevel(EdmModule::SERVICE_ID)) {
        echo SignDocumentsButton::widget([
            'buttonText' => Yii::t('app/message', 'Signing'),
            'documentsIds' => [$document->id],
        ]);
        if ($document->type === VTBRegisterCurType::TYPE) {
            // Вывести страницу
            echo $this->render('@addons/edm/views/payment-register/_rejectSigning', ['id' => $document->id]);
        }
    }
?>
</div>

<div class="row">
    <div class="col-xs-4">
        <?php
        // Создать детализированное представление
        echo DetailView::widget([
            'model' => $extModel,
            'template' => '<tr><th width="50%">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'attribute' => 'document.id',
                    'label' => 'ID',
                ],
                [
                    'value' => $documentPayerOrganization ? $documentPayerOrganization->name : null,
                    'label' => Yii::t('edm', 'Payer'),
                ],
                [
                    'attribute' => 'debitAccount',
                    'label' => Yii::t('edm', 'Debiting account'),
                ],
                [
                    'value' => @$documentAccount->edmDictCurrencies->name,
                    'label' => Yii::t('edm', 'Account currency'),
                ],
                [
                    'value' => @$documentAccount->bank->name,
                    'label' => Yii::t('edm', 'Bank'),
                ],
                [
                    'attribute' => 'document.dateCreate',
                    'label' => Yii::t('app/message', 'Registry Time'),
                ],
            ]
        ]);
        ?>
    </div>
    <div class="col-xs-4">
        <?php
        // Создать детализированное представление
        echo DetailView::widget([
            'model' => $extModel,
            'template' => '<tr><th width="50%">{label}</th><td>{value}</td></tr>',
            'attributes' => [
                [
                    'label' => Yii::t('edm', 'Payment orders in the registry'),
                    'value' => $isRealRegister ? $extModel->paymentsCount : 1,
                ],
                [
                    'attribute' => 'document.signaturesRequired',
                    'label' => Yii::t('document', 'Signatures required'),
                ],
                [
                    'attribute' => 'document.signaturesCount',
                    'label' => Yii::t('document', 'Signatures count'),
                ],
                [
                    'attribute' => 'document.status',
                    'label' => Yii::t('app/message', 'Status'),
                    'value' => $document->getStatusLabel()
                ],
                [
                    'attribute' => 'businessStatusTranslation',
                    'label' => Yii::t('document','Execution status'),
                ],
                [
                    'attribute' => 'businessStatusDescription',
                    'label' => Yii::t('document', 'Status description'),
                ]
            ]
        ]);
        ?>
    </div>
</div>
<?php
// Создать таблицу для вывода
echo GridView::widget([
    'dataProvider' => $paymentsDataProvider,
    'id' => 'payments-grid',
    'formatter' => [
        'class' => Formatter::class,
        'decimalSeparator' => ',',
        'thousandSeparator' => '',
        'nullDisplay' => '',
        'dateFormat' => 'dd.MM.Y',
    ],
    'rowConfig' => [
        'attrColor' => 'businessStatus',
        'map'   => [
            'RJCT' => 'red',
        ]
    ],
    'rowOptions' => function (CurrencyPaymentSearch $model, $key) use ($isRealRegister, $document) {
        return [
            'data' => [
                'document-id' => $document->id,
                'payment-id' => $model->extId,
                'is-real-register' => $isRealRegister,
            ],
        ];
    },
    'columns' => [
        'numberDocument',
        'date:date',
        'payerName',
        'debitAccount',
        'creditAccount',
        'currency',
        [
            'attribute' => 'sum',
            'format' => 'html',
            'value' => function($model) {
                return str_replace(' ', '&nbsp;', Yii::$app->formatter->asDecimal($model->sum, 2));
            }
        ],
        'beneficiary',
        [
            'attribute' => 'businessStatus',
            'value' => function(CurrencyPaymentSearch $model) {
                return PaymentRegisterDocumentExt::translateBusinessStatus($model) ?: '';
            },
        ],
        [
            'class'    => 'yii\grid\ActionColumn',
            'template' => '{view}',
            'buttons'  => [
                'view' => function ($url, CurrencyPaymentSearch $model, $key) {
                    return Html::a(
                        '<span class="glyphicon glyphicon-eye-open">',
                        '#',
                        [
                            'title' => Yii::t('app', 'View'),
                            'class' => 'view-payment-button',
                        ]
                    );
                },
            ],
        ],
    ],
]);

$signatures = $document->getSignatures(Document::SIGNATURES_ALL, Cert::ROLE_SIGNER);

// Вывести блок подписей
echo $this->render('@common/views/document/_signatures', ['signatures' => $signatures]);
// Вывести модальное окно просмотра
echo $this->render('@addons/edm/views/documents/_fcoViewModal');

$this->registerJs(<<<JS
    function onViewPaymentClick(event) {
        event.preventDefault();

        var row = $(this).closest('tr');
        if (row.length === 0) {
            return;
        }

        var documentId = row.data('document-id');
        var paymentId = row.data('payment-id');
        var isRealRegister = row.data('is-real-register');

        var url = isRealRegister
            ? '/edm/currency-payment/view-register-payment?id=' + documentId + '&paymentId=' + paymentId
            : '/edm/currency-payment/view-payment?id=' + documentId;

        $('#fcoViewModal .modal-body').html('');
        $.ajax({
            url: url,
            type: 'get',
            success: function (answer) {
                $('#fcoViewModal').modal('show');
                $('#fcoViewModal .modal-body').html(answer);
            }
        });
    }
    $('.view-payment-button').click(onViewPaymentClick);
    $('#payments-grid tbody tr').dblclick(onViewPaymentClick);
JS);

$this->registerCss(
    '#buttons-block {
        margin-bottom: 1em;
    }
    #buttons-block .btn {
        margin-right: .5em;
    }'
);
