#!/bin/bash
#скрипт запускает только очистку предыдущего докер контейнера
#уже после подтягивания из git
#тут ничего удалять не нужно
DIR="$(dirname $(readlink -f $0))"
INSTALL_DIR="$(readlink -f $DIR/../)"
APP_DIR="$INSTALL_DIR/app"


docker exec -i `docker ps --format "{{.ID}}: {{.Ports}}" | grep '443' | cut -d ':' -f 1` /var/www/cyberft/app/utils/cleanup_jobs.sh
docker rm -f `docker ps --format "{{.ID}}: {{.Ports}}" | grep '443' | cut -d ':' -f 1` &> /dev/null
if [ $? -ne 0 ]; then
	echo "No containers to delete"
	exit 0
fi

if [ -f "$APP_DIR/.env" ]; then
    . "$APP_DIR/.env"
    if [ ! -z DOCKER_CONTAINER_ID ]; then
        docker rm -f $DOCKER_CONTAINER_ID > /dev/null
    fi
fi