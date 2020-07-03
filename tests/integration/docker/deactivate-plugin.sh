#!/bin/bash

set -euo pipefail

wp plugin deactivate remove-noreferrer --user=1 --allow-root
