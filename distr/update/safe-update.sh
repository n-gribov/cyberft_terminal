#!/bin/bash

SCRIPT_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"

source $SCRIPT_PATH/safe-backup.sh

BACKUP_DIR=$PREV_INSTALL_DIR/backup

notice "Copying app files"
cp -R $INSTALL_DIR/* $PREV_INSTALL_DIR

cat > $PREV_INSTALL_DIR/app/dummy.json <<EOL
{
    "terminalId" : "TESTRUM@AXXX",
    "noSecurityOfficers" : true,
    "adminEmail" : "admin@cyberft.com",
    "adminPassword" : "admin@cyberft.com"
}
EOL

notice "Updating application"
$PREV_INSTALL_DIR/distr/docker/install.sh --data-file=dummy.json
$PREV_INSTALL_DIR/distr/docker/service.sh pause

. $PREV_INSTALL_DIR/app/.env

docker exec -i $DOCKER_CONTAINER_ID mysql -uroot -p123qwe -e "drop database cyberft"
docker exec -i $DOCKER_CONTAINER_ID mysql -uroot -p123qwe -e "create database cyberft default charset utf8"
docker exec -i $DOCKER_CONTAINER_ID mysql -uroot -p123qwe cyberft < $PREV_INSTALL_DIR/backup/cyberft.sql
docker exec -i $DOCKER_CONTAINER_ID /var/www/cyberft/app/src/yii app/update

cp -R $BACKUP_DIR/storage $PREV_INSTALL_DIR/app/
docker exec -i $DOCKER_CONTAINER_ID chown -R www-data:www-data /var/www/cyberft/app/storage

# адовый симлинк, убрать после создания миграции стораджа
docker exec -i $DOCKER_CONTAINER_ID ln -s /var/www/cyberft/app/storage/ /var/www/cyberft/storage

$PREV_INSTALL_DIR/distr/docker/service.sh resume