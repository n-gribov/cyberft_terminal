<?php

use common\document\Document;
use common\helpers\DocumentHelper;
use yii\bootstrap\Modal;
use yii\widgets\DetailView;

/* @var Document $document */

$infoAttributes = [
    'senderParticipant',
    'receiverParticipant',
    'signaturesRequired',
    'signaturesCount',
    'origin',
    'uuid',
];

$status = DocumentHelper::getStatusLabel($document);
$statusValue = $status['label'];

$detailsAttributes = [
    'directionLabel',
    'dateCreate',
    'dateUpdate',
    [
        'attribute' => 'status',
        'format' => 'html',
        'value' => ($isVolatile ? '<i class="fa fa-spinner fa-spin"></i> ' : '') . $statusValue
    ]
];

$extModel = $document->extModel;

// Для Pain.001 и Auth.026 выводим информацию из документа pain.002
if ($extModel) {
    $businessStatusLabel = Yii::t('document', 'Unknown');
    $businessStatusDescription = Yii::t('document', 'Unknown');

    if ($document->typeGroup == 'ISO20022' && $document->type != 'pain.002') {

        // Получение бизнес-статуса
        $businessStatusLabels = DocumentHelper::getBusinessStatusesList();

        if (isset($extModel->statusCode)) {
            $businessStatusLabel = isset($businessStatusLabels[$extModel->statusCode])
                ? $businessStatusLabels[$extModel->statusCode]
                : $extModel->statusCode;
        }
        if ($extModel->errorDescription) {
            $businessStatusDescription = $extModel->errorDescription;
        }
    } elseif ($document->direction == Document::DIRECTION_OUT) {
        if ($extModel->hasAttribute('businessStatus')) {
            $businessStatusLabels = DocumentHelper::getBusinessStatusesListWithPartially();
            if (array_key_exists($extModel->businessStatus, $businessStatusLabels)) {
                $businessStatusLabel = $businessStatusLabels[$extModel->businessStatus];
            }
        }
        if ($extModel->hasAttribute('businessStatusDescription') && $extModel->businessStatusDescription) {
            $businessStatusDescription = $extModel->businessStatusDescription;
        }
    }

    $detailsAttributes[] = [
        'format' => 'html',
        'label' => Yii::t('document', 'Processing status on the receiver system'),
        'value' => $businessStatusLabel,
    ];

    $detailsAttributes[] = [
        'label' => Yii::t('document', 'Processing status description'),
        'value' => $businessStatusDescription,
    ];
}

if ($errorDescription) {
    $detailsAttributes[] = [
        'label' => Yii::t('other', 'Error description'),
        'value' => $errorDescription
    ];
}

Modal::begin([
    'id' => 'transport-info-modal',
    'header' => '<h4 class="modal-title">' . Yii::t('document', 'Transport information') . '</h4>',
    'footer' => null,
]); ?>

<div class="row">
    <div class="col-lg-7">
        <?= DetailView::widget([
            'model' => $document,
            'attributes' => $infoAttributes
        ]) ?>
    </div>
    <div class="col-lg-5">
        <?= DetailView::widget([
            'model' => $document,
            'template' => '<tr><th width="50%">{label}</th><td>{value}</td></tr>',
            'attributes' => $detailsAttributes
        ]) ?>
    </div>
</div>

<?php
Modal::end();

$this->registerCss('
    #transport-info-modal .modal-dialog {
        width: 900px;
    }
');

?>
