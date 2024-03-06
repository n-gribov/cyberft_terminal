<?php
/**
 * Created by PhpStorm.
 * User: vk
 * Date: 18.03.15
 * Time: 22:19
 */

namespace addons\swiftfin\models\documents\mt\widgets\assets;

use yii\web\AssetBundle;

class CollectionAsset extends AssetBundle
{
	public $sourcePath = '@addons/swiftfin/models/documents/mt/widgets/assets';

	public $js = [
		'js/collection.js'
	];

	public $depends = [
		'yii\bootstrap\BootstrapAsset',
	];

}