#!/bin/bash
SCRIPT_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source $SCRIPT_PATH/../inc/vars.sh
source $SCRIPT_PATH/../inc/functions.sh

if [ -f "$APP_DIR/.env" ]; then
    # Warning! Docker image id will be redefined after this include
    source "$APP_DIR/.env"

    if [ ! -z DOCKER_CONTAINER_ID ]; then
        PREV_DOCKER_CONTAINER_ID=$DOCKER_CONTAINER_ID
        if [ ! -z `docker ps -q | grep $DOCKER_CONTAINER_ID` ]; then
            notice "Останавливаем предыдущий контейнер"
            docker stop $DOCKER_CONTAINER_ID
        fi
    fi
fi

if [ `docker images -q cyberft:$DOCKER_IMAGE_VERSION | wc -l` -eq  0 ]; then
    if [ ! -f $SCRIPT_PATH/image/cyberft.$DOCKER_IMAGE_VERSION.tar.gz ]; then
        notice "Загружаем docker image";
        wget -q http://cyberft-ci.cyberplat.com:8080/images/cyberft.$DOCKER_IMAGE_VERSION.tar.gz -O $SCRIPT_PATH/image/cyberft.$DOCKER_IMAGE_VERSION.tar.gz ;
        if [[ $? -ne 0 ]]; then
		    error "Невозможно загрузить image";
		    exit 1
		fi
    fi
    notice "Устанавливаем docker image";
    docker load < $SCRIPT_PATH/image/cyberft.$DOCKER_IMAGE_VERSION.tar.gz ;
    if [[ $? -ne 0 ]] ; then
        error "Невозможно установить docker image" ;
        exit 1
    fi
    notice "docker image загружен";
fi

#удаляем image
rm -rf $SCRIPT_PATH/image/cyberft.$DOCKER_IMAGE_VERSION.tar.gz

DOCKER_IMAGE_ID=`docker images -q cyberft:$DOCKER_IMAGE_VERSION`
MYSQL_DIR="$INSTALL_DIR/docker/mysql";
REDIS_DIR="$INSTALL_DIR/docker/redis";
NGINX_LOGS_DIR="$INSTALL_DIR/app/logs";
CRYPTO_PRO_DIR="$INSTALL_DIR/docker/crypto-pro";
DOCKER_PORT_HTTP='80';
DOCKER_PORT_HTTPS='443';

if [ `which netstat | wc -l` -eq 0 ]; then
    notice "Устанавливаем netstat";
	apt-get -y install netstat
	    if [ $? -ne 0 ]; then
		    error "netstat не был установлен"
		    exit 1
	    fi
fi

echo ""
echo "**********************************************"
notice "Установка нового контейнера"

#check tcp ports
if [ `netstat -pntl | grep -E ":($DOCKER_PORT_HTTP|$DOCKER_PORT_HTTPS)\b" | wc -l` -gt 0 ]; then
    notice "У вас закрыты порты: ${DOCKER_PORT_HTTP} и ${DOCKER_PORT_HTTPS}!" "warning"
    notice "Для продолжения необходимо открыть порты, либо использовать другие" ;
    sudo netstat -tulpn | grep ":80"
    sudo netstat -tulpn | grep ":443"
    if ask "Укажите новые порты?"; then
        read -p "Порт для доступа к HTTP: " DOCKER_PORT_HTTP;
        read -p "Порт для доступа к HTTPS: " DOCKER_PORT_HTTPS;
    else
        notice "Установка прервана." "warning";
        exit 1;
    fi
fi



rm -f $APP_DIR/.dockerinfo

    docker run -itd \
    -p $DOCKER_PORT_HTTP:80 \
    -p $DOCKER_PORT_HTTPS:443 \
    -v $INSTALL_DIR/config/mysql:/etc/mysql:ro \
    -v $INSTALL_DIR/config/nginx/cyberft_default.conf:/etc/nginx/conf.d/cyberft.conf:ro \
    -v $INSTALL_DIR/config/nginx/openssl:/var/www/cyberft/openssl:ro \
    -v $INSTALL_DIR/config/stunnel:/etc/stunnel:ro \
    -v $INSTALL_DIR/config/elasticsearch/elasticsearch.yml:/etc/elasticsearch/elasticsearch.yml:ro \
    -v $INSTALL_DIR/config/php7-fpm/php.ini:/etc/php/7.3/fpm/php.ini:ro \
    -v $INSTALL_DIR/config/php7-fpm/www.conf:/etc/php/7.3/fpm/pool.d/www.conf:ro \
    -v $INSTALL_DIR/config/php7-cli/php.ini:/etc/php/7.3/cli/php.ini:ro \
    -v $INSTALL_DIR/config/incron/incron_cyberft.conf:/etc/incron_cyberft.conf:ro \
    -v $INSTALL_DIR/app:/var/www/cyberft/app \
    -v $MYSQL_DIR:/var/lib/mysql \
    -v $REDIS_DIR:/var/lib/redis \
    -v $CRYPTO_PRO_DIR:/var/opt/cprocsp/keys/www-data \
    -v /etc/localtime:/etc/localtime:ro \
    -v /etc/timezone:/etc/timezone:ro \
    --hostname=cyberft \
    $DOCKER_IMAGE_ID \
    ;

#check that all fine
if [ $? -eq 0 ]; then
    DOCKER_CONTAINER_NAME=`docker ps -l --format="{{.Names}}"`
    notice "Установлен контейнер $DOCKER_CONTAINER_NAME"
    echo "**********************************************"
    echo ""
else
    #if no - start old container and remove error contaner
    error "Ошибка установки контейнера"
    error "Откатываем изменения"
    cp -R $INSTALL_DIR/app/backup/latest/config/ $INSTALL_DIR
    cp -R $INSTALL_DIR/app/backup/latest/distr/ $INSTALL_DIR
    cp -R $INSTALL_DIR/app/backup/latest/src/ $INSTALL_DIR/app

    notice "Запуск предыдущего контейнера:"
    docker start $PREV_DOCKER_CONTAINER_ID > /dev/null 2>&1
    docker exec -i $PREV_DOCKER_CONTAINER_ID /var/www/cyberft/app/service.sh start
    docker rm -f `docker ps -lq` > /dev/null 2>&1
    error "Исправьте ошибки перед повторным запуском"
    echo ""
    exit 1;
fi

#remove old container
if [ ! -z $PREV_DOCKER_CONTAINER_ID ] ; then
    echo ""
    echo "**********************************************"
    notice "Удаляем старый контейнер"
    docker rm -f $PREV_DOCKER_CONTAINER_ID > /dev/null 2>&1
    notice "Старый контейнер был удален"
    echo "**********************************************"
    echo ""
    echo ""
fi

#it's need for the following scripts
DOCKER_CONTAINER_ID=`docker ps -l --format="{{.ID}}"`
