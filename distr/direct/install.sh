#!/bin/bash
NGINX_USER="www-data"
MYSQL_USERNAME="root"
MYSQL_PASSWORD="123qwe"
MYSQL_HOST="localhost"
MYSQL_DBNAME="cyberft"
ELASTIC_HTTP_ADDRESS="localhost:9200"
ELASTIC_INDEX="cyberft"
REDIS_HOSTNAME="localhost"
REDIS_PORT="6379"
REDIS_DATABASE="0"
REDIS_KEY="cyberft"
NGINX_CONF_DEFAULT="$INSTALL_DIR/config/nginx/cyberft_direct.conf";
NGINX_CONF="$INSTALL_DIR/config/nginx/cyberft.conf";

read -e -p "Specify NGINX user [www-data]: " input
NGINX_USER="${input:-$NGINX_USER}"
read -e -p "Specify MYSQL host [localhost]: " input 
MYSQL_HOST="${input:-$MYSQL_HOST}"
read -e -p "Specify MYSQL user [root]: " input
MYSQL_USERNAME="${input:-$MYSQL_USERNAME}"
read -e -p "Specify MYSQL password [123qwe]: " input
MYSQL_PASSWORD="${input:-$MYSQL_PASSWORD}"
read -e -p "Specify MYSQL database [cyberft]: " input
MYSQL_DBNAME="${input:-$MYSQL_DBNAME}"
read -e -p "Specify Elasticsearch host [localhost]: " input
ELASTIC_HTTP_ADDRESS="${input:-$ELASTIC_HTTP_ADDRESS}"
read -e -p "Specify Elasticsearch index [cyberft]: " input
ELASTIC_INDEX="${input:-$ELASTIC_INDEX}"
read -e -p "Specify Redis host [localhost]: " input
REDIS_HOSTNAME="${input:-$REDIS_HOSTNAME}"
read -e -p "Specify Redis port [6379]: " input
REDIS_PORT="${input:-$REDIS_PORT}"
read -e -p "Specify Redis database [0]: " input
REDIS_DATABASE="${input:-$REDIS_DATABASE}"
read -e -p "Specify Redis key [cyberft]: " input
REDIS_KEY="${input:-$REDIS_KEY}"

echo "INSTALL_TYPE=direct" >> $APP_DIR/.env
echo "NGINX_USER=$NGINX_USER" >> $APP_DIR/.env
echo "MYSQL_HOST=$MYSQL_HOST" >> $APP_DIR/.env
echo "MYSQL_USERNAME=$MYSQL_USERNAME" >> $APP_DIR/.env
echo "MYSQL_PASSWORD=$MYSQL_PASSWORD" >> $APP_DIR/.env
echo "MYSQL_DBNAME=$MYSQL_DBNAME" >> $APP_DIR/.env
echo "ELASTIC_HTTP_ADDRESS=$ELASTIC_HTTP_ADDRESS" >> $APP_DIR/.env
echo "ELASTIC_INDEX=$ELASTIC_INDEX" >> $APP_DIR/.env
echo "REDIS_HOSTNAME=$REDIS_HOSTNAME" >> $APP_DIR/.env
echo "REDIS_PORT=$REDIS_PORT" >> $APP_DIR/.env
echo "REDIS_DATABASE=$REDIS_DATABASE" >> $APP_DIR/.env
echo "REDIS_KEY=$REDIS_KEY" >> $APP_DIR/.env

source $APP_DIR/install.sh

#cyberft-crypt
cp -R $APP_DIR/src/bin/cyberft-crypt /usr/bin
mkdir -p /var/www/cyberft/app/src/bin
ln -sf /usr/bin/cyberft-crypt /var/www/cyberft/app/src/bin

# add config to incrontab
echo "root" >> "/etc/incron.allow"
touch /etc/incron_cyberft.conf
chmod 0755 /etc/incron_cyberft.conf
echo "$APP_DIR/temp/cert IN_CREATE $APP_DIR/utils/crypto_pro_container_import.sh" >> /etc/incron_cyberft.conf
incrontab /etc/incron_cyberft.conf

notice "configuring nginx";

cat $NGINX_CONF_DEFAULT | sed 's|HOME_DIR|'"$INSTALL_DIR"'|' > $NGINX_CONF
chmod 777 $NGINX_CONF

if [ -d "/etc/nginx/conf.d/" ]; then
	ln -s $NGINX_CONF /etc/nginx/conf.d/
else
	if [ -d "/etc/nginx/site-enabled/" ]; then
		ln -s $NGINX_CONF /etc/nginx/site-enabled/
		service nginx restart
	else
		notice "Can't find nginx config folder" "warning"
		notice "Manualy copy $NGINX_CONF to nginx config folder" "warning"
	fi
fi

notice "configuring stunnel4";	
sed -i 's/ENABLED=0/ENABLED=1/' /etc/default/stunnel4
if [ -d "/etc/stunnel/" ]; then
	cp $INSTALL_DIR/config/stunnel/* /etc/stunnel/
	service stunnel4 restart
else
	notice "Can't find stunnel4 config folder" "warning"
	notice "Manualy copy $INSTALL_DIR/config/stunnel/* to stunnel4 config folder" "warning"
fi
notice "example configs for other dependencies are in $INSTALL_DIR/config/ folder"
notice "for starting app use $APP_DIR/service.sh start"