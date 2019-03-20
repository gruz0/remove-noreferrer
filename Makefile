.PHONY: help dockerize shell install_linters lint

help:
	@echo 'Available targets:'
	@echo '  make dockerize'
	@echo '  make shell'
	@echo '  make install_linters'
	@echo '  make lint'

dockerize:
	docker-compose down
	docker-compose up --build

shell:
	docker-compose exec wordpress bash

install_linters:
	bin/install_linters.sh

lint:
	bin/lint.sh
