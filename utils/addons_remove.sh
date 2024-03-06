#!/usr/bin/env bash
set -e
DIR=$1
VTB=$2
SBBOL=$3
sbbol2=$4
raiffeisen=$5
cd $DIR

if [ "$2" == "VTB" ]; then
    echo "VTB"
    rm -rf ./app/src/addons/$VTB
fi

if [ "$3" == "SBBOL" ]; then
    echo "SBBOL"
    rm -rf ./app/src/addons/$SBBOL
fi

if [ "$4" == "sbbol2" ]; then
    echo "sbbol2"
    rm -rf ./app/src/addons/$sbbol2
fi

if [ "$5" == "raiffeisen" ]; then
    echo "raiffeisen"
    rm -rf ./app/src/addons/$raiffeisen
fi
