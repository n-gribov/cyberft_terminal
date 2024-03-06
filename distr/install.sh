#!/bin/bash
ABSOLUTE_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$ABSOLUTE_PATH"

#Пишем в лог-файл
if [ -f install.log ]; then 
    rm install.log
fi

source inc/vars.sh
source inc/functions.sh
source inc/license.sh

exec > >(tee -i install.log)

checkroot

if ask "Вы хотите использовать docker?"; then
    source "$DISTR_DIR/docker/install.sh"
else
    source "inc/requirements.sh"
    source "$DISTR_DIR/direct/install.sh"
fi

. "$APP_DIR/.env"

if [ "$INSTALL_TYPE" == "docker" ]; then
	AUTORUN="$DISTR_DIR/docker/service.sh start"
else
	AUTORUN="$APP_DIR/service.sh start"
fi

if [ "$(cat /etc/rc.local | grep -c "${AUTORUN}")" -eq 0 ]; then
	cat /etc/rc.local | sed 's|"exit 0|'"\"exit0"'|' | sed 's|exit 0|'"$AUTORUN\nexit 0"'|' > /tmp/rc.local
	mv /tmp/rc.local /etc/rc.local
	chmod +x /etc/rc.local
	notice "Autorun script added to rc.local";
fi

if [ `(which samba) | wc -l` -eq 0 ]; then
    if ask "Do you want to install samba?"; then
    notice "installing samba"
    source "$DISTR_DIR/direct/samba.sh"
    fi
fi
