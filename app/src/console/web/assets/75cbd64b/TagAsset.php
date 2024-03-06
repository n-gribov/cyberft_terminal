<?php
namespace addons\swiftfin\models\documents\mt\widgets\assets;

use yii\web\AssetBundle;

class TagAsset extends AssetBundle
{

	public $sourcePath = '@addons/swiftfin/models/documents/mt/widgets/assets';

	public $js = [
		'js/jquery.filter_input.js'
	];
	public $css = [
		'css/tag.css'
	];

	public $depends = [
		'yii\bootstrap\BootstrapAsset'
	];

}