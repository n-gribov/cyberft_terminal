<?php

use addons\edm\widgets\CancelVtbDocumentButton;
use common\widgets\FastPrint\FastPrint;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var \common\document\Document $document */
/** @var \common\models\vtbxml\documents\PayDocCur $bsDocument */
$backUrl = Yii::$app->request->get('backUrl', Yii::$app->request->referrer);
$this->title = \addons\edm\models\VTBDocument\VTBDocumentType::getName('VTBPayDocCur');
?>
<div style="padding-bottom: 1em;">
    <?= Html::a(
        Yii::t('app', 'Back'),
        $backUrl,
        ['class' => 'btn btn-default']
    ) ?>
    <?= Html::a(
        Yii::t('app', 'Print'),
        '#',
        ['class' => 'btn btn-default print-link']
    ) ?>
    <?= CancelVtbDocumentButton::widget([
        'document' => $document,
        'documentNumber' => $bsDocument->DOCUMENTNUMBER ?: null,
        'documentDate' => $bsDocument->DOCUMENTNUMBER ? $bsDocument->DOCUMENTDATE->format('Y-m-d') : null,
    ]) ?>
</div>
<?= // Вывести блок детализаци документа
    $this->render('_bsDocumentDetails', compact('document', 'bsDocument')) ?>
<div id="view-table-record-modal-placeholder"></div>

<?php
echo FastPrint::widget([
    'printUrl' => Url::to(['/edm/vtb-documents/print-pay-doc-cur-from-register', 'id' => $document->id, 'number' => $bsDocument->DOCUMENTNUMBER]),
    'printBtn' => '.print-link',
]);

$this->registerJsFile(
    '@web/js/edm/vtb-documents/view.js',
    [
        'depends' => [\yii\web\JqueryAsset::className()],
        'position' => \yii\web\View::POS_END,
    ]
);
