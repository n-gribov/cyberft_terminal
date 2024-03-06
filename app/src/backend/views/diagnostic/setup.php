<?php

$this->title = $title;

$base = [];
ksort($config);
foreach($config as $k=>$v) {
	if(!is_array($v)) {
		$base[$k] = $v;
		unset($config[$k]);
	}
}
ksort($base);
$config = array_merge(['base' => $base],$config);

/**
 * @var $this View
 * @var $config array
 */
?>
<?=$this->render('_submenu');?>

	<div class="panel-heading">
		<h4>
			<ul class="nav nav-tabs">
				<?php $i=0; foreach($config as $k=>$v): ?>
					<li<?=(0===$i?' class="active"':null)?>>
						<a href="#<?=$k?>" data-toggle="tab"><?=$k?></a>
					</li>
				<?php $i++; endforeach; ?>
			</ul>
		</h4>
	</div>
	<div class="panel-body">
		<div class="tab-content">
			<?php $i=0; foreach($config as $k=>$v): ?>
				<div class="tab-pane<?=(0===$i?' active':null)?>" id="<?=$k?>"><pre><?=json_encode($v,JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)?></pre></div>
			<?php $i++; endforeach; ?>
		</div>
	</div>

