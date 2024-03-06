#!/bin/bash
set -e

ABSOLUTE_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd $ABSOLUTE_PATH

source ../distr/inc/vars.sh
source ../distr/inc/functions.sh

#APPCHECKER
#http://cyberft-ci.cyberplat.com:11000
APPCHECKDIR='/home/appchecker'
APPCHECKREPORTPATH='/home/appchecker/data/projects/CyberFT/prj/reports/'
APPCHECKSERVER='192.168.60.112'
PROJECTNAME='CyberFT'
APPCHECKERPASS='C0sm0d4rk-'
APPCHECKERUSER='admin'
APPCHECKERJAVABIN="java -jar appchecker-rest-client.jar -s $APPCHECKSERVER -n $PROJECTNAME -u $APPCHECKERUSER -pw $APPCHECKERPASS -pt ci -o $APPCHECKREPORTPATH"

echo "Проверка работоспособности сервиса"
if [ `ps ax | grep -v grep | grep appchecker | wc -l` -eq 0 ]; then
  echo "Process is not running, Starting.."
  cd $APPCHECKDIR
  $APPCHECKDIR/start.sh &
  sleep 20
else
  echo "Process is running."
fi

echo "Начинаем анализ уязвимостей"
cd $APPCHECKDIR
$APPCHECKERJAVABIN
echo "Анализ закончен"
echo "Отчет лежит в папке $APPCHECKREPORTPATH"
