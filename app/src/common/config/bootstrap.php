<?php
Yii::setAlias('project', dirname(dirname(__DIR__)));
Yii::setAlias('common', dirname(__DIR__));
Yii::setAlias('addons', dirname(dirname(__DIR__)) . '/addons');
Yii::setAlias('backend', dirname(dirname(__DIR__)) . '/backend');
Yii::setAlias('console', dirname(dirname(__DIR__)) . '/console');
Yii::setAlias('appRuntime', dirname(dirname(__DIR__)) . '/runtime');
Yii::setAlias('logs', dirname(dirname(dirname(__DIR__))) . '/logs');

// Корневой каталог проекта
Yii::setAlias('projectRoot', dirname(dirname(dirname(__DIR__))) . '/');
Yii::setAlias('bin', dirname(dirname(__DIR__)).'/bin');
Yii::setAlias('import', '@projectRoot/import');
Yii::setAlias('export', '@projectRoot/export');
Yii::setAlias('storage', '@projectRoot/storage');
Yii::setAlias('temp', '@projectRoot/temp');
Yii::setAlias('cftcp', '@projectRoot/cftcp');