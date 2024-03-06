<?php
use addons\swiftfin\models\Document;
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model Document */
/* @var $mode string */
/* @var $content string */
$content = $model->contentPrintable;
AppAsset::register($this);
$this->beginPage();
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
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
    <div class="panel-body no-print">
        <input type="button" id="btnPrint" value="<?=Yii::t('app', 'Print document')?>" class="btn btn-primary no-print" onclick="window.print()" />
        <input type="button" id="btnCancel" value="<?=Yii::t('app', 'Close')?>" class="btn btn-default no-print" onclick="window.close()" />
    </div>
    <div id="forPrintPreview">
        <pre class="print-preview"><?php if ($this->title) : ?><h4><?= Html::encode($this->title) ?></h4><?php endif ?><?= $content ?></pre>
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