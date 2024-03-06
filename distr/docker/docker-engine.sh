#!/bin/bash

ABSOLUTE_PATH="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
source $ABSOLUTE_PATH/../inc/vars.sh
source $ABSOLUTE_PATH/../inc/functions.sh
######################################################################
## Installation of docker-machine                                   ##
######################################################################

if [ `uname -r | grep -E '3.2|3.3|3.4|3.5|3.6|3.7|3.8|3.9|3.10' | wc -l` -gt 0 ]; then
    notice "Linux kernel must be updated!" "warning";
	notice "Kernel version greater than 3.10 needed, your version is: `uname -r`" "warning";
        if `ask "Cancel installation?"`
	        then
		        exit 1;
	    fi
fi

if [ `which sudo | wc -l` -eq 0 ]; then
    notice "Устанавливаем sudo";
	apt-get -y install sudo
	    if [ $? -ne 0 ]; then
		    error "sudo не был установлен"
		    exit 1
	    fi
fi

if  [ `which docker | wc -l` -eq 0 ]; then
    notice "Устанавливаем docker";
    sudo apt-get install \
    apt-transport-https \
    ca-certificates \
    curl \
    gnupg2 \
    software-properties-common \
    python-software-properties ;
    curl -fsSL https://download.docker.com/linux/$(. /etc/os-release; echo "$ID")/gpg | sudo apt-key add - ;
    sudo add-apt-repository \
    "deb [arch=amd64] https://download.docker.com/linux/$(. /etc/os-release; echo "$ID") \
    $(lsb_release -cs) \
    stable" ;
        if [ $(. /etc/os-release; echo "$VERSION_ID")  -eq 7 ]; then
            sed -i 's/.*"deb-src".*/##"deb-src"' /etc/apt/sources.list
        fi

    sudo apt-get update ;
    sudo apt-get install docker-ce ;
  	   if [ $? -ne 0 ]; then
		   error "docker-engine не был установлен"
		   exit 1
	   fi
fi 