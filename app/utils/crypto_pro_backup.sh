#!/bin/bash
set -e
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cryptotempcertdir=$DIR/../temp/crypto_keys
cryptoprodir=/var/opt/cprocsp/keys/www-data
cryptolicence=$cryptotempcertdir/license.txt
scancryptodir=`ls /var/opt/cprocsp/keys/www-data/ | wc -l`
licence=`php $DIR/crypto_pro_license.php`

source $DIR/../.env

if [ -d "$cryptotempcertdir" ]; then
    rm -rf $cryptotempcertdir
fi

if [ $scancryptodir -eq 0 ]; then
    echo "Ключи Крипто-Про не обнаружены"
    exit
    else
        mkdir $cryptotempcertdir
        cp -R $cryptoprodir/* $cryptotempcertdir
        chown -R www-data:www-data $cryptotempcertdir/*
        chmod -R 0755 $cryptotempcertdir/*
        touch $cryptolicence
        echo $licence > $cryptolicence
        echo ""
        echo "**********************************************"
        echo "Ключи Крипто-Про скопированы во временную директорию $cryptotempcertdir:"
        ls $cryptotempcertdir | grep -v license.txt
        echo ""
        echo "Лицензия Крипто-Про была скопирована"
        cat $cryptotempcertdir/license.txt
        echo "**********************************************"
        echo ""
fi

