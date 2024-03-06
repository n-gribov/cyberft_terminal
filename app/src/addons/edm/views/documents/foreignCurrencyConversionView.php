<?php

use addons\edm\EdmModule;
use common\document\Document;
use common\document\DocumentPermission;
use common\modules\certManager\models\Cert;
use common\widgets\FastPrint\FastPrint;
use yii\helpers\Url;

?>
<?=$this->render('_fcoTransportInfo', compact('document', 'businessStatus', 'businessStatusDescription')) ?>
<?=$this->render('readable/foreignCurrencyConversion', compact('model')) ?>

<script>
    <?php if(Yii::$app->user->can(DocumentPermission::CREATE, ['serviceId' => EdmModule::SERVICE_ID, 'document' => $document])): ?>
        $('#fcoViewUpdateButton').data('id', <?= $document->id ?>).data('type', '<?= $model->operationType ?>');
    <?php else: ?>
        $('#fcoViewUpdateButton').addClass('hidden');
    <?php endif; ?>

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
$signatures = $document->getSignatures(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER);

echo $this->render('@common/views/document/_signatures', ['signatures' => $signatures]);

echo FastPrint::widget([
    'printUrl' => Url::toRoute(['/edm/documents/foreign-currency-operation-print', 'id' => $document->id, 'type' => $document->type]),
    'printBtn' => '#fcoViewPrintButton',
    'documentId' => $document->id
]);
