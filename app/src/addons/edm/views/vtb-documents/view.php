<?php

use addons\edm\EdmModule;
use addons\edm\widgets\CancelVtbDocumentButton;
use common\widgets\documents\SignDocumentsButton;
use common\widgets\FastPrint\FastPrint;
use common\widgets\TransportInfo\TransportInfoButton;
use common\widgets\TransportInfo\TransportInfoModal;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/** @var \yii\web\View $this */
/** @var \common\document\Document $document */
/** @var \common\models\vtbxml\documents\BSDocument $bsDocument */
/** @var \addons\edm\models\VTBPrepareCancellationRequest\VTBPrepareCancellationRequestForm $cancellationForm */
/** @var boolean $isPrintable */
$this->title = \addons\edm\models\VTBDocument\VTBDocumentType::getName($document->type);
$backUrl = Yii::$app->request->get('backUrl', Yii::$app->request->referrer);
?>
<div style="margin-bottom: 1em">
    <?php
    echo Html::a(
        Yii::t('app', 'Back'),
        $backUrl,
        ['class' => 'btn btn-default']
    );
    echo '&nbsp;';

    if ($document->isSignableByUserLevel(EdmModule::SERVICE_ID)) {
        echo SignDocumentsButton::widget([
            'buttonText' => Yii::t('app/message', 'Signing'),
            'documentsIds' => [$document->id],
            'successRedirectUrl' => Url::to([
                '/edm/vtb-documents/view',
                'id' => $document->id,
                'backUrl' => $backUrl,
            ]),
        ]);
        echo '&nbsp;';
    }

    if ($isPrintable) {
        echo Html::a(
            Yii::t('app', 'Print'),
            '#',
            ['class' => 'btn btn-default print-link']
        );
    }

    echo CancelVtbDocumentButton::widget([
        'document' => $document,
    ]);
    ?>

    <div class="pull-right">
        <?= TransportInfoButton::widget() ?>
    </div>
</div>

<?= // Вывести блок детализации документа
    $this->render('_bsDocumentDetails', compact('document', 'bsDocument')) ?>

<div id="view-table-record-modal-placeholder"></div>
<?= TransportInfoModal::widget(['document' => $document]) ?>

<?= FastPrint::widget([
    'printUrl' => Url::to(['/edm/vtb-documents/print', 'id' => $document->id]),
    'printBtn' => '.print-link',
]) ?>

<?php
$this->registerJsFile(
    '@web/js/edm/vtb-documents/view.js',
    [
        'depends' => [\yii\web\JqueryAsset::class],
        'position' => \yii\web\View::POS_END,
    ]
);

if (Yii::$app->request->get('triggerSigning')) {
    $this->registerJs("$('#sign-documents-button').trigger('click');", View::POS_READY);
}
if (Yii::$app->request->get('triggerCancellation')) {
    $this->registerJs("$('#show-cancel-document-modal-button').trigger('click');", View::POS_READY);
}
