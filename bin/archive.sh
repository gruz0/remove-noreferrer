#!/bin/bash

set -euo pipefail

ARCHIVE="./archive"
RN_DIR="remove-noreferrer"
RN_PATH="$ARCHIVE/$RN_DIR"

mkdir -p $RN_PATH

rm -rf $ARCHIVE/*.zip $RN_PATH/*

cp -Rv remove-noreferrer.php admin base core frontend inc $RN_PATH > /dev/null

cd $ARCHIVE

zip -r remove-noreferrer.zip $RN_DIR/* > /dev/null

cd ..

rm -rf $RN_PATH
