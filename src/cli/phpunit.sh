#!/bin/bash

# directories
SOURCE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="$(dirname $SOURCE_DIR)"

#absolute path to phpunit
PHPUNIT="$PROJECT_DIR/vendor/bin/phpunit"

# default phpunit options
PHPUNITDEFAULTS_CONFIGURATION="phpunit.local.xml"

PHPUNITARGS="$@"

if ! [[ "$PHPUNITARGS" =~ '--help' ]] && ! [[ "$PHPUNITARGS" =~ '--configuration|-c' ]]; then
    PHPUNITARGS="--configuration=$PHPUNITDEFAULTS_CONFIGURATION $PHPUNITARGS"
fi

## xdebug configuration
#PHP_IDE_SERVER_NAME='dev-php'
#
#export PHP_IDE_CONFIG="serverName=${PHP_IDE_SERVER_NAME}" XDEBUG_CONFIG="idekey=PHPSTORM"

${PHPUNIT} ${PHPUNITARGS}
