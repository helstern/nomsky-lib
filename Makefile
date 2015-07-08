SHELL=/bin/bash
PHPFLAGS=
PHPENV=

ifdef XDEBUG_CONFIG
	PHPFLAGS += -d xdebug.remote_enable=1
endif

ifdef PHP_IDE_CONFIG
	PHPENV += PHP_IDE_CONFIG=$(PHP_IDE_CONFIG)
endif

initialize:
	test -d 'provision-puppet/.git' || git clone git@github.com:helstern/provisioning-with-puppet.git provision-puppet

validate:
	php -v

compile: validate
	cd nomsky/depend/composer; php ./composer.phar install

test: compile
	$(PHPENV) php $(PHPFLAGS) nomsky/depend/composer/bin/phpunit --configuration=nomsky/src/test/resources/phpunit.local.xml





