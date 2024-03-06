<?php
use addons\swiftfin\models\Document;
use yii\helpers\Url;
use yii\web\View;
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $model Document */
/* @var $mode string */

AppAsset::register($this);

$this->registerCss(<<<CSS
    #forPrintPreview {
        padding: 1em;
        font-size: 11px
    }
CSS
);

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
</head>
<body class="static-header">
	<?php $this->beginBody() ?>
	<div id="forPrintPreview">
		<?php if ($this->title): ?><h4><?= Html::encode($this->title) ?></h4><?php endif; ?><?= $content ?>
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