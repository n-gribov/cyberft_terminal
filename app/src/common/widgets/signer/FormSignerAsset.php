<?php
namespace common\widgets\signer;

class FormSignerAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@common/widgets/signer/assets';
    public $js = [
        'js/jquery.fn.serializeObject.js',
        'js/adapter/capicom.js',
		'js/formSigner.js'
    ];
}