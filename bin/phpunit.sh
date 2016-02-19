#!/bin/bash

# directories
SOURCE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="$( cd "${SOURCE_DIR}/.." && pwd )"

#phpunit
PHPUNIT="$PROJECT_DIR/composer/bin/phpunit"

#absolute path to configuration
PHPUNIT_CONFIGURATION="$PROJECT_DIR/src/test/config/phpunit.xml.dist"

ARG_XDEBUG=''
PHPUNIT_ARGS=''
PHPUNIT_ARGS_CONF="--configuration ${PHPUNIT_CONFIGURATION}"
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
                PHPUNIT_ARGS="${PHPUNIT_ARGS} --configuration ${2}"
                PHPUNIT_ARGS_CONF=''
                shift
            ;;
            --help)
                ${PHPUNIT} --help
                exit 0
            ;;
            *)
                PHPUNIT_ARGS="${PHPUNIT_ARGS} ${1}"
            ;;
        esac
        shift
    done
}

parse_arguments "$@"

if test -n "${PHPUNIT_ARGS_CONF}"; then
    PHPUNIT_ARGS="${PHPUNIT_ARGS_CONF} ${PHPUNIT_ARGS}"
fi

PHP='php'
if test -n "${ARG_XDEBUG}"; then
    PHP="${PHP} -d xdebug.remote_enable=1 -d xdebug.remote_connect_back=1"
fi

${PHP} ${PHPUNIT} ${PHPUNIT_ARGS}
