#!/bin/bash -ex

# directories
SOURCE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="${SOURCE_DIR}"

DEPTH=2
until [ $DEPTH -le 0 ] ; do
    let DEPTH-=1
    PROJECT_DIR="$(dirname ${PROJECT_DIR})"
done

#absolute path to phpunit
PHPUNIT="$PROJECT_DIR/composer/bin/phpunit"

#absolute path to configuration
PHPUNIT_CONFIGURATION_DIR="$PROJECT_DIR/src/test/resources/"

# default phpunit options
PHPUNIT_DEFAULT_CONFIGURATION_FILE="$PROJECT_DIR/src/test/resources/phpunit.local.xml"

# xdebug configuration
PHP_IDE_SERVER_NAME='nomsky-dev'

PHPUNITARGS="$@"

if ! [[ "$PHPUNITARGS" =~ '--help' ]] && ! [[ "$PHPUNITARGS" =~ '--configuration|-c' ]]; then
    PHPUNITARGS="--configuration=$PHPUNIT_DEFAULT_CONFIGURATION_FILE $PHPUNITARGS"
fi

export PHP_IDE_CONFIG="serverName=${PHP_IDE_SERVER_NAME}" XDEBUG_CONFIG="idekey=PHPSTORM"
${PHPUNIT} ${PHPUNITARGS}
