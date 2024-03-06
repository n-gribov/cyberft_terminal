#!/bin/bash
set -e

if [ "$1" = "docker" ] || ask "Выполнить резервное копирование? Внимание, это может занять длительное время!" ; then
    notice "Проверка свободного места";
    USED=`df -h $PREV_INSTALL_DIR | sed -n -e 2p | sed -r 's/ +/ /g' | cut '-d ' -f5 | sed 's/%//'`
    notice "Занятое пространство: $USED% "
        if [ "$1" = "docker" ] || ask "Внимание! Не рекомендуется выполнять обновление при занятом пространстве более 60%. Выполнить копирование ?" ; then
            notice "Начинаем резервное копирование файлов, не прерывайте процесс.";
            cp --force $APP_DIR/backup.sh $PREV_INSTALL_DIR/app
            mkdir -p $PREV_INSTALL_DIR/app/backup/latest
            rm -rf $PREV_INSTALL_DIR/app/backup/latest/*
            $PREV_INSTALL_DIR/app/backup.sh
            mv $PREV_INSTALL_DIR/app/src $PREV_INSTALL_DIR/app/backup/latest
            mv $PREV_INSTALL_DIR/distr $PREV_INSTALL_DIR/app/backup/latest
            mv $PREV_INSTALL_DIR/config $PREV_INSTALL_DIR/app/backup/latest
            notice "Резервное копирование завершено";
        fi
            if [ -d "$PREV_INSTALL_DIR/app/src.bak" ]; then
                rm -rf $PREV_INSTALL_DIR/app/src.bak
        fi
fi

# copy app source files
echo ""
echo "**********************************************"
notice "Копируем новые файлы проекта CyberFT"

#remove old migrations
rm -rf $PREV_INSTALL_DIR/app/src/console/migrations/*
rm -rf $PREV_INSTALL_DIR/app/src/addons/swiftfin/migrations/*
rm -rf $PREV_INSTALL_DIR/app/src/addons/ISO20022/migrations/*
rm -rf $PREV_INSTALL_DIR/app/src/addons/finzip/migrations/*
rm -rf $PREV_INSTALL_DIR/app/src/addons/fileact/migrations/*
rm -rf $PREV_INSTALL_DIR/app/src/addons/edm/migrations/*

#remove old utils files
if [ -f $PREV_INSTALL_DIR/app/crypto-pro-container-import.sh ]; then
    rm -rf $PREV_INSTALL_DIR/app/crypto-pro-container-import.sh
    rm -rf $PREV_INSTALL_DIR/app/crypto-pro-backup.sh
    rm -rf $PREV_INSTALL_DIR/app/crypto_pro_key_name.php
    rm -rf $PREV_INSTALL_DIR/app/crypto_pro_containers.php
    rm -rf $PREV_INSTALL_DIR/app/crypto_pro_license.php
fi

#copy new src
cp -R $APP_DIR/src $PREV_INSTALL_DIR/app

#create utils directory for old versions
if [ ! -d "$PREV_INSTALL_DIR/app/utils" ]; then
    mkdir $PREV_INSTALL_DIR/app/utils
fi

#copy scripts
cp --force $APP_DIR/background-jobs $PREV_INSTALL_DIR/app
cp --force $APP_DIR/install.sh $PREV_INSTALL_DIR/app
cp --force $APP_DIR/service.sh $PREV_INSTALL_DIR/app
cp --force $APP_DIR/gitinfo.json $PREV_INSTALL_DIR/app
cp --force $APP_DIR/utils/crypto_pro_container_import.sh $PREV_INSTALL_DIR/app/utils
cp --force $APP_DIR/utils/crypto_pro_backup.sh $PREV_INSTALL_DIR/app/utils
cp --force $APP_DIR/utils/crypto_pro_key_name.php $PREV_INSTALL_DIR/app/utils
cp --force $APP_DIR/utils/crypto_pro_containers.php $PREV_INSTALL_DIR/app/utils
cp --force $APP_DIR/utils/crypto_pro_license.php $PREV_INSTALL_DIR/app/utils
cp --force $APP_DIR/utils/yuicompressor.jar $PREV_INSTALL_DIR/app/utils
cp --force $APP_DIR/utils/compiler.jar $PREV_INSTALL_DIR/app/utils
cp --force $APP_DIR/utils/prepare_file.sh $PREV_INSTALL_DIR/app/utils
cp -R --force $APP_DIR/utils/cronjobs/ $PREV_INSTALL_DIR/app/utils
notice "Файлы скопированы в директорию $PREV_INSTALL_DIR"
echo "**********************************************"
echo ""

# copy updated distr scripts
notice "Копируем служебные скрипты";
cp -R $DISTR_DIR $PREV_INSTALL_DIR
notice "Служебные скрипты были скопированы в директорию $PREV_INSTALL_DIR""distr";

# nginx section, create config
NGINX_CONF_DEFAULT="$INSTALL_DIR/config/nginx/cyberft_direct.conf";
NGINX_CONF="$INSTALL_DIR/config/nginx/cyberft.conf";

cat $NGINX_CONF_DEFAULT | sed 's|HOME_DIR/|'"$PREV_INSTALL_DIR"'|' > $NGINX_CONF
chmod 777 $NGINX_CONF

# copy updated configs
echo ""
echo "**********************************************"
notice "Копируем новые конфигурационные файлы"
if [ -d $PREV_INSTALL_DIR/config/nginx/openssl ]; then
    echo "Каталог nginx/openssl не будет перезаписан"
    cp $INSTALL_DIR/config/nginx/*.conf $PREV_INSTALL_DIR/config/nginx
else
    echo "Каталог nginx/openssl будет перезаписан"
    cp -R $INSTALL_DIR/config/nginx/ $PREV_INSTALL_DIR/config
fi
cp -R $INSTALL_DIR/config/mysql $PREV_INSTALL_DIR/config
cp -R $INSTALL_DIR/config/php5-fpm $PREV_INSTALL_DIR/config
cp -R $INSTALL_DIR/config/stunnel $PREV_INSTALL_DIR/config
cp -R $INSTALL_DIR/config/elasticsearch $PREV_INSTALL_DIR/config
cp -R $INSTALL_DIR/config/incron $PREV_INSTALL_DIR/config
chmod -R 0755 $PREV_INSTALL_DIR/config/*
notice "Конфигурационные файлы из каталога $INSTALL_DIR/config скопированы в каталог $PREV_INSTALL_DIR""config"
echo "**********************************************"
echo ""

# symlink nginx config
if [ -d "/etc/nginx/conf.d/" ]; then
    ln -sf "$PREV_INSTALL_DIR"/config/nginx/cyberft.conf /etc/nginx/conf.d/
else
    if [ -d "/etc/nginx/site-enabled/" ]; then
        ln -sf "$PREV_INSTALL_DIR"/config/nginx/cyberft.conf /etc/nginx/site-enabled/
        service nginx restart
    else
        notice "Can't find nginx config folder" "warning"
        notice "Manualy copy $NGINX_CONF to nginx config folder" "warning"
    fi
fi

# symlink mysql config
ln -sf "$PREV_INSTALL_DIR"/config/mysql/debian.cnf /etc/mysql/
ln -sf "$PREV_INSTALL_DIR"/config/mysql/debian-start /etc/mysql/
ln -sf "$PREV_INSTALL_DIR"/config/mysql/my.cnf /etc/mysql/

# symlink stunnel config
ln -sf "$PREV_INSTALL_DIR"/config/stunnel/stomp-client-prod.conf /etc/stunnel/
ln -sf "$PREV_INSTALL_DIR"/config/stunnel/stomp-client-test.conf /etc/stunnel/

# add config to incrontab
if  [ -f /etc/incron_cyberft.conf ]; then
    rm -rf /etc/incron_cyberft.conf
fi

#incrontab config
echo "root" >> "/etc/incron.allow"
touch /etc/incron_cyberft.conf
chmod 0755 /etc/incron_cyberft.conf
echo "$PREV_INSTALL_DIR/app/temp/cert IN_CREATE $PREV_INSTALL_DIR/app/utils/crypto_pro_container_import.sh" >> /etc/incron_cyberft.conf
incrontab -r
incrontab /etc/incron_cyberft.conf

#cyberft-crypt
if [ ! -d /var/www/cyberft/app/src/bin ]; then
    mkdir -p /var/www/cyberft/app/src/bin
fi
cp -R $PREV_INSTALL_DIR/app/src/bin/cyberft-crypt /usr/bin
ln -sf /usr/bin/cyberft-crypt /var/www/cyberft/app/src/bin

$PREV_INSTALL_DIR/app/install.sh --update
echo ""
echo "**********************************************"
notice "Приложение было обновлено"
echo "**********************************************"
