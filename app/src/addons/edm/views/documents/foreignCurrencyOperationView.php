<?php

use addons\edm\EdmModule;
use addons\edm\models\ForeignCurrencyOperation\ForeignCurrencyOperationType;
use common\document\Document;
use common\modules\certManager\models\Cert;
use common\widgets\FastPrint\FastPrint;
use yii\helpers\Url;

/** @var Document $document */
/** @var ForeignCurrencyOperationType $typeModel */

if ($typeModel->operationType === ForeignCurrencyOperationType::OPERATION_PURCHASE) {
    $this->title = Yii::t('edm', 'Foreign currency purchase request') . ' #' . $document->id;
} else {
    $this->title = Yii::t('edm', 'Foreign currency sell request') . ' #' . $document->id;
}

?>
<script>
    $('#fcoViewModal .modal-header h4').text('<?= $typeModel->operationType !== null ? $typeModel->getOperationTypes()[$typeModel->operationType] : '' ?>');
    $('#fcoViewUpdateButton')
        .data('id', <?= $document->id ?>)
        .data('type', '<?= $typeModel->operationType ?>');

    $('#fcoViewPrintButton').attr('href', '/edm/documents/foreign-currency-operation-print?id=<?= $document->id?>&type=<?=$document->type ?>');
    $('#fcoViewDeleteButton').attr('href', '/edm/documents/foreign-currency-operation-delete?id=<?= $document->id ?>');

    $('#fcoViewSignButton')
        .toggle(<?= $document->isSignableByUserLevel(EdmModule::SERVICE_ID) ? 'true' : 'false' ?>)
        .data('document-id', <?= $document->id ?>);

    $('#rejectSignButton')
        .toggle(<?= $document->isSignableByUserLevel(EdmModule::SERVICE_ID) ? 'true' : 'false' ?>)
        .data('id', <?= $document->id ?>);
</script>
<?= $this->render('_fcoTransportInfo', compact('document', 'businessStatus', 'businessStatusDescription')) ?>
<?= $this->render('readable/foreignCurrencyOperation', ['document' => $document, 'typeModel' => $typeModel]) ?>
<br/><br/>
<?php
$signatures = $document->getSignatures(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER);
echo $this->render('@common/views/document/_signatures', ['signatures' => $signatures]);

echo FastPrint::widget([
    'printUrl' => Url::toRoute(['/edm/documents/foreign-currency-operation-print', 'id' => $document->id, 'type' => $typeModel->operationType]),
    'printBtn' => '#fcoViewPrintButton',
    'documentId' => $document->id,
    'documentType' => $document->type,
]);