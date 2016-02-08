SHELL=/bin/bash
DIR := $(shell dirname $(abspath $(lastword $(MAKEFILE_LIST))))
PHPFLAGS=
PHPENV=


ifdef XDEBUG_CONFIG
	PHPFLAGS += -d xdebug.remote_enable=1
endif

ifdef PHP_IDE_CONFIG
	PHPENV += PHP_IDE_CONFIG=$(PHP_IDE_CONFIG)
endif

pre-configure:
	test -d "$(DIR)/../provision-puppet/.git" || git clone git@github.com:helstern/provisioning-with-puppet.git "$(DIR)/../provision-puppet"

configure: pre-configure
	$(MAKE) -$(MAKEFLAGS) vagrant

vagrant:
	bash "$(DIR)/bin/vagrant-env.sh" --project_dir "$(DIR)" --env vagrant.local
	ARG_ENV=vagrant.local source "$(DIR)/bin/env-set.sh"; cd "$(DIR)/vagrant" ; vagrant up --provision ; vagrant halt

vagrant-up:
	ARG_ENV=vagrant.local source "$(DIR)/bin/env-set.sh" && cd "$(DIR)/vagrant" && vagrant up

validate:
	php -v

compile: validate
	php ./bin/composer.phar install

test: compile
	$(PHPENV) php $(PHPFLAGS) nomsky/depend/composer/bin/phpunit --configuration nomsky/src/test/resources/phpunit.local.xml

.PHONY: pre-configure configure vagrant





