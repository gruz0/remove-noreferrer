#!/bin/bash
set -euo pipefail

# FIXME: Переименовать файл в bin/setup

composer install -o --no-progress

cp bin/pre-commit .git/hooks/pre-commit
