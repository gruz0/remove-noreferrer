#!/bin/bash
set -euo pipefail

echo "Waiting for database..."

while ! nc -z 127.0.0.1 3307; do
	echo "Database is not started yet..."
	sleep 1
done

while ! mysqladmin ping --host=127.0.0.1 --port=3307 --user=wordpress -ppassword; do
	echo "Database is not ready to accept connection yet..."
	sleep 1
done

echo "Database started"
