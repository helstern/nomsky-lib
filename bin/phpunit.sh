#!/bin/bash

# directories
SOURCE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )../" && pwd )"
PROJECT_DIR="${SOURCE_DIR}"

#phpunit
PHPUNIT="$PROJECT_DIR/composer/bin/phpunit"

#absolute path to configuration
PHPUNIT_CONFIGURATION_DIR="$PROJECT_DIR/src/test/config/phpunit.xml.dist"

# default phpunit options
PHPUNIT_DEFAULT_CONFIGURATION_FILE="$PROJECT_DIR/src/test/config/phpunit.local.xml"

ARG_XDEBUG=''
PHPUNIT_ARGS=''
PHPUNIT_CONF="--configuration $PHPUNIT_DEFAULT_CONFIGURATION_FILE"
# parse options (flags, flags with arguments, long options) and input
function parse_arguments() {
    while [ $# -gt 0 ]
    do
        case "${1}" in
            --env)
                ARG_ENV="${2}"
                if [  -z "${ARG_ENV}" ]; then
                    echo 'env can not be empty' 1>&2
                    exit 1
                fi
                shift
            ;;
            --xdebug)
                ARG_XDEBUG='xdebug'
            ;;
            --configuration|-c)
                PHPUNITARGS="${PHPUNITARGS} ${1}"
                PHPUNIT_CONF=""
            ;;
            --help)
                ${PHPUNIT} --help
                exit 0
            ;;
            -*)
                PHPUNIT_ARGS="${PHPUNITARGS} ${1}"
            ;;
        esac
        shift
    done
}

parse_arguments "$@"

if test -n "${PHPUNIT_CONF}"; then
    PHPUNIT_ARGS="${PHPUNIT_CONF} ${PHPUNIT_ARGS}"
fi

if test -n "${ARG_XDEBUG}"; then
    ARG_ENV=xdebug source env-set.sh
    PHPUNIT_ARGS="-d xdebug.remote_enable=1 ${PHPUNIT_ARGS}"

fi

${PHPUNIT} ${PHPUNITARGS}
