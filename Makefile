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

initialize:
	test -d "$(DIR)/../provision-puppet/.git" || git clone git@github.com:helstern/provisioning-with-puppet.git "$(DIR)/../provision-puppet"
	$(MAKE) -$(MAKEFLAGS) vagrant

validate:
	php -v

compile: validate
	php ./bin/composer.phar install

test: compile
	bash bin/phpunit --configuration src/test/config/phpunit.xml.dist

vagrant:
	bash "$(DIR)/vagrant/bin/make-env.sh" --project_dir "$(DIR)" --env libvirt.local --default-provider libvirt
	bash bin/vagrant.sh provision --env libvirt.local

vagrant-up:
	bash bin/vagrant.sh up --env libvirt.local

.PHONY: pre-configure configure vagrant





