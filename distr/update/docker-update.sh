#!/bin/bash
set -e

#check prev .env & old container is running
if [ `docker ps -q | grep $DOCKER_CONTAINER_ID| wc -l` -eq 0 ]; then
    OLD_DOCKER_CONTAINER_NAME=`docker ps -a --filter "id=$DOCKER_CONTAINER_ID" --format="{{.Names}}"`
    if [ `docker ps -a --filter "id=$DOCKER_CONTAINER_ID" --format="{{.Names}}" | wc -l`  -eq 0 ] ; then
            echo ""
            echo "**********************************************"
            error "В вашем файле "$PREV_INSTALL_DIR"app/.env указан неверный id контейнера:"
            cat $PREV_INSTALL_DIR/app/.env | grep 'DOCKER_CONTAINER_ID'
            error "Измените данный id для продолжения процедуры обновления"
            echo "**********************************************"
            echo ""
            exit 1
    fi
    error "Внимание!"
    error "Контейнер $OLD_DOCKER_CONTAINER_NAME не запущен"
    error "Для продолжения обновления необходимо запустить контейнер $OLD_DOCKER_CONTAINER_NAME"
    if ask "Хотите запустить данный контейнер ?"; then
        docker start $OLD_DOCKER_CONTAINER_NAME
        notice "Контейнер $OLD_DOCKER_CONTAINER_NAME был запущен"
    else
        notice "Установка прервана"
        exit 1
    fi
fi

