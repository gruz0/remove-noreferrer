#!/bin/bash

set -euo pipefail

wp plugin uninstall remove-noreferrer --user=1 --allow-root
