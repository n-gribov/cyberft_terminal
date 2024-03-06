#!/bin/bash
SAMBA_USER="cyberft-samba";
SAMBA_GROUP="cyberft-exchange";
SAMBA_CONF_DEFAULT="$INSTALL_DIR/config/samba/cyberft_default.conf";
SAMBA_CONF="$INSTALL_DIR/config/samba/cyberft.conf";

. $APP_DIR/.env

if [ `which samba|wc -l` -eq 0 ]; then
	apt-get install -f
	apt-get update;
	apt-get -y --force-yes install samba;
fi

useradd $SAMBA_USER > /dev/null 2>&1
notice "Input password for samba user $SAMBA_USER";
smbpasswd -a $SAMBA_USER
groupadd $SAMBA_GROUP > /dev/null 2>&1
usermod -a -G $SAMBA_GROUP $SAMBA_USER
usermod -a -G $SAMBA_GROUP $NGINX_USER
chown $NGINX_USER:$SAMBA_GROUP $APP_DIR/import/*
chown $NGINX_USER:$SAMBA_GROUP $APP_DIR/export/*

cat $SAMBA_CONF_DEFAULT | sed 's|STORAGE|'"$APP_DIR"'|' | sed 's|USER|'"$SAMBA_USER"'|' > $SAMBA_CONF
chmod 777 $SAMBA_CONF;

if [ `cat /etc/samba/smb.conf | grep "include = ${SAMBA_CONF}" | wc -l` -eq 0 ]; then
	echo "include = ${SAMBA_CONF}" >> /etc/samba/smb.conf
fi