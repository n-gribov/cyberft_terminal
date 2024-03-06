#!/bin/bash
DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source $DIR/../.env
tempcertdir=$DIR/../temp/cert
cryptoprodir=/var/opt/cprocsp/keys/www-data
HOMECERTDIR="/home/certs"

# Параметры файла для передачи статуса обработки сертификата
status_file=$DIR/../temp/cert-load-status.json
echo "status_file is $status_file"

# Файл для дебага
upload_status_file=$DIR/../temp/upload-status.log
echo "upload status file is $upload_status_file"

#Записываем всё происходящее в дебаг-файл
exec > >(tee -i $upload_status_file)

if [ -f $status_file ];then
    rm -rf $status_file
fi

if [ -f $upload_status_file ]; then
    rm -rf $upload_status_file
fi

function failend()
{
    echo $status_content > $status_file
    rm -rf "$cryptoprodir/$container_dirname/$cert_filename"
    rm -rf $tempcertdir/*
    rm -rf $HOMECERTDIR
    if [ -d "$cryptoprodir/$container_dirname" ]; then
        rm -rf "$cryptoprodir/$container_dirname"
    fi
    exit
}

function successend()
{
    echo $status_content > $status_file
    rm -rf "$cryptoprodir/$container_dirname/$cert_filename"
    rm -rf $tempcertdir/*
    rm -rf $HOMECERTDIR
    exit
}

chmod -R 0755 $cryptoprodir
chown -R www-data:www-data $cryptoprodir

# Создание директории home/certs
mkdir $HOMECERTDIR
chmod 0775 -R $HOMECERTDIR
echo "tempcertdir is $tempcertdir"

# Получение файла сертификата
cert_file=`find $tempcertdir/* -type f -not -name '*.key' | head -n1`
echo "cert_file is $cert_file"

# Получение имени файла сертификата
cert_filename=`basename "$tempcertdir/$cert_file"`
echo "cert_filename is $cert_filename"

filename=`echo "$cert_filename"`
echo "filename is $filename"

# Перенос файла сертификата в home/certs
cp "$tempcertdir/$cert_filename" "$HOMECERTDIR/$cert_filename"
echo "cp into home/certs"

# Получение пути директории контейнера
container_dir=`find "$tempcertdir/" -type d | tail -n1`
echo "container dir is $container_dir"

# Получение имени директории контейнера
container_dirname=`basename "$container_dir"`
echo "container_dirname is $container_dirname"

# Получение содержимого файла с названием контейнера
name_key=`php $DIR/../utils/crypto_pro_key_name.php "$tempcertdir/$container_dirname/name.key"`
echo "name key is $name_key"

# Проверка контейнера на существование
container_search=`sudo -u www-data /opt/cprocsp/bin/amd64/csptest -keyset -enum_cont -verifycontext -fqcn | grep "$name_key"`
echo "container search - $container_search"

if [ `sudo -u www-data /opt/cprocsp/bin/amd64/csptest -keyset -enum_cont -verifycontext -fqcn | grep "$name_key" | wc -l` -eq  0 ]; then
    # Контейнер не существует
    # Создание директории контейнера в структуре КриптоПро
    mkdir "$cryptoprodir/$container_dirname"
    echo "no container"
    # Перенос файлов контейнера
    cp -rp $container_dir/* $cryptoprodir/$container_dirname

    # Права на директорию с контейнером
    chmod -R 777 $cryptoprodir/$container_dirname

    # Группа пользователя - www-data
    chown -R www-data:www-data $cryptoprodir/$container_dirname

    sudo -u www-data /opt/cprocsp/bin/amd64/certmgr -inst -file "$HOMECERTDIR/$filename" -cont "\\\\.\HDIMAGE\\$name_key" >> $upload_status_file
    command="sudo -u www-data /opt/cprocsp/bin/amd64/certmgr -inst -file '$HOMECERTDIR/$filename' -cont '\\\\.\HDIMAGE\\$name_key'"
    echo $command
    # Еще раз проверяем
        if [ `sudo -u www-data /opt/cprocsp/bin/amd64/csptest -keyset -enum_cont -verifycontext -fqcn | grep "$name_key" | wc -l` -eq  0 ]; then
            status_content='{"status": "error", "msg": "Ошибка добавления контейнера в хранилище крипто-про"}'
            echo $status_content
            failend
        fi

        if [ `sudo -u www-data /opt/cprocsp/bin/amd64/certmgr -list | grep "$container_dirname" | wc -l` -eq 0 ]; then
            #Пробуем добавить без сертификата
            sudo -u www-data /opt/cprocsp/bin/amd64/certmgr -inst -cont "\\\\.\HDIMAGE\\$name_key"

                if [ `sudo -u www-data /opt/cprocsp/bin/amd64/certmgr -list | grep "$container_dirname" | wc -l` -eq 0 ]; then
                status_content='{"status": "error", "msg": "Ошибка добавления сертификата к указанному контейнеру в хранилище крипто-про"}'
                echo $status_content
                failend
                fi
        fi

    # Добавление сертификата в терминал
    sudo -u www-data $DIR/../src/yii cryptopro/install-certificate-from-container "$name_key"

#@todo переделать, неверная логика
#        #Проверяем, что загрузилось в терминал
#        if [ `cat $upload_status_file | grep 'added' | wc -l` -eq 0 ]; then
#            status_content='{"status": "error", "msg": "Ошибка добавления ключа в терминал CyberFT"}'
#            echo $status_content
#            failend
#        fi

    status_content='{"status": "success", "msg": "Сертификат успешно добавлен"}'
    echo $status_content
    successend

    else
    echo "Контейнер уже существует"
    # Пробуем еще раз пробросить в базу, на случай, если в хранилище крипто-про контейнер есть, а у нас нет
    sudo -u www-data $DIR/../src/yii cryptopro/install-certificate-from-container "$name_key"
    # Контейнер уже существует
    status_content='{"status": "error", "msg": "Сертификат уже добавлен", "container": "'$container_dirname'"}'
    echo $status_content
    successend
fi