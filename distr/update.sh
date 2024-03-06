#!/bin/bash
ABSOLUTE_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ABSOLUTE_PATH"

# Пишем в лог-файл
if [ -f update.log ]; then
    rm -f update.log
fi

source inc/vars.sh
source inc/functions.sh
source inc/license.sh

exec > >(tee -i update.log)

checkroot
echo ""
echo ""
echo "**********************************************"
echo "                                             *"
notice "Скрипт обновления ПО CyberFT                 *"
echo "                                             *"
echo "**********************************************"
echo ""
echo ""

read -r -e -p "Укажите директорию, в которую установлен текущий терминал CyberFT: " PREV_INSTALL_DIR

if [ ! -f  "$PREV_INSTALL_DIR/app/.env" ]; then
    error "Cannot detect application in specified directory. Applying safe update scenario."
	if ask "Did you use Docker during install?"; then
		source update/safe-update.sh
	    else
		source update/safe-direct-update.sh
    fi
    else
	. "$PREV_INSTALL_DIR/app/.env"

	    if [ "$INSTALL_TYPE" == "docker" ]; then
		    source update/docker-update.sh
	    else
	        source inc/requirements.sh
		    source update/direct-update.sh
	fi
fi