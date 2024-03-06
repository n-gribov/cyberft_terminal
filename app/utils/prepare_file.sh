#!/bin/bash
APP_DIR='/var/www/cyberft/app/'
chmod -R 0755 $APP_DIR/import/edm/in/*
chown www-data:www-data $APP_DIR/import/edm/in/*
