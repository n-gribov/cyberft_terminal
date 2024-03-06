#!/bin/bash
ABS_DIR=$(dirname "$(readlink -f "$0")");
source "$ABS_DIR/../inc/functions.sh"
source "$ABS_DIR/../inc/vars.sh"
source "$ABS_DIR/../../app/.env"
tmppid="$ABS_DIR/tmppidfilelist"
processlist=$ABS_DIR/../../app/processlist
debugfile=cyberft-diag.tar
commands=(start stop status diag);
log_files="app-debug.log app-error.log app-edm.log app-iso20022.log app-regularjobs.log app-stomp.log app-system.log dispatcher-error.log dispatcher.log dispatcher-stomp.log error.log"
services="php-fpm$PHP_VERSION_UNIX mysqld nginx redis-server stunnel4"


for i in $commands; do
if [[ $(docker ps -qa | grep -c "$DOCKER_CONTAINER_ID") -eq 0 ]]; then
    error "Контейнер $DOCKER_CONTAINER_ID отсутствует в системе"
    exit 1;
elif [[ $(docker ps -q | grep -c "$DOCKER_CONTAINER_ID") -eq 0 && $1 != 'stop' && $1 != 'status' && $1 != 'status--diag' && $1 == 'start' ]]; then
    docker start "$DOCKER_CONTAINER_ID"
fi
done

start() {
    docker exec -i "$DOCKER_CONTAINER_ID" /var/www/cyberft/app/service.sh start
    docker exec -i "$DOCKER_CONTAINER_ID" /var/www/cyberft/app/src/yii app/update
    notice "Сервисы запущены"
}

stop() {
     if [[ $(docker ps -q | grep -c "$DOCKER_CONTAINER_ID") -eq 0 ]];then
     notice "Команда не требуется. Контейнер уже остановлен"
     else
     docker exec -i "$DOCKER_CONTAINER_ID" /var/www/cyberft/app/service.sh stop
     notice "Сервисы оставновлены"
     fi
}

diag() {
     if [[ $(docker ps -q | grep -c "$DOCKER_CONTAINER_ID") -eq 0 ]];then
        error "Контейнер был ранее остановлен, нужно запустить сервисы командой start"
     else
        if [ -d  "$ABS_DIR/../../app/diag"  ];then
            rm -rf "$ABS_DIR/../../app/diag"
        fi
        mkdir "$ABS_DIR/../../app/diag"
        touch "$ABS_DIR/../../app/diag/diag.log"
        for file in $log_files;
            do
                cp "$ABS_DIR/../../app/logs/$file" "$ABS_DIR/../../app/diag/" > /dev/null 2>&1 &
            done
        if [ -f  "$ABS_DIR/../../app/gitinfo.json" ]; then
            cp "$ABS_DIR/../../app/gitinfo.json" "$ABS_DIR/../../app/diag"
        fi
        if [ -f "$tmppid" ];then
            rm "$tmppid"
        fi
        for service_pids in $services;
            do
                pidof "$service_pids" >> "$tmppid"
        done
        listpid=$(cat "$tmppid" | tr '\n' ' ')
        for pids in $listpid;
            do
                top -b -p "$pids" -n 1 >> "$processlist"
                echo "" >> "$processlist"
                echo "______________" >> "$processlist"
                echo "" >> "$processlist"
            done
        cp -R "$processlist" "$ABS_DIR/../../app/diag"
        rm "$processlist"
        rm "$tmppid"
        exec > >(tee -i "$ABS_DIR/../../app/diag/diag.log")
        docker exec -i "$DOCKER_CONTAINER_ID" /var/www/cyberft/app/service.sh status
        docker exec -i "$DOCKER_CONTAINER_ID" redis-cli KEYS '*'
        shopt -s dotglob
        tar -czf "$ABS_DIR/../../app/$debugfile" "$ABS_DIR/../../app/diag" &> /dev/null
        rm -rf "$ABS_DIR/../../app/diag"
        notice "archive created to $ABS_DIR/../../app/$debugfile"
     fi
}

status()  {
     if [[ $(docker ps -q | grep -c "$DOCKER_CONTAINER_ID") -eq 0 ]];then
     error "Контейнер был ранее остановлен, нужно запустить сервисы командой start"
     else
     docker exec -i "$DOCKER_CONTAINER_ID" /var/www/cyberft/app/service.sh status
     fi
}

case "$1" in
  status)
    status
    ;;
  diag)
    diag
    ;;
  start)
    start
    ;;
  stop)
    stop
    ;;
  restart)
    stop
    start
    ;;
  *)
    notice "Usage: $0 {start|restart|status|diag|stop}"
esac