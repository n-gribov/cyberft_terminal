#!/bin/bash
while ! [ `ps ax | grep php | wc -l` -eq 4 ] ; do service php7.0-fpm start && service stunnel4 start && service incron start && service incron start; sleep 3; /opt/connection.sh -h localhost -p 9000; done

