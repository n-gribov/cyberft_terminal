<?php

use backend\assets\AppAsset;
use common\document\Document;
use common\helpers\DateHelper;
use common\models\cyberxml\CyberXmlDocument;
use common\modules\certManager\models\Cert;
use yii\helpers\Html;
use yii\web\BadRequestHttpException;
use yii\web\View;

/* @var $this View */
/* @var $model Document */
/* @var $mode string */
/* @var $content string */

$this->title = Yii::t('app', 'Document') . ' #' .$model->id;

if ($model->getValidStoredFileId()) {
    $typeModel = CyberXmlDocument::getTypeModel($model->getValidStoredFileId());
} else if ($model->status == $model::STATUS_CREATING) {
    echo 'Документ еще не создан';

    return;
} else {
    echo 'К сожалению, нет возможности отобразить документ данного типа';

    return;
}

$content = '';

switch( $mode ) {
	case 'printmt':
		$content = $typeModel->getSource->getContent();
		break;
	case 'readable':
		$content = $typeModel->getSource()->getContentReadable();
		break;
	case 'printable':
        $date = DateHelper::convert($model->dateCreate, 'datetime', 'php:d/m/y H:i:s');
        $content = 'CYBERFT (' . $model->uuid . ')     ' . $date;
		$content .= PHP_EOL . $typeModel->getSource()->getContentPrintable();
        $signatureDataProvider = $model->getSignatureDataProvider(Document::SIGNATURES_TYPEMODEL, Cert::ROLE_SIGNER);
        $allModels = $signatureDataProvider->allModels;
        if (!empty($allModels)) {
            $content .= "----------------------------- Signatures ---------------------------";
            $content .= PHP_EOL . "UID\t\t:\t" . $model->uuid;
            $signatureCount = 1;
            foreach($allModels as $signature) {
                $content .= PHP_EOL . 'Signature ' . $signatureCount++ . "\t:\t" . $signature['name'] . ' - ' . $signature['fingerprint'];
            }
        }
		break;
	default:
		throw new BadRequestHttpException("Unknown mode '{$mode}'");
}

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="<?= Yii::$app->language ?>">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=10;IE=11"/>
	<meta charset="<?= Yii::$app->charset ?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?= Html::csrfMetaTags() ?>
	<title><?= Html::encode($this->title) ?></title>
	<?php $this->head() ?>
	<style type="text/css">
	@media print{
		.no-print, .no-print *
		{
			display: none !important;
		}
	}
	pre.print-preview
	{
		font-family: monospace, monospace;
		font-size: 1em;
		border: 0px none;
		page-break-inside: avoid;
		display: block;
		padding: 0px;
		margin: 10px;
		margin-top: 0px;
		font-size: 13px;
		line-height: 1.42857143;
		word-break: break-all;
		word-wrap: break-word;
		color: #32373d;
		background-color: #ffffff;
		border-radius: 0px;
	}
	</style>
</head>
<body class="static-header">
	<?php $this->beginBody() ?>
	<div  class="panel-body no-print">
        <input type="button" id="btnPrint" value="<?=Yii::t('app', 'Print document')?>" class="btn btn-primary no-print" style="width:170px" onclick="window.print()" />
		<input type="button" id="btnCancel" value="<?=Yii::t('app', 'Close')?>" class="btn btn-default no-print"  style="width:170px" onclick="window.close()" />
	</div>
	<div id="forPrintPreview">
        <hr />
        <?= Yii::t('app', 'Received by CyberFT') ?>
        <hr />
        <pre class="print-preview"><?= $content ?></pre>
	</div>
	<?php $this->endBody() ?>
	<?php
		if (isset($this->blocks['javascripts'])) {
			echo $this->blocks['javascripts'];
		}
	?>
</body>
</html>
<?php $this->endPage() ?>