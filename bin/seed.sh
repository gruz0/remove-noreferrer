#!/bin/bash
set -euo pipefail

docker-compose exec wordpress /docker/prepare.sh localhost:8000

open http://localhost:8000/wp-admin/
