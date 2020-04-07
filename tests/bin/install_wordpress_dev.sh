#!/bin/bash
set -eu

WORDPRESS_DEV_DIR=./tests/wordpress-dev

rm -rf $WORDPRESS_DEV_DIR/*
mkdir -p $WORDPRESS_DEV_DIR

cd $WORDPRESS_DEV_DIR

WP_CONFIG_PATH=trunk/wp-tests-config.php

svn co http://develop.svn.wordpress.org/trunk/
cp trunk/wp-tests-config-sample.php $WP_CONFIG_PATH

# Default case for Linux sed, just use "-i"
# https://stackoverflow.com/a/51060063
sedi=(-i)
case "$(uname)" in
  # For macOS, use two parameters
  Darwin*) sedi=(-i "")
esac

sed "${sedi[@]}" -e "s/^define( 'DB_NAME'.*/define( 'DB_NAME', 'wordpress' );/g" $WP_CONFIG_PATH
sed "${sedi[@]}" -e "s/^define( 'DB_USER'.*/define( 'DB_USER', 'wordpress' );/g" $WP_CONFIG_PATH
sed "${sedi[@]}" -e "s/^define( 'DB_PASSWORD'.*/define( 'DB_PASSWORD', 'wordpress' );/g" $WP_CONFIG_PATH
sed "${sedi[@]}" -e "s/^define( 'DB_HOST'.*/define( 'DB_HOST', '127.0.0.1:3307' );/g" $WP_CONFIG_PATH
