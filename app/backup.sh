#!/bin/bash
ABS_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd $ABS_PATH
timestamp=`date '+%Y%m%d%H%M'`

# @todo Pass envfile as argument
. $ABS_PATH/.env 

BACKUP_DIR=backup

if [ ! -d $BACKUP_DIR ]; then
    mkdir $BACKUP_DIR
fi


if [ -f ./service.sh ]; then 
    ./service.sh pause
else 
    if [ `ps ax | grep '[b]in/dispatcher' | wc -l` -gt 0 ]; then
        kill -9 $(ps ax | grep '[b]in/dispatcher' | awk '{print $1}')
    fi

    if [ `ps ax | grep '[r]esque' | wc -l` -gt 0 ]; then
        kill -9 $(ps ax | grep '[r]esque' | awk '{print $1}')
    fi
fi

if [ `ps ax | grep mysql | wc -l` -eq 1 ]; then
    service mysql start
fi

# Making MySQL dump file
echo "Копируем БД"
mysqldump -u$MYSQL_USERNAME -p$MYSQL_PASSWORD $MYSQL_DBNAME > $BACKUP_DIR/$timestamp.$MYSQL_DBNAME.sql
tar -cf $BACKUP_DIR/$timestamp.cyberft.tar -C $BACKUP_DIR $timestamp.$MYSQL_DBNAME.sql 
rm -f $BACKUP_DIR/$timestamp.$MYSQL_DBNAME.sql

# Compressing complete backup archive
echo "Сжимаем архив"
gzip -c $BACKUP_DIR/$timestamp.cyberft.tar > $BACKUP_DIR/$timestamp.cyberft.tar.gz
rm -f $BACKUP_DIR/$timestamp.cyberft.tar