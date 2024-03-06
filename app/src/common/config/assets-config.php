<?php

Yii::setAlias('@webroot', Yii::getAlias('@backend/web'));
Yii::setAlias('@web', '/');

$utilsDirectory = Yii::getAlias('@projectRoot/utils');

return [
    'jsCompressor' => "java -Xmx512m -jar {$utilsDirectory}/compiler.jar --warning_level=QUIET --js {from} --js_output_file {to}",
    'cssCompressor' => "java -Xmx512m -jar {$utilsDirectory}/yuicompressor.jar --type css {from} -o {to}",
    'bundles' => [
        'backend\assets\AppAsset',
    ],
    'targets' => [
        'all' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'all-{hash}.js',
            'css' => 'all-{hash}.css',
        ],
    ],
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ],
];