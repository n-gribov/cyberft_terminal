<?php
use backend\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
AppAsset::register($this);

$this->beginPage();
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html lang="<?=Yii::$app->language?>">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=10;IE=11"/>
	<meta charset="<?=Yii::$app->charset?>"/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?=Html::csrfMetaTags()?>
	<title><?=Html::encode($this->title)?></title>
	<?php $this->head() ?>
</head>
<body style="padding-top: 10px; background: #ffffff">
<?php
$this->beginBody();
print $content;
$this->endBody();
if (isset($this->blocks['javascripts'])) {
    echo $this->blocks['javascripts'];
}
?>
</body>
</html>
<?php
$this->endPage();
