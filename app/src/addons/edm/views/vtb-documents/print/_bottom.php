<?php

use yii\helpers\Html;

/** @var \addons\edm\models\BaseVTBDocument\BaseVTBDocumentType $typeModel */
/** @var \common\document\DocumentStatusReportsData $statusReportsData */
/** @var string|null $bankName */
/** @var string $stampStatus */

$dateFormat = 'php:d.m.Y';
$dateTimeFormat = 'php:d.m.Y H:i';

$stampDate = $statusReportsData->getStatusDate($stampStatus);

?>

<?php if ($stampDate): ?>
    <div class="stamp">
        <strong><?= Html::encode($bankName) ?></strong><br>
        <strong>Принято</strong><br>
        <?= Yii::$app->formatter->asDate($stampDate, $dateFormat) ?>
    </div>
<?php endif; ?>

<p class="signatures-header">Электронные подписи</p>
<?php foreach ($typeModel->signatureInfo->signatures as $signature): ?>
    <p><?= Html::encode($signature->uid) ?></p>
<?php endforeach; ?>
<?php if (count($typeModel->signatureInfo->signatures) === 0): ?>
    <p>(нет)</p>
<?php endif; ?>

<?php
$accpDate = $statusReportsData->getStatusDate('ACCP');
$acscDate = $statusReportsData->getStatusDate('ACSC');
$rjctDate = $statusReportsData->getStatusDate('RJCT');
?>

<p>
    <strong>Дата представления в Банк:</strong>
    <?= $accpDate ? Yii::$app->formatter->asDatetime($accpDate, $dateTimeFormat) : '' ?>
    <br/>
    <strong>Дата принятия Банком: </strong>
    <?= $acscDate ? Yii::$app->formatter->asDatetime($acscDate, $dateTimeFormat) : '' ?>
    <br/>
    <strong>Дата отказа Банком: </strong>
    <?= $rjctDate ? Yii::$app->formatter->asDatetime($rjctDate, $dateTimeFormat) : '' ?>
    <br/>
    <strong>Причина отказа:</strong>
    <?= Html::encode($statusReportsData->getRejectionReason()) ?>
</p>

<style>
    .stamp {
        border: 1px solid black;
        float: right;
        margin-right: 20px;
        margin-top: 0;
        min-width: 200px;
        text-transform: uppercase;
    }
    .signatures-header {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 0;
    }
</style>
