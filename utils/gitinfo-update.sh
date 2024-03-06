#!/bin/sh
DIR="$(dirname $(readlink -f $0))"

# Extracting git data 
targetFile="${DIR}/../app/gitinfo.json"
branch=`git rev-parse --abbrev-ref HEAD`
commit=`git rev-parse --short HEAD`
dateUpdate=`date '+%F %X'`

cat > $APP_DIR/$targetFile <<EOL
{
    "branch" : "$branch", 
    "commit" : "$commit", 
    "dateUpdate" : "$dateUpdate"
}
EOL
