#!/bin/bash

wp config set WP_DEBUG true --raw --allow-root --quiet
wp config set WP_DEBUG_DISPLAY true --raw --allow-root --quiet
wp config set WP_DEBUG_LOG true --raw --allow-root --quiet
