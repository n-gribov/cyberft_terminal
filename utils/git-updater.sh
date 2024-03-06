#!/bin/bash
#remote update с сервера gitlab, на сервер в задании из .gitlab-ci
#НЕ трогать, тут велосипед
#@todo ref
set -e
DIR=$1
COMMIT=$2
BRANCH=$3
CRYPTO_PRO_KEY=$4
cd $DIR
dateUpdate=`date '+%F %X'`

echo "check docker"
docker info

echo "stash"
git stash 
echo "fetch"
git fetch
echo "checkout to $COMMIT"
git checkout -f $COMMIT

source distr/inc/vars.sh

cat > $APP_DIR/gitinfo.json <<EOL
{
    "branch" : "$BRANCH", 
    "commit" : "${COMMIT:0:7}", 
    "dateUpdate" : "$dateUpdate"
}
EOL

. $APP_DIR/.env


if [ -z `docker ps -q | grep $DOCKER_CONTAINER_ID` ]; then
    echo "starting docker $DOCKER_CONTAINER_ID"
	docker start $DOCKER_CONTAINER_ID
	docker exec -i $DOCKER_CONTAINER_ID /var/www/cyberft/app/service.sh start
	contisstop=true
fi

#работает только одной строкой ( && )
echo "Applying migrations to container $DOCKER_CONTAINER_ID"
docker exec -i $DOCKER_CONTAINER_ID /var/www/cyberft/app/background-jobs stop && docker exec -i $DOCKER_CONTAINER_ID /var/www/cyberft/app/src/yii resque/purge && docker exec -i $DOCKER_CONTAINER_ID /var/www/cyberft/app/src/yii app/update && docker exec -i $DOCKER_CONTAINER_ID /var/www/cyberft/app/background-jobs start && docker exec -i $DOCKER_CONTAINER_ID /var/www/cyberft/app/background-jobs status


if [[ $contisstop ]]; then
    docker exec -i $DOCKER_CONTAINER_ID /opt/cprocsp/sbin/amd64/cpconfig --license -set $4
    docker exec -i $DOCKER_CONTAINER_ID php /var/www/cyberft/app/utils/crypto_pro_containers.php
fi
