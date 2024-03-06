<?php
require(dirname(__DIR__) . '/vendor/autoload.php');
require(dirname(__DIR__) . '/common/env.php');
require(dirname(__DIR__) . '/common/Yii.php');
require(dirname(__DIR__) . '/common/config/bootstrap.php');
require(dirname(__DIR__) . '/console/config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(dirname(__DIR__) . '/common/config/main.php'),
    require(dirname(__DIR__) . '/console/config/main.php')
);

/*
 * Используем globals, поэтому первичная инициализация в null
 */
$app = null;