if ask "Выполнить бэкап базы данных ?"; then
notice "Выполняем backup"
mkdir -p $PREV_INSTALL_DIR/app/backup/latest
rm -rf $PREV_INSTALL_DIR/app/backup/latest/*
cp -R $APP_DIR/backup.sh $PREV_IMSTALL_DIR/app
docker exec -i $DOCKER_CONTAINER_ID /var/www/cyberft/app/backup.sh
cp -R $PREV_INSTALL_DIR/app/src $PREV_INSTALL_DIR/app/backup/latest
cp -R $PREV_INSTALL_DIR/distr $PREV_INSTALL_DIR/app/backup/latest
cp -R $PREV_INSTALL_DIR/config $PREV_INSTALL_DIR/app/backup/latest
    if [ -d "$PREV_INSTALL_DIR/app/src.bak" ]; then
        rm -rf $PREV_INSTALL_DIR/app/src.bak
    fi
fi

#check free space
echo ""
echo "**********************************************"
notice "Проверка свободного места:";

#проверка свободного места в каталоге установки.
DESTAPP=`docker inspect $DOCKER_CONTAINER_ID | grep '/app' | sed q | cut -d '"' -f 2 | cut -d ":" -f 1 `
USEDAPP=`df -h $DESTAPP | sed -n -e 2p | sed -r 's/ +/ /g' | cut '-d ' -f5 | sed 's/%//'`

#Проверка свободного места в каталоге /var/(по умолчанию), где установлен docker
DESTVAR='/var'
USEDVAR=`df -h $DESTVAR | sed -n -e 2p | sed -r 's/ +/ /g' | cut '-d ' -f5 | sed 's/%//'`
notice "Занятое пространство в каталоге $DESTAPP: $USEDAPP% "
notice "Занятое пространство в каталоге $DESTVAR: $USEDVAR% "
echo "**********************************************"
echo ""


if ask "Внимание! Не рекомендуется выполнять обновление ПО CyberFT при занятом пространстве в каталоге $DESTVAR более 90%. У вас занято $USEDVAR%! Выполнить обновление ?"; then
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
    #copy new src
    cp -R $APP_DIR/src $PREV_INSTALL_DIR/app
    #create utils directory for old versions
    if [ ! -d "$PREV_INSTALL_DIR/app/utils" ]; then
        mkdir $PREV_INSTALL_DIR/app/utils
    fi
    #remove old utils files
    if [ -f $PREV_INSTALL_DIR/app/crypto-pro-container-import.sh ]; then
        rm -rf $PREV_INSTALL_DIR/app/crypto-pro-container-import.sh
        rm -rf $PREV_INSTALL_DIR/app/crypto-pro-backup.sh
        rm -rf $PREV_INSTALL_DIR/app/crypto_pro_key_name.php
        rm -rf $PREV_INSTALL_DIR/app/crypto_pro_containers.php
        rm -rf $PREV_INSTALL_DIR/app/crypto_pro_license.php
    fi
    #copy scripts
    cp --force $APP_DIR/backup.sh $PREV_INSTALL_DIR/app
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
else
    error "Установка прервана"
    exit 0;
fi

#check if crypto-pro exist
docker exec -i $DOCKER_CONTAINER_ID /var/www/cyberft/app/utils/crypto_pro_backup.sh

#stop prev container
echo ""
echo "**********************************************"
notice "Останавливаем предыдущий контейнер"
#перенаправляем вывод, показывает какой контейнер остановлен по умолчанию
docker stop $DOCKER_CONTAINER_ID > /dev/null 2>&1
notice "Контейнер остановлен"
echo "**********************************************"
echo ""
PREV_DOCKER_CONTAINER_ID=$DOCKER_CONTAINER_ID

# copy updated distr scripts
echo ""
echo "**********************************************"
notice "Копируем служебные скрипты"
cp -R $DISTR_DIR $PREV_INSTALL_DIR
notice "Служебные скрипты из каталога $DISTR_DIR были скопированы в каталог $PREV_INSTALL_DIR"
echo "**********************************************"
echo ""

# copy updated configs
echo ""
echo "**********************************************"
notice "Копируем новые конфигурационные файлы"
#если был сделан внутренний бэкап, папка config пропадает, нужно её создать, иначе файлы мускуля не пробросятся
if [ ! -d $PREV_INSTALL_DIR/config ]; then
    mkdir $PREV_INSTALL_DIR/config
fi

#после этого уже копируем
if [ -d $PREV_INSTALL_DIR/config/nginx/openssl ]; then
    echo "Каталог nginx/openssl не будет перезаписан"
    cp $INSTALL_DIR/config/nginx/*.conf $PREV_INSTALL_DIR/config/nginx
else
    echo "Каталог nginx/openssl будет перезаписан"
    cp -R $INSTALL_DIR/config/nginx/ $PREV_INSTALL_DIR/config
fi

cp -R $INSTALL_DIR/config/mysql $PREV_INSTALL_DIR/config
cp -R $INSTALL_DIR/config/php5-fpm $PREV_INSTALL_DIR/config
cp -R $INSTALL_DIR/config/php7-fpm $PREV_INSTALL_DIR/config
cp -R $INSTALL_DIR/config/php7-cli $PREV_INSTALL_DIR/config
cp -R $INSTALL_DIR/config/stunnel $PREV_INSTALL_DIR/config
cp -R $INSTALL_DIR/config/elasticsearch $PREV_INSTALL_DIR/config
cp -R $INSTALL_DIR/config/incron $PREV_INSTALL_DIR/config
chmod -R 0755 $PREV_INSTALL_DIR/config/*
chmod -R 0755 $PREV_INSTALL_DIR/docker/*
chown -R root:root $PREV_INSTALL_DIR/docker/mysql/*
notice "Конфигурационные файлы из каталога $INSTALL_DIR/config скопированы в каталог $PREV_INSTALL_DIR""config"
echo "**********************************************"
echo ""

# Mandatory container update
$PREV_INSTALL_DIR/distr/docker/container.sh

#may be rename cont ?
DOCKER_CONTAINER_NAME=`docker ps -l --format="{{.Names}}"`
echo "**********************************************"
if ask "Хотите сменить имя контейнера ?"; then
    read -e -p "Укажите новое имя контейнера " DOCKER_CONTAINER_NEW_NAME;
    docker rename $DOCKER_CONTAINER_NAME $DOCKER_CONTAINER_NEW_NAME
    notice "Контейнер $DOCKER_CONTAINER_NAME был переименован в $DOCKER_CONTAINER_NEW_NAME"
    echo "**********************************************"
    echo ""
    echo ""
else
    notice "Наименование контейнера осталось без изменений - $DOCKER_CONTAINER_NAME"
    echo "**********************************************"
    echo ""
    echo ""
fi

DOCKER_CONTAINER_ID=`docker ps -lq`
sed -i -- "s/$PREV_DOCKER_CONTAINER_ID/$DOCKER_CONTAINER_ID/g" $PREV_INSTALL_DIR/app/.env
docker exec -i $DOCKER_CONTAINER_ID /var/www/cyberft/app/install.sh --update

# Add autorun to rc.local
AUTOSTART_SCRIPT="$PREV_INSTALL_DIR""/distr/docker/service.sh start";
cat $RC_LOCAL | sed '/service.sh/d' | sed '/docker start/d' | sed 's|"exit 0|'"\"exit0"'|' | sed 's|exit 0|'"$AUTOSTART_SCRIPT\nexit 0"'|' > $TMP_RC_LOCAL ;
mv $TMP_RC_LOCAL $RC_LOCAL ;
chmod +x $RC_LOCAL ;
notice "Cкрипт автозагрузки был добавлен в $RC_LOCAL";

LOCAL_IP=`ip -4 addr show eth0 | grep -oP '(?<=inet\s)\d+(\.\d+){3}'`
DOCKER_HTTPS_PORT=`docker inspect $DOCKER_CONTAINER_ID | grep '"HostPort"' | sed -e :a -e '$d;N;2,3ba' -e 'P;D' | cut -d '"' -f 4`
echo ""
echo "**********************************************"
notice "Приложение было обновлено"
notice "Для дальнейшей работы пройдите по ссылке:"
notice "https://$LOCAL_IP:$DOCKER_HTTPS_PORT/"
echo "**********************************************"