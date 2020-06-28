#!/bin/bash
set -euo pipefail

curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
mv wp-cli.phar /usr/local/bin/wp

wp config set WP_DEBUG true --raw --allow-root
wp config set WP_DEBUG_DISPLAY true --raw --allow-root
wp config set WP_DEBUG_LOG true --raw --allow-root

chown -R www-data:www-data /var/www/html/
