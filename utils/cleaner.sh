#!/bin/bash
DIR="$(dirname $(readlink -f $0))"
INSTALL_DIR="$(readlink -f $DIR/../)"
APP_DIR="$INSTALL_DIR/app"

sleep 4

if [ -f "$APP_DIR/.env" ]; then
    . "$APP_DIR/.env"
    if [ ! -z DOCKER_CONTAINER_ID ]; then
        docker rm -f $DOCKER_CONTAINER_ID > /dev/null
    fi
fi

sudo rm -rf $APP_DIR/export
sudo rm -rf $APP_DIR/cftcp
sudo rm -rf $APP_DIR/backup
sudo rm -rf $APP_DIR/import
sudo rm -rf $APP_DIR/storage
sudo rm -rf $APP_DIR/logs
sudo rm -rf $APP_DIR/.env
sudo rm -rf $INSTALL_DIR/distr/install.log
sudo rm -rf $INSTALL_DIR/docker