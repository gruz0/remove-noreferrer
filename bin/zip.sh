#!/bin/bash

set -euo pipefail

PLUGIN_VERSION=${1-""}

if [ "${PLUGIN_VERSION}" == "" ]; then
	echo "You need to specify plugin's version as a first argument"
	exit 1
fi

DIST="./dist"
SLUG="remove-noreferrer"
TARGET_DIR="$DIST/$SLUG"

ARCHIVE_NAME="remove-noreferrer-${PLUGIN_VERSION}.zip"

rm -rf ${DIST:?}/
mkdir -p $TARGET_DIR

cp readme.txt $TARGET_DIR/
cp index.php $TARGET_DIR/
cp remove-noreferrer.php $TARGET_DIR/
cp -R admin $TARGET_DIR/
cp -R base $TARGET_DIR/
cp -R core $TARGET_DIR/
cp -R frontend $TARGET_DIR/
cp -R inc $TARGET_DIR/

cd $DIST

zip -r $ARCHIVE_NAME $SLUG > /dev/null

rm -rf $SLUG

declare -a FilesArray=(
	"readme.txt"
	"index.php"
	"remove-noreferrer.php"
	"admin/class-options-page.php"
	"admin/class-options-validator.php"
	"admin/class-plugin.php"
	"admin/index.php"
	"base/class-plugin.php"
	"base/index.php"
	"core/class-adapter.php"
	"core/class-options.php"
	"core/class-plugin.php"
	"core/index.php"
	"frontend/class-links-processor.php"
	"frontend/class-plugin.php"
	"frontend/index.php"
	"inc/autoloader.php"
	"inc/index.php"
)

if [[ $(unzip -Z1 $ARCHIVE_NAME | grep -c -E -v "\/$") -ne ${#FilesArray[@]} ]]; then
	echo "Unmatched archive's files count"
	exit 1
fi

for file in "${FilesArray[@]}"; do
	if [[ ! $(unzip -Z1 $ARCHIVE_NAME | grep $SLUG/$file) ]]; then
		echo "File $SLUG/${file} does not exist in the archive"
		exit 1
	fi
done
