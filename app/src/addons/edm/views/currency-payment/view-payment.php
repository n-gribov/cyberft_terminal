<?php

use addons\edm\EdmModule;
use addons\edm\models\CurrencyPaymentRegister\CurrencyPaymentRegisterDocumentExt;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\modules\certManager\models\Cert;
use common\widgets\FastPrint\FastPrint;
use yii\web\View;
use yii\widgets\DetailView;

/** @var Document $document */

// Получение данных для вывода транспортной информации
$status = DocumentHelper::getStatusLabel($document);
$statusValue = $status['label'];

$transportInfoAttributes = [
    'senderParticipant',
    'receiverParticipant',
    [
        'attribute' => 'status',
        'value' => $statusValue
    ],
    'uuid',
    'origin',
    'directionLabel',
    [
        'attribute' => 'signaturesRequired',
        'visible' => !is_null($document->signaturesRequired),
    ],
    [
        'attribute' => 'signaturesCount',
        'visible' => !is_null($document->signaturesCount),
    ],
    'dateCreate',
    'dateUpdate'
];

$extModel = $document->extModel;
$isPaymentFromRegister = $extModel instanceof CurrencyPaymentRegisterDocumentExt;
$isSinglePaymentFromRegister = $isPaymentFromRegister && $extModel->paymentsCount == 1;

if ($isPaymentFromRegister) {
    $transportInfoAttributes[] = [
        'label' => Yii::t('document','Execution status'),
        'value' => $businessStatus
    ];
    $transportInfoAttributes[] = [
        'label' => Yii::t('document','Status description'),
        'value' => $businessStatusDescription
    ];
}

?>

<a href="" class="btn-transport-info">
    <span class="glyphicon glyphicon-info-sign"></span>
    Транспортная информация
</a>

<div class="transport-info">
<?php
    // Создать детализированное представление
    echo DetailView::widget([
        'model' => $document,
        'attributes' => $transportInfoAttributes,
    ]);
?>
</div>

<div class="row margin-bottom-10">
    <div class="col-md-6">
        <strong>Номер документа </strong><?=$model->number?>
    </div>
    <div class="col-md-6">
        <strong>Дата </strong><?=$model->date?>
    </div>
</div>

<?= // Вывести страницу
    $this->render('@addons/edm/views/documents/readable/foreignCurrencyPayment', ['model' => $model]) ?>

<script>
    $('#fcoViewModal .modal-header h4').text('<?= Yii::t('edm', 'Foreign currency payment') ?>');
    $('#fcoViewUpdateButton')
        .data('id', <?= $document->id ?>)
        .data('type', '<?= $model->operationType ?>');

    $('#fcoViewPrintButton').attr('href', '/edm/documents/foreign-currency-operation-print?id=<?= $document->id ?>&type=<?= $document->type ?>');
    $('#fcoViewDeleteButton').attr('href', '/edm/documents/foreign-currency-operation-delete?id=<?= $document->id ?>');

    $('#fcoViewDeleteButton').toggle(<?= $document->isSignable() && !$isPaymentFromRegister ? 'true' : 'false' ?>);
    $('#fcoViewUpdateButton').toggle(<?= $document->isSignable() && $document->type === 'MT103' ? 'true' : 'false' ?>);
    $('#fcoViewPrintButton').toggle(<?= $document->type === 'MT103' ? 'true' : 'false' ?>);

    $('#fcoViewSignButton')
        .toggle(<?= $document->isSignableByUserLevel(EdmModule::SERVICE_ID) && (!$isPaymentFromRegister || $isSinglePaymentFromRegister) ? 'true' : 'false' ?>)
        .data('document-id', <?= $document->id ?>);
</script>

<?php
$signatures = $document->getSignatures(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER);

// Вывести блок подписей
echo $this->render('@common/views/document/_signatures', ['signatures' => $signatures]);

echo FastPrint::widget([
    'printUrl' => "/edm/documents/foreign-currency-operation-print?id={$document->id}&type={$document->type}",
    'printBtn' => '.print-link',
    'documentId' => $document->id,
    'documentType' => $document->type
]);

$this->registerCss('
    .transport-info {
        display: none;
    }

    .btn-transport-info {
        color: #4c4c4c;
        font-weight: bold;
        margin-bottom: 15px;
        display: block;
    }

    .btn-transport-info:hover,
    .btn-transport-info:active,
    .btn-transport-info:visited,
    .btn-transport-info:focus {
       text-decoration: none;
    }
');

$script = <<<JS
    $('.btn-transport-info').on('click', function(e) {
        e.preventDefault();
        $('.transport-info').slideToggle('400');
    });
JS;

$this->registerJs($script, View::POS_READY);