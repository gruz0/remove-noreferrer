.PHONY: help dockerize shell install_linters lint release

help:
	@echo 'Available targets:'
	@echo '  make dockerize'
	@echo '  make shell'
	@echo '  make install_linters'
	@echo '  make lint'
	@echo '  make release'

dockerize:
	docker-compose down
	docker-compose up --build

shell:
	docker-compose exec wordpress bash

install_linters:
	bin/install_linters.sh

lint:
	bin/lint.sh

release:
	bin/release.sh
