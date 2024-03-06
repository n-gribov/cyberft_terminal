#!/bin/bash

if [ "$EUID" -ne 0 ]
    then echo "Please run as root"
    exit
fi

APP_DIR=`dirname "$(readlink -f "$0")"`;
source $APP_DIR/.env

status() {
    # @todo Adapt to init.d specs
    if [ `cat $APP_DIR/.env | grep COMPOSE=1 | wc -l` -eq 1 ]; then
        service cron status
        service stunnel4 status
        service php7.3-fpm status
        service incron status
        $APP_DIR/background-jobs status
    else
        service cron status
        service stunnel4 status
        service mysql status
        service nginx status
        service php7.3-fpm status
        service redis-server status
        #service elasticsearch status
        service incron status
        $APP_DIR/background-jobs status
    fi
}

start() {
    if [ `cat $APP_DIR/.env | grep COMPOSE=1 | wc -l` -eq 1 ]; then
        service php7.3-fpm start
            if [ -d /var/run/php ];then
                chmod -R 0777 /var/run/php
            fi
        service stunnel4 start
        service incron start
        incrontab -r
        incrontab /etc/incron_cyberft.conf
        $APP_DIR/background-jobs start
    else
        service cron start
        service mysql start
        service nginx start
        service php7.3-fpm start
            if [ -d /var/run/php ];then
                chmod -R 0777 /var/run/php
            fi
        service stunnel4 start
        service redis-server start
        #service elasticsearch start
        service incron start
        incrontab -r
        incrontab /etc/incron_cyberft.conf
        $APP_DIR/src/yii resque/purge
        $APP_DIR/background-jobs start
    fi
}

stop() {
    if [ `cat $APP_DIR/.env | grep COMPOSE=1 | wc -l` -eq 1 ]; then
        $APP_DIR/background-jobs stop
        service cron stop
        service php7.3-fpm stop
        service stunnel4 stop
        service incron stop
    else
        $APP_DIR/background-jobs stop
        service cron stop
        service stunnel4 stop
        service mysql stop
        service nginx stop
        service php7.3-fpm stop
        #service elasticsearch stop
        service incron stop
    fi
}

case "$1" in
  status)
    status
    ;;
  start)
    start
    ;;
  stop)
    stop
    ;;
  restart)
    stop
    start
    ;;
  pause)
    $APP_DIR/background-jobs stop
    ;;
  resume)
    $APP_DIR/background-jobs restart
    ;;
  *)
    echo "Usage: service cyberft {start|stop|restart|pause|resume}"
esac
