#!/bin/bash
ABSOLUTE_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
INSTALL_DIR="$(readlink -f $ABSOLUTE_PATH/../../)"

DISTR_DIR="$INSTALL_DIR/distr"
APP_DIR="$INSTALL_DIR/app"
CONFIG_DIR="$INSTALL_DIR/config"

DOCKER_DATA_DIR="$INSTALL_DIR/docker"
DOCKER_IMAGE_VERSION="v4"

RC_LOCAL="/etc/rc.local"
TMP_RC_LOCAL="/tmp/rc.local"

if [ -f "$ABSOLUTE_PATH/vars-local.sh" ]; then
    source $ABSOLUTE_PATH/vars-local.sh
fi

PHP_VERSION='7.3.5'
NGINX_VERSION='nginx version: nginx/1.15.12'
REDIS_VERSION='3.2.11'
JAVA_VERSION='1.8'
MYSQL_VERSION='5.5.59'

PHP_VERSION_UNIX='7.3'