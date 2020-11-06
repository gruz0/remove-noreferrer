#!/bin/bash
set -euo pipefail

composer install -o --no-progress

cp bin/pre-commit .git/hooks/pre-commit
