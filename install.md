CyberFT Terminal
===

Установка на сервер
---

### Первичная установка

Образ лежит на файл-сервере (напр. smb://fileserver2/public/docker/images/cyberft.v3.0.tar.gz),
актуальная версия образа указана в скрипте `distr/inc/vars.sh`.
Он должен быть предварительно помещен в `distr/docker/image`.
Дальнейшая установка производится вызовом команды:

~~~
sudo ./distr/install.sh
~~~

### Предустановленные параметры
Для инсталляции в режиме предустановленных параметров необходимо создать файл в директории `app`

~~~
{
    "terminalId" : "EGORRUM@A001",
    "noSecurityOfficers" : true,
    "adminEmail" : "admin@cyberft.com",
    "adminPassword" : "admin@cyberft.com",
    "lsoEmail" : "lso@cyberft.com",
    "lsoPassword" : "lso@cyberft.com",
    "rsoEmail" : "rso@cyberft.com",
    "rsoPassword" : "rso@cyberft.com"
}
~~~

Далее нужно передать инсталлеру параметром имя файла:
~~~
sudo ./distr/install.sh --data-file=test.json
~~~
