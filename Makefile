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
	$(MAKE) -$(MAKEFLAGS) vagrant-initialize

validate:
	php -v

compile: validate
	php ./bin/composer.phar install

test: compile
	bash bin/phpunit.sh --configuration src/test/config/phpunit.xml.dist

vagrant-initialize:
	bash "$(DIR)/vagrant/bin/make-env.sh" --env libvirt.local --project_dir "$(DIR)" --default-provider libvirt
	$(MAKE) ARGS=provision vagrant

vagrant:
	bash bin/vagrant.sh ${ARGS} --env libvirt.local

.PHONY: initialize vagrant





