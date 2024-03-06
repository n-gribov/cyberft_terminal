#!/usr/bin/env bash
ABSOLUTE_PATH="/var/www/cyberft/app";
echo "background-jobs stop"
$ABSOLUTE_PATH/background-jobs stop
echo "clean redis keys"
$ABSOLUTE_PATH/src/yii resque/purge
echo "background-jobs start"
$ABSOLUTE_PATH/background-jobs start