#!/usr/bin/env bash

# directories
SOURCE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="$( cd "$( dirname "${SOURCE_DIR}" )../" && pwd )"

#phpunit
VAGRANT_DIR="$PROJECT_DIR/vagrant"

ARG_ENV=''
VAGRANT_ARGS=''
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
            -*)
                VAGRANT_ARGS="${VAGRANT_ARGS} ${1}"
            ;;
        esac
        shift
    done
}

parse_arguments "$@"

if test -n "${ARG_ENV}"; then
    ARG_ENV=$("${VAGRANT_DIR}/config/${ARG_ENV}.env") source env-set.sh
else
    echo 'env can not be empty' 1>&2
    exit 1
fi

cd ${VAGRANT_DIR}
vagrant "${VAGRANT_ARGS}"
