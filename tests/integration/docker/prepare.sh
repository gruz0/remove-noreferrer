#!/bin/bash

set -uo pipefail

WP_HOST=$1

#
# Install clean WP
#
if ! wp core is-installed --allow-root; then
  wp core install \
    --allow-root \
    --url=$WP_HOST \
    --title=Example \
    --admin_user=admin \
    --admin_password=password \
    --admin_email=me@domain.tld \
    --quiet
fi

#
# Activate TwentySeventeen theme
#
wp theme install twentyseventeen --activate --allow-root --quiet

#
# Remove all widgets
#
wp widget reset --all --allow-root --quiet

#
# Create a Text widget
#
wp widget add text sidebar-1 1 \
  --allow-root \
  --title='Text Widget' \
  --text='<a href="https://domain.tld/" target="_blank" rel="noopener noreferrer">widget_text_link</a>' \
  --filter=true \
  --visual=true \
  --raw \
  --quiet

#
# Create a Custom HTML widget
#
wp widget add custom_html sidebar-1 2 \
  --allow-root \
  --title='Custom HTML Widget' \
  --content='<a href="https://domain.tld/" target="_blank" rel="noopener noreferrer">widget_custom_html_link</a>' \
  --quiet

#
# Delete all posts and pages
#
if [ "$(wp post list --allow-root --format=ids)" != "" ]; then
  post_ids=$(wp post list --post_type='page,post' --format=ids --allow-root)
  wp post delete $post_ids --force --allow-root --quiet
fi

#
# Create a post
#
wp post create \
  --allow-root \
  --post_type=post \
  --post_title='Post' \
  --post_content='<a href="https://domain.tld/" target="_blank" rel="noopener noreferrer">post_link</a>' \
  --post_status='publish' \
  --quiet

POST_ID=$(wp post list --allow-root --post_type='post' --format=ids)

#
# Create a comment to post
#
wp comment create \
  --allow-root \
  --comment_post_ID=$POST_ID \
  --comment_content='<a href="https://domain.tld/" target="_blank" rel="noopener noreferrer">post_comment_link</a>' \
  --comment_author='wp-cli' \
  --quiet

#
# Create a page
#
wp post create \
  --allow-root \
  --post_type=page \
  --post_title='Page' \
  --post_content='<a href="https://domain.tld/" target="_blank" rel="noopener noreferrer">page_link</a>' \
  --post_status='publish' \
  --quiet

PAGE_ID=$(wp post list --allow-root --post_type='page' --format=ids)

#
# Create a comment to page
#
wp comment create \
  --allow-root \
  --comment_post_ID=$PAGE_ID \
  --comment_content='<a href="https://domain.tld/" target="_blank" rel="noopener noreferrer">page_comment_link</a>' \
  --comment_author='wp-cli' \
  --quiet
