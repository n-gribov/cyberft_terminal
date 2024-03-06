#!/bin/bash
APP_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
MODE_UPDATE=false
cryptotempcertdir=$APP_DIR/temp/crypto_keys
cryptoprodir=/var/opt/cprocsp/keys/www-data

for i in "$@"
do
case $i in
    -u|--update)
    MODE_UPDATE=true
    shift # past argument
    ;;
    -d=*|--data-file=*)
    DATA_FILE="${i#*=}"
    shift # pass argument
    ;;
    *)
            # unknown option
    ;;
esac
done

. $APP_DIR/.env

$APP_DIR/service.sh stop

#mysql section
if [ "$MODE_UPDATE" == "false" ]; then
    mysql_install_db
    service mysql start
    mysqladmin -u "$MYSQL_USERNAME" password "$MYSQL_PASSWORD"
        else if [ "$INSTALL_TYPE" == "docker" ]; then
            MYSQL_USER=root
            chown -R $MYSQL_USER:$MYSQL_USER /var/lib/mysql/
            chmod -R 0755 /var/lib/mysql
            service mysql start
                else if [ "$INSTALL_TYPE" == "direct" ]; then
                    MYSQL_USER=`stat -c "%U" '/var/lib/mysql/'`
                    chown -R $MYSQL_USER:$MYSQL_USER /var/lib/mysql/
                    chown -R $MYSQL_USER:$MYSQL_USER /etc/mysql
                    chmod -R 0755 /var/lib/mysql
                    chmod -R 0755 /etc/mysql
                          if [ ! -d /var/lib/mysql-files ];then
                            mkdir /var/lib/mysql-files
                          fi
                    service mysql start
                fi
        fi
fi

#redis section
chown -R $REDIS_USER:$REDIS_USER /var/lib/redis/
chmod -R 0755 /var/lib/redis/
service redis-server start

#nginx section
if [ "$MODE_UPDATE" == "false" ]; then
    if [ ! -f  $APP_DIR/logs/access.log ]; then
        if [ ! -d $APP_DIR/logs ]; then
            mkdir $APP_DIR/logs
            chown -R $NGINX_USER:$NGINX_USER $APP_DIR/logs/
            chmod -R 0755 $APP_DIR/logs/
        fi
        echo "Creating access log"
        touch $APP_DIR/logs/access.log
        chown $NGINX_USER:$NGINX_USER $APP_DIR/logs/access.log
        echo "Access log successfully created"
    fi
    rm -rf /etc/nginx/sites-enabled/default
    usermod -a -G $NGINX_USER nginx
    chown -R $NGINX_USER:$NGINX_USER $APP_DIR
    chmod -R g=u $APP_DIR
fi

service nginx start

#for samba ownership part one
#if [ -d $APP_DIR/import ];then
#    SHARE_DIR_USER=`stat -c "%U" $APP_DIR/import`
#    SHARE_DIR_GROUP=`stat -c "%G" $APP_DIR/import`
#    SHARE_DIR_RIGHTS=`stat -c "%a" $APP_DIR/import`
#else
#    SHARE_DIR_USER='www-data'
#    SHARE_DIR_GROUP='www-data'
#    SHARE_DIR_RIGHTS='775'
#fi

#cyberft-crypt
echo "installing cyberft-crypt"
chmod +x $APP_DIR/src/bin/cyberft-crypt
ln -sf $APP_DIR/src/bin/cyberft-crypt /usr/bin/cyberft-crypt
cyberft-crypt version
echo "cyberft-crypt successfully installed"

#update application
if [ "$MODE_UPDATE" == "false" ]; then
sudo -u $NGINX_USER $APP_DIR/src/init --default
fi
sudo -u $NGINX_USER $APP_DIR/src/yii app/update

