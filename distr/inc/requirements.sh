#!/bin/bash
if [ -n "$(type -t notice)" ] && [ "$(type -t notice)" = function ];
then :
else
    script_dir="$(dirname "$0")"
    source "$script_dir/functions.sh"
    source "$script_dir/vars.sh"
fi
notice "Проверка минимальных системных требований"
echo ""
echo ""
######################################################################
## Обязательные зависимости                                         ##
######################################################################

#nginx
if [ `dpkg --get-selections | grep nginx | wc -l` -eq 0 ]; then
    notice "Для продолжения работы необходимо установить: NGINX" "warning"
    errorStatus=1
fi

if [ `dpkg --get-selections | grep mysql | wc -l` -eq 0 ]; then
	notice "Для продолжения работы необходимо установить: MYSQL" "warning"
	errorStatus=1
fi

#redis
if [ `dpkg --get-selections | grep redis-server | wc -l` -eq 0 ]; then
	notice "Для продолжения работы необходимо установить: REDIS" "warning"
	errorStatus=1
fi

if [ `dpkg --get-selections | grep elasticsearch | wc -l` -eq 0 ]; then
	notice "Для продолжения работы необходимо установить: Elastic Search" "warning"
	errorStatus=1
fi

if [ `dpkg --get-selections | grep stunnel4 | wc -l` -eq 0 ]; then
	notice "Для продолжения работы необходимо установить: STUNNEL4" "warning"
	errorStatus=1
fi

if [ `dpkg --get-selections | grep incron | wc -l` -eq 0 ]; then
	notice "Для продолжения работы необходимо установить: INCRONTAB" "warning"
	errorStatus=1
fi


if [ `(update-java-alternatives -l | awk '{system(""$3"/bin/java -version 2>&1 | grep \"version\"")}') | grep -E $JAVA_VERSION | wc -l` -eq 0 ]; then
	notice "Для продолжения работы необходимо установить: JAVA OPENJDK версии не ниже 1.8" "warning"
	errorStatus=1
fi

if [ `which openssl | wc -l` -eq 0 ]; then
	notice "Для продолжения работы необходимо установить: OpenSSL" "warning"
	errorStatus=1
fi

if [ `dpkg --get-selections | grep php7.0-fpm | wc -l` -eq 0 ]; then
	notice "Для продолжения работы необходимо установить: php7.0-fpm" "warning"
	errorStatus=1
fi

php --version | grep $PHP_VERSION > /dev/null 2>&1
if [ $? -ne 0 ]; then
    notice "Для продолжения работы необходимо установить: PHP версии не ниже $PHP_VERSION" "warning"
    errorStatus=1
else
    ######################################################################
	## Обязательные зависимости PHP                                     ##
	######################################################################

    if [ `dpkg --get-selections | grep php | grep mcrypt | wc -l` -eq 0 ]; then
		notice "Для продолжения работы необходимо установить: php5-mcrypt" "warning"
		errorStatus=1
	fi

	if [ `dpkg --get-selections | grep php | grep mysql | wc -l` -eq 0 ]; then
		notice "Для продолжения работы необходимо установить: php5-mysql" "warning"
		errorStatus=1
	fi

#	if [ `dpkg --get-selections | grep php | grep apc | wc -l` -eq 0 ]; then
#		notice "Для продолжения работы необходимо установить: php-apc" "warning"
#		errorStatus=1
#	fi

	if [ `dpkg --get-selections | grep php | grep curl | wc -l` -eq 0 ]; then
		notice "Для продолжения работы необходимо установить: php5-curl" "warning"
		errorStatus=1
	fi
fi

if [ `dpkg --get-selections | grep uuid | wc -l` -eq 0 ]; then
	notice "Для продолжения работы необходимо установить: UUID" "warning"
	errorStatus=1
fi

if [[ $errorStatus -eq 1 ]] ;  then
	notice "Установка невозможна! Исправьте все ошибки и запустите скрипт установки еще раз." "warning"
	exit 1
fi

######################################################################
## Не обязательные зависимости, Проверка версий                     ##
######################################################################

#nginx
 #To fix this, redirect Nginx’s output from stderr to stdout and then pipe to grep:
if [ `nginx -v 2>&1 | grep "$NGINX_VERSION" | wc -l`  -eq 0 ]; then
    echo "**********************************************"
	notice "Ваша версия nginx :"
	echo `nginx -v 2>&1`
	which nginx
	echo ""
	notice "Отличается от рекомендуемой: "
	echo "$NGINX_VERSION"
	echo ""
	notice "Коррректная работоспособность приложения не гарантиируется" "warning"
    echo ""
	    if ask "Продолжить ?" ; then
	        warningStatus=0
                else
                    notice "Установка прервана. Для корректной работы установите $NGINX_VERSION" "warning"
                    notice "http://nginx.org/ru/linux_packages.html" "warning"
                    exit 1;
        fi
fi

#redis-server
if [ `redis-server -v | grep "$REDIS_VERSION" | wc -l`  -eq 0 ]; then
    echo "**********************************************"
	notice "Ваша версия redis-server :"
	echo `redis-server -v`
	which redis-server
	echo ""
	notice "Отличается от рекомендуемой: "
	echo "$REDIS_VERSION"
	echo ""
	notice "Коррректная работоспособность приложения не гарантиируется" "warning"
    echo ""
	    if ask "Продолжить ?" ; then
	        warningStatus=0
                else
                    notice "Установка прервана. Для корректной работы установите $REDIS_VERSION" "warning"
                    notice "https://redis.io/topics/quickstart" "warning"
                    exit 1;
        fi
fi

#mysql
if [ `mysql --version | grep "$MYSQL_VERSION" | wc -l`  -eq 0 ]; then
    echo "**********************************************"
	notice "Ваша версия mysql :"
	echo `mysql --version`
	which mysql
	echo ""
	notice "Отличается от рекомендуемой: "
	echo "$MYSQL_VERSION"
	echo ""
	notice "Коррректная работоспособность приложения не гарантиируется" "warning"
    echo ""
	    if ask "Продолжить ?" ; then
	        warningStatus=0
                else
                    notice "Установка прервана. Для корректной работы установите $MYSQL_VERSION" "warning"
                    exit 1;
        fi
fi

#crypro-pro csp
if ask "Будет ли использоваться серверное подписание КриптоПро?"
    then
          if [ `sudo -u www-data env PATH="$PATH:$(ls -d /opt/cprocsp/{s,}bin/*|tr '\n' ':')" cpconfig| wc -l` -eq 0 ]; then
               echo "Внимание! Для поддержки подписания КриптоПро требуется установка КриптоПро 3.9 CSP"
               warningStatus=1
          fi

          if [[ "$warningStatus" -eq 1 ]]; then
               notice "Внимание! У вас отсутствуют важные компоненты необходимые для корректной работы терминала." "warning"
          fi
fi
