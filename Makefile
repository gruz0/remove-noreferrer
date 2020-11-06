.PHONY: help dockerize shell setup lint test coverage e2e e2e_single zip install_wordpress_dev dockerize_test_database shutdown_test_database wait_for_database

PLUGIN_VERSION=$(shell grep -r ' \* Version:' remove-noreferrer.php | egrep -o '([0-9]{1,}\.)+[0-9]{1,}')

help:
	@echo 'Available targets:'
	@echo '  make dockerize'
	@echo '  make shell'
	@echo '  make setup'
	@echo '  make lint'
	@echo '  make test'
	@echo '  make coverage'
	@echo '  make e2e'
	@echo '  make e2e_single'
	@echo '  make zip'
	@echo '  make install_wordpress_dev'
	@echo '  make dockerize_test_database'
	@echo '  make shutdown_test_database'
	@echo '  make wait_for_database'

dockerize:
	docker-compose down
	docker-compose up --build

shell:
	docker-compose exec wordpress bash

setup:
	bin/setup.sh

lint:
	bin/lint.sh

test:
	composer test

coverage:
	composer coverage
	open build/coverage/index.html

e2e: zip
	tests/integration/bin/all.sh $(PLUGIN_VERSION)

e2e_single: zip
	tests/integration/bin/run.sh $(WP_VERSION) $(PLUGIN_VERSION) $(IS_GITHUB_ACTIONS)

zip:
	bin/zip.sh $(PLUGIN_VERSION)

install_wordpress_dev:
	tests/bin/install_wordpress_dev.sh $(WP_VERSION)

dockerize_test_database:
	tests/bin/dockerize_test_database.sh

shutdown_test_database:
	tests/bin/shutdown_test_database.sh

wait_for_database:
	tests/bin/wait_for_database.sh
