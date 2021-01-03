.PHONY: help dockerize shell setup seed lint test coverage e2e e2e_single zip install_wordpress_dev dockerize_test_database shutdown_test_database wait_for_database fix_permissions stop clean

PLUGIN_VERSION=$(shell grep -r ' \* Version:' remove-noreferrer.php | egrep -o '([0-9]{1,}\.)+[0-9]{1,}')

help:
	@echo 'Available targets:'
	@echo '  make dockerize'
	@echo '  make shell'
	@echo '  make setup'
	@echo '  make seed'
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
	@echo '  make fix_permissions'
	@echo '  make stop'
	@echo '  make clean'

dockerize: stop
	docker-compose up --build -d
	docker-compose exec wordpress /docker/install-wp-cli.sh
	docker-compose exec wordpress /docker/activate-debug.sh
	docker-compose exec wordpress /docker/touch-debug-log.sh
	docker-compose exec wordpress /docker/fix-permissions.sh
	docker-compose logs -f

shell:
	docker-compose exec wordpress bash

setup:
	bin/setup.sh

seed:
	bin/seed.sh

lint:
	bin/lint.sh

test:
	XDEBUG_MODE=coverage composer test

coverage:
	XDEBUG_MODE=coverage composer coverage
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

fix_permissions:
	docker-compose exec wordpress /docker/fix-permissions.sh

stop:
	docker-compose down

clean: stop
	docker-compose down
	docker-compose rm -f
	rm -rf .data
