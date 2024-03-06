<?php
use addons\swiftfin\models\documents\mt\mtUniversal\Sequence;

/* @var $model Sequence */
?>
<?php if (!$model->disableContainer): ?>
<div class="panel-body">
<?php endif ?>

<?php
	foreach ($model->getModel() as $k => $v) {
		print $v->toHtmlForm($form);
	}
?>

<?php if (!$model->disableContainer): ?>
</div>
<?php endif ?>
