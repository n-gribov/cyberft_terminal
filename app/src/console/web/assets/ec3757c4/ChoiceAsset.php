<?php
namespace addons\swiftfin\models\documents\mt\widgets\assets;

use yii\web\AssetBundle;

class ChoiceAsset extends AssetBundle
{
	public $sourcePath = '@addons/swiftfin/models/documents/mt/widgets/assets';

	public $js = [
		'js/choiceSwitcher.js',
	];

	public $depends = [
		'yii\bootstrap\BootstrapAsset',
	];

}