<?php

use common\document\Document;
use common\helpers\DocumentHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\transport\models\CFTStatusReportType;
use yii\widgets\DetailView;

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
        $statusReport = Document::find()->where(['uuidReference' => $uuid])
            ->andWhere(['type' => CFTStatusReportType::TYPE])->one();

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

$this->registerJs($script, yii\web\View::POS_READY);
