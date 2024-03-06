#!/bin/bash

for i in "$@"
do
case $i in
    -d=*|--data-file=*)
    DATA_FILE="${i#*=}"
    shift # past argument=value
    ;;
    *)
            # unknown option
    ;;
esac
done

ABSOLUTE_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source $ABSOLUTE_PATH/../inc/vars.sh
source $ABSOLUTE_PATH/../inc/functions.sh


source $DISTR_DIR/docker/docker-engine.sh
source $DISTR_DIR/docker/container.sh

notice "Writing application env file"
cat > $APP_DIR/.env <<EOL
INSTALL_TYPE=docker
DOCKER_IMAGE_ID=$DOCKER_IMAGE_ID
DOCKER_CONTAINER_ID=$DOCKER_CONTAINER_ID
NGINX_USER=www-data
MYSQL_USER=root
MYSQL_USERNAME=root
MYSQL_PASSWORD=123qwe
REDIS_USER=redis
EOL

source $APP_DIR/.env

# Need to wait until container services are all up
printf "Waiting for container"
until docker inspect --format="{{.State.Status}}" $DOCKER_CONTAINER_ID | grep "running"
do
    printf "."
    sleep 0.5
done

# @todo check docker run artifact

notice "Container is up and running"


docker exec -i $DOCKER_CONTAINER_ID /var/www/cyberft/app/install.sh --data-file=$DATA_FILE
