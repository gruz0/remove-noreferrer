#!/bin/bash
set -euo pipefail

# Prepare directories

WORDPRESS_DEV_DIR=./tests/wordpress-dev

rm -rf ${WORDPRESS_DEV_DIR:?}/*
mkdir -p $WORDPRESS_DEV_DIR

cd $WORDPRESS_DEV_DIR

# Set WP version to be installed

WP_VERSION=${1-latest}

if [ $WP_VERSION == 'latest' ]; then
	RELEASE_PATH='trunk'
else
	RELEASE_PATH="tags/$WP_VERSION"
fi

svn co -q http://develop.svn.wordpress.org/$RELEASE_PATH src

# Copy sample config

WP_CONFIG_PATH=src/wp-tests-config.php
cp src/wp-tests-config-sample.php $WP_CONFIG_PATH

# Default case for Linux sed, just use "-i"
# https://stackoverflow.com/a/51060063
sedi=(-i)
case "$(uname)" in
	# For macOS, use two parameters
	Darwin*) sedi=(-i "")
esac

# Update settings in WP config

sed "${sedi[@]}" -e "s/^define( 'DB_NAME'.*/define( 'DB_NAME', 'wordpress' );/g" $WP_CONFIG_PATH
sed "${sedi[@]}" -e "s/^define( 'DB_USER'.*/define( 'DB_USER', 'wordpress' );/g" $WP_CONFIG_PATH
sed "${sedi[@]}" -e "s/^define( 'DB_PASSWORD'.*/define( 'DB_PASSWORD', 'wordpress' );/g" $WP_CONFIG_PATH
sed "${sedi[@]}" -e "s/^define( 'DB_HOST'.*/define( 'DB_HOST', '127.0.0.1:3307' );/g" $WP_CONFIG_PATH
