#!/bin/bash
set -e
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )/../"
. distr/inc/vars.sh

dockerImageDir=$1
dockerImage="$dockerImageDir/cyberft.$DOCKER_IMAGE_VERSION.tar.gz"
if [ ! -f $dockerImage ] ; then
    echo "Can't find $dockerImage";
	exit 1
fi

echo "composer install --no-dev"
cd app/src
/usr/bin/php"$PHP_VERSION_UNIX" /usr/bin/composer install --no-dev

cd $DIR

branch=$CI_BUILD_REF_NAME
commit=`git rev-parse --short HEAD`

dateUpdate=`date '+%F %X'`

cat > $APP_DIR/gitinfo.json <<EOL
{
    "branch" : "$branch",
    "commit" : "$commit",
    "dateUpdate" : "$dateUpdate"
}
EOL

echo "remove artifacts"
rm -rf utils
rm -rf $APP_DIR/test_params.json
rm -rf $APP_DIR/src/RoboFile.php
rm -rf $APP_DIR/src/codeception.yml
rm -rf $APP_DIR/src/tests
rm -rf $APP_DIR/src/composer.phar
rm -rf $APP_DIR/../distr/docker/image/archive/

echo "remove old bin utils"
rm -rf $APP_DIR/src/cyberft-crypt1
rm -rf $APP_DIR/src/cyberft-crypt-deb8

echo "remove VTB"
if [ -d $APP_DIR/src/addons/VTB ];then
     rm -rf $APP_DIR/src/addons/VTB
fi

echo "remove SBBOL"
if [ -d $APP_DIR/src/addons/SBBOL ];then
     rm -rf $APP_DIR/src/addons/SBBOL
fi

echo "remove sbbol2"
if [ -d $APP_DIR/src/addons/sbbol2 ];then
     rm -rf $APP_DIR/src/addons/sbbol2
fi

echo "remove raiffeisen"
if [ -d $APP_DIR/src/addons/raiffeisen ];then
     rm -rf $APP_DIR/src/addons/raiffeisen
fi

#clean test init data
echo "remove init data"
rm -rf "$APP_DIR/src/console/controllers/initdata"


echo "remove redundant controllers"
folder="$APP_DIR/src/console/controllers/*"
excess="AppController.php"
excess="$excess\|CryptoproController.php"
excess="$excess\|ElasticController.php"
excess="$excess\|ResqueController.php"
excess="$excess\|TerminalController.php"
excess="$excess\|UserController.php"
excess="$excess\|actions"

ls -d -1 $folder | grep -v $excess |  xargs rm -f
echo "Clean: $APP_DIR/src/console/controllers/*"

folder="$APP_DIR/src/addons/edm/console/*"
excess="DefaultController.php"
ls -d -1 $folder | grep -v $excess |  xargs rm -f
echo "Clean: $APP_DIR/src/addons/edm/console/*"

folder="$APP_DIR/src/addons/fileact/console/*"
excess="DefaultController.php"
ls -d -1 $folder | grep -v $excess |  xargs rm -f
echo "Clean: $APP_DIR/src/addons/fileact/console/*"

folder="$APP_DIR/src/addons/finzip/console/*"
excess="DefaultController.php"
ls -d -1 $folder | grep -v $excess |  xargs rm -f
echo "Clean: $APP_DIR/src/addons/finzip/console/*"

folder="$APP_DIR/src/addons/ISO20022/console/*"
excess="DefaultController.php"
ls -d -1 $folder | grep -v $excess |  xargs rm -f
echo "Clean: $APP_DIR/src/addons/ISO20022/console/*"

folder="$APP_DIR/src/addons/swiftfin/console/*"
excess="DefaultController.php"
ls -d -1 $folder | grep -v $excess |  xargs rm -f
echo "Clean: $APP_DIR/src/addons/ISO20022/console/*"

folder="$APP_DIR/src/common/modules/autobot/console/*"
excess="DefaultController.php"
ls -d -1 $folder | grep -v $excess |  xargs rm -f
echo "Clean: $APP_DIR/src/common/modules/autobot/console/*"

folder="$APP_DIR/src/common/modules/certManager/console/*"
excess="DefaultController.php"
ls -d -1 $folder | grep -v $excess |  xargs rm -f
echo "Clean: $APP_DIR/src/common/modules/certManager/console/*"

folder="$APP_DIR/src/common/modules/monitor/console/*"
excess="DefaultController.php"
ls -d -1 $folder | grep -v $excess |  xargs rm -f
echo "Clean: $APP_DIR/src/common/modules/monitor/console/*"

folder="$APP_DIR/src/common/modules/transport/console/*"
excess="DefaultController.php"
ls -d -1 $folder | grep -v $excess |  xargs rm -f
echo "Clean: $APP_DIR/src/common/modules/transport/console/*"



echo "Copy docker image"
cp "$dockerImage" "$DISTR_DIR/docker/image/cyberft.$DOCKER_IMAGE_VERSION.tar.gz"

APP_VERSION=`cat $APP_DIR/src/common/config/main.php |grep version|cut -f4 -d "'"`
tagsCount=`git tag -l v$APP_VERSION*|wc -l`
let tagNumber=tagsCount+1
tagId=v$APP_VERSION.$tagNumber

echo "current branch is $branch"
echo "placing git tag $tagId on commit $commit"
git tag -a $tagId $commit -m "build № $tagNumber"
git push git@gitlab-new.cyberplat.com:CyberFT/Terminal.git --tags

echo "remove .git"
find . | grep "\/\.git"| xargs rm -rf

echo "compressing build to archive"

distrName=cyberft-$tagId.tar.gz
shopt -s dotglob
tar -czf "/home/_builds/$distrName" *
shopt -u dotglob
echo "archive created to /home/_builds/$distrName"

echo "Creating checksum file"
checksumFileName="$distrName.sha256"
sha256sum /home/_builds/$distrName > "$checksumFileName"
sed -i 's=/home/_builds/==g' "$checksumFileName"

#FTP download.cyberft.ru
FTPLOGIN='Cyberft'
FTPPASS='HfpLdfNhb11'
FTPURL='download.cyberft.ru/Download/CyberFT'

echo "Загрузка дистрибутива на клиентское ftp"
curl --upload-file "{/home/_builds/$distrName,$checksumFileName}" ftp://$FTPLOGIN:$FTPPASS@$FTPURL/ \
    --verbose \
    --progress-bar \
    --write-out "Time: %{time_total}\nSize: %{size_upload}\nUpload speed: %{speed_upload}\n"
echo "Дистрибутив успешно загружен"
echo "Дистрибутив можно скачать по адресу $FTPURL/$distrName"
