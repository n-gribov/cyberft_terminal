<?php

use addons\edm\EdmModule;
use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\certManager\models\Cert;
use common\modules\transport\models\CFTStatusReportType;
use common\widgets\FastPrint\FastPrint;
use yii\web\View;
use yii\widgets\DetailView;

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
];

$errorDescription = null;

if ($document->status == Document::STATUS_REJECTED) {
    $uuid = $document->direction == Document::DIRECTION_OUT ? $document->uuid : $document->uuidRemote;
    if ($uuid) {

        $statusReport = Document::find()
            ->where(['uuidReference' => $uuid])
            ->andWhere(['type' => CFTStatusReportType::TYPE])
            ->one();

        if ($statusReport) {
            $typeModel = CyberXmlDocument::getTypeModel($statusReport->actualStoredFileId);
            $errorDescription = $typeModel->errorDescription;
        }
    }
}

if ($errorDescription) {
    $transportInfoAttributes[] = [
        'label' => Yii::t('other', 'Error description'),
        'value' => $errorDescription
    ];
}

$transportInfoAttributes[] = 'uuid';
$transportInfoAttributes[] = 'origin';
$transportInfoAttributes[] = 'directionLabel';
$transportInfoAttributes[] = [
    'attribute' => 'signaturesRequired',
    'visible' => !is_null($document->signaturesRequired),
];
$transportInfoAttributes[] = [
    'attribute' => 'signaturesCount',
    'visible' => !is_null($document->signaturesCount),
];

$transportInfoAttributes[] = 'dateCreate';
$transportInfoAttributes[] = 'dateUpdate';
$transportInfoAttributes[] = [
    'label' => Yii::t('document','Execution status'),
    'value' => $businessStatus
];
$transportInfoAttributes[] = [
    'label' => Yii::t('document','Status description'),
    'value' => $businessStatusDescription
];
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
<?php
// Вывести страницу
echo $this->render('readable/foreignCurrencySellTransit', ['model' => $model, 'signatureList' => $signatureList]);

// Формирование url для обратного редиректа
$referrer = parse_url(Yii::$app->request->referrer);

$referrerUrl = $referrer['path'];

if (isset($referrer['query'])) {
    $referrerUrl .= '?' . $referrer['query'];
}
?>
<script>
    $('#fcoViewModal .modal-header h4').text('<?= Yii::t('edm', 'Sell of foreign currency from the transit account') ?>');

    $('#fcoViewUpdateButton')
        .data('id', <?= $document->id ?>)
        .data('type', '<?= $model->operationType ?>');

    $('#fcoViewPrintButton').attr('href', '/edm/documents/foreign-currency-operation-print?id=<?= $document->id?>&type=<?= $document->type ?>');
    $('#fcoViewDeleteButton').attr('href', '/edm/documents/foreign-currency-operation-delete?id=<?= $document->id ?>');

    $('#fcoViewDeleteButton, #fcoViewUpdateButton').toggle(<?= $document->isSignable() ? 'true' : 'false' ?>);

    $('#fcoViewSignButton')
        .toggle(<?= $document->isSignableByUserLevel(EdmModule::SERVICE_ID) ? 'true' : 'false' ?>)
        .data('document-id', <?= $document->id ?>);

    $('#rejectSignButton')
        .toggle(<?= $document->isSignableByUserLevel(EdmModule::SERVICE_ID) ? 'true' : 'false' ?>)
        .data('id', <?= $document->id ?>);
</script>

<?php
echo FastPrint::widget([
    'printUrl' => "/edm/documents/foreign-currency-operation-print?id={$document->id}&type={$document->type}",
    'printBtn' => '.print-link',
    'documentId' => $document->id,
    'documentType' => $document->type
]);

$this->registerCss(<<<CSS
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
CSS);

$script = <<<JS
    $('.btn-transport-info').on('click', function(e) {
        e.preventDefault();
        $('.transport-info').slideToggle('400');
    });
JS;

$this->registerJs($script, View::POS_READY);
