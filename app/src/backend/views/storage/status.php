<?php
use backend\assets\AppAsset;

AppAsset::register($this);

/* @var $message string */
?>

<?php if (!$status) : ?>
	<script type="text/javascript">
		setTimeout(function () {
			window.location.reload(1);
		}, 10000);
	</script>
<?php endif ?>

<div class="info-block">
    <img src="/img/cyberft/logo.svg" width="300" height="300">
    <h4 <?= $status ? 'class="text-danger"' : ''?>><?=$message ?></h4>
</div>

<?php
$this->registerCss('
    .info-block {
        text-align: center;
        margin: 0 auto;
    }
');
