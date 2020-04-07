.PHONY: help dockerize shell install_linters lint release install_wordpress_dev dockerize_test_database shutdown_test_database

help:
	@echo 'Available targets:'
	@echo '  make dockerize'
	@echo '  make shell'
	@echo '  make install_linters'
	@echo '  make lint'
	@echo '  make release'
	@echo '  make install_wordpress_dev'
	@echo '  make dockerize_test_database'
	@echo '  make shutdown_test_database'

dockerize:
	docker-compose down
	docker-compose up --build

shell:
	docker-compose exec wordpress bash

install_linters:
	bin/install_linters.sh

lint:
	bin/lint.sh

test:
	composer test

release:
	bin/release.sh

install_wordpress_dev:
	tests/bin/install_wordpress_dev.sh

dockerize_test_database:
	tests/bin/dockerize_test_database.sh

shutdown_test_database:
	tests/bin/shutdown_test_database.sh