#for samba ownership part two
#chown -R $SHARE_DIR_USER:$SHARE_DIR_GROUP $APP_DIR/import/
#chown -R $SHARE_DIR_USER:$SHARE_DIR_GROUP $APP_DIR/export/
#chown -R $SHARE_DIR_USER:$SHARE_DIR_GROUP $APP_DIR/import/*
#chown -R $SHARE_DIR_USER:$SHARE_DIR_GROUP $APP_DIR/export/*
#chmod -R $SHARE_DIR_RIGHTS $APP_DIR/import/
#chmod -R $SHARE_DIR_RIGHTS $APP_DIR/export/
#chmod -R $SHARE_DIR_RIGHTS $APP_DIR/import/*
#chmod -R $SHARE_DIR_RIGHTS $APP_DIR/export/*

if [ "$MODE_UPDATE" == "false" ]; then
    if [ -z "$DATA_FILE" ]; then
        DATA_FILE="$APP_DIR/$DATA_FILE"
    fi
    sudo -u $NGINX_USER $APP_DIR/src/yii app/config $APP_DIR/$DATA_FILE
fi

$APP_DIR/service.sh restart

#php7 fix
if [ -d /var/run/php ]; then
    chmod -R 0777 /var/run/php/ > /dev/null 2>&1
    chmod -R 0777 /var/run/php/* > /dev/null 2>&1
fi

#crypto-pro update
if [ "$INSTALL_TYPE" == "docker" ]; then
    if [ "$MODE_UPDATE" == "true" ]; then
        if [ -d $cryptotempcertdir ]; then
             cp -R  $cryptotempcertdir/* $cryptoprodir/
             echo ""
             echo "**********************************************"
             echo "Установка лизензии Крипто-Про"
             cryptolicence=`cat $cryptotempcertdir/license.txt`
             /opt/cprocsp/sbin/amd64/cpconfig -license -set $cryptolicence
             echo "Лизензия Крипто-Про была установлена"
             echo "**********************************************"
             echo ""
             echo ""
             echo "**********************************************"
             echo "Установка контейнеров Крипто-Про"
             chmod -R 0755 $cryptoprodir
             chown -R $NGINX_USER:$NGINX_USER $cryptoprodir
             php $APP_DIR/utils/crypto_pro_containers.php
             echo "Контейнеры Крипто-Про были установлены"
             echo "**********************************************"
             echo ""
             echo ""
             echo "**********************************************"
             echo "Сертификаты Крипто-Про для верификации входящих документов были обновлены"
             sudo -u $NGINX_USER env PATH="$PATH:$(ls -d /opt/cprocsp/{s,}bin/*|tr '\n' ':')" $APP_DIR/src/yii cryptopro/add-certificates-from-terminal iso20022 ignore-status
             sudo -u $NGINX_USER env PATH="$PATH:$(ls -d /opt/cprocsp/{s,}bin/*|tr '\n' ':')" $APP_DIR/src/yii cryptopro/add-certificates-from-terminal fileact ignore-status
             echo "**********************************************"
             echo ""
             rm -rf $cryptoprodir/license.txt
             rm -rf $cryptotempcertdir
        fi
    fi
fi

#crontab for background-jobs
if [ $(crontab -l | wc -l) -eq 0 ]; then
    if [ ! -f /var/log/cronjobs.log ]; then
        echo "creating cronjob log file"
        touch /var/log/cronjobs.log
    fi
    echo "create schedule for cron"
    (crontab -l ; echo "0 1 * * * /var/www/cyberft/app/utils/cronjobs/cronjobs.sh | /var/www/cyberft/app/utils/cronjobs/timestamp.sh >> /var/log/cronjobs.log") | crontab -
fi

#hack for cpro
#http://cpdn.cryptopro.ru/content/csp40/html/timevalidity.html
/opt/cprocsp/sbin/amd64/cpconfig -ini '\config\parameters' -add long StrengthenedKeyUsageControl 0
/opt/cprocsp/sbin/amd64/cpconfig -ini '\config\parameters' -add long ControlKeyTimeValidity 0