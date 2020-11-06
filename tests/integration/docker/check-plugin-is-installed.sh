#!/bin/bash

set -euo pipefail

wp plugin is-installed remove-noreferrer --user=1 --allow-root
