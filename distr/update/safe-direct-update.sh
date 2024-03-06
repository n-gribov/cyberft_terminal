#!/bin/bash
set -e
ENV_FILE=`find $PREV_INSTALL_DIR -name ".env"`
if [ ! -f "$ENV_FILE" ]; then
    error "Can't find env file"
    exit 1
fi

cat $ENV_FILE | sed 's/ *= */=/' > $PREV_INSTALL_DIR/.env_old
. $PREV_INSTALL_DIR/.env_old

mysqldump -u$MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DBNAME > $PREV_INSTALL_DIR/cyberft.sql

TMP_DIR=`mktemp -d`

mkdir $TMP_DIR/backup
mv $PREV_INSTALL_DIR/* $TMP_DIR/backup
mv $TMP_DIR/backup $PREV_INSTALL_DIR

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

NGINX_USER="www-data"
read -e -p "Specify NGINX user [www-data]: " input
NGINX_USER="${input:-$NGINX_USER}"
echo "INSTALL_TYPE=direct" >> $PREV_INSTALL_DIR/app/.env
echo "NGINX_USER=$NGINX_USER" >> $PREV_INSTALL_DIR/app/.env

notice "Updating application"
$PREV_INSTALL_DIR/app/install.sh --data-file=dummy.json
$PREV_INSTALL_DIR/app/service.sh pause

. $PREV_INSTALL_DIR/app/.env

mysql -u$MYSQL_USERNAME -p$MYSQL_PASSWORD -e "drop database cyberft"
mysql -u$MYSQL_USERNAME -p$MYSQL_PASSWORD -e "create database cyberft default charset utf8"
mysql -u$MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DBNAME < $PREV_INSTALL_DIR/backup/cyberft.sql


$PREV_INSTALL_DIR/app/src/yii app/update

cp -R $PREV_INSTALL_DIR/backup/storage/* $PREV_INSTALL_DIR/app/storage/
chown -R $NGINX_USER:$NGINX_USER $PREV_INSTALL_DIR/app/storage

# адовый симлинк, убрать после создания миграции стораджа
ln -s $PREV_INSTALL_DIR/app/storage $PREV_INSTALL_DIR/storage

notice "configuring nginx";
NGINX_CONF_DEFAULT="$PREV_INSTALL_DIR/config/nginx/cyberft_direct.conf";
NGINX_CONF="$PREV_INSTALL_DIR/config/nginx/cyberft.conf";	

cat $NGINX_CONF_DEFAULT | sed 's|HOME_DIR|'"$PREV_INSTALL_DIR"'|' > $NGINX_CONF
chmod 777 $NGINX_CONF

if [ -d "/etc/nginx/conf.d/" ]; then
	rm -rf /etc/nginx/conf.d/cyberft.conf
	ln -s $NGINX_CONF /etc/nginx/conf.d/
else
	if [ -d "/etc/nginx/site-enabled/" ]; then
		ln -s $NGINX_CONF /etc/nginx/site-enabled/
	else
		notice "Can't find nginx config folder" "warning"
		notice "Manualy copy $NGINX_CONF to nginx config folder" "warning"
	fi
fi

$PREV_INSTALL_DIR/app/service.sh restart