.PHONY: help dockerize shell install_linters lint test coverage release install_wordpress_dev dockerize_test_database shutdown_test_database wait_for_database

help:
	@echo 'Available targets:'
	@echo '  make dockerize'
	@echo '  make shell'
	@echo '  make install_linters'
	@echo '  make lint'
	@echo '  make test'
	@echo '  make coverage'
	@echo '  make release'
	@echo '  make install_wordpress_dev'
	@echo '  make dockerize_test_database'
	@echo '  make shutdown_test_database'
	@echo '  make wait_for_database'

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

coverage:
	composer coverage
	open build/coverage/index.html

release:
	bin/release.sh

install_wordpress_dev:
	tests/bin/install_wordpress_dev.sh $(WP_VERSION)

dockerize_test_database:
	tests/bin/dockerize_test_database.sh

shutdown_test_database:
	tests/bin/shutdown_test_database.sh

wait_for_database:
	tests/bin/wait_for_database.sh
