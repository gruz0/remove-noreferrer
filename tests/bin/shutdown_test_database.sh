#!/bin/bash
set -eu

DOCKER_COMPOSE_CONFIG=./tests/docker-compose.yml

docker-compose -f $DOCKER_COMPOSE_CONFIG down
docker-compose -f $DOCKER_COMPOSE_CONFIG rm -f
