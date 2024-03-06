#!/bin/bash
set -e
ENV_FILE=`find $PREV_INSTALL_DIR -name ".env"`
if [ ! -f "$ENV_FILE" ]; then
    error "Can't find env file"
    exit 1
fi

APP_SRC_PATH="$(cd "$(dirname "$ENV_FILE")" && pwd)"
DOCKER_IMAGE_VERSION=`cat $APP_SRC_PATH/dockerinfo`
DOCKER_CONTAINER_ID=`docker ps -a | grep "cyberft_$DOCKER_IMAGE_VERSION" | awk '{print $1}'`

if [ -z $DOCKER_CONTAINER_ID ]; then
	error "Can't find docker container cyberft_$DOCKER_IMAGE_VERSION"
	exit 1
fi

cat $ENV_FILE | sed 's/ *= */=/' > $PREV_INSTALL_DIR/.env_old
. $PREV_INSTALL_DIR/.env_old

docker exec -i $DOCKER_CONTAINER_ID mysqldump -u$MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DBNAME > $PREV_INSTALL_DIR/cyberft.sql

if [ `docker ps -q | grep $DOCKER_CONTAINER_ID | wc -l` -gt 0 ]; then
    notice "Stopping container $DOCKER_CONTAINER_ID"
    docker stop $DOCKER_CONTAINER_ID
fi

TMP_DIR=`mktemp -d`

mkdir $TMP_DIR/backup
mv $PREV_INSTALL_DIR/* $TMP_DIR/backup
mv $TMP_DIR/backup $PREV_INSTALL_DIR

BACKUP_DIR=$PREV_INSTALL_DIR/backup
