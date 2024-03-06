#!/bin/bash

if [ `which more | wc -l` -eq 0 ]; then
	notice "Для продолжения работы необходимо установить more" "warning"
	exit 0
fi

more -d "$DISTR_DIR/license.txt"

if ! ask "Вы согласны с условиями лицензионного соглашения?" ; then
    exit 0
fi
