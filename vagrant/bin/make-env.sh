#!/usr/bin/env bash

# declare arguments
ARG_PROJECT_DIR=
ARG_ENV=
ARG_DEFAULT_PROVIDER=
SCRIPT_DIR=$(cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

# parse options (flags, flags with arguments, long options) and input
function parse_arguments() {
    while [ $# -gt 0 ]
    do
        case "${1}" in
            --project_dir)
                ARG_PROJECT_DIR="${2}"
                if [  -z "${ARG_PROJECT_DIR}" ]; then
                    echo 'project_dir can not be empty' 1>&2
                    exit 1
                fi
                shift
            ;;
            --default-provider)
                ARG_DEFAULT_PROVIDER="${2}"
                if [  -z "${ARG_DEFAULT_PROVIDER}" ]; then
                    echo 'default provider must not be empty' 1>&2
                    exit 1
                fi
                shift
            ;;
            --env)
                ARG_ENV="${2}"
                if [  -z "${ARG_ENV}" ]; then
                    echo 'env can not be empty' 1>&2
                    exit 1
                fi
                shift
            ;;
            -*)
                echo "invalid parameter ""${1}" 1>&2
                exit 1
            ;;
        esac
        shift
    done
}

parse_arguments "$@"

if [ -z "${ARG_ENV}" ]; then
    echo 'environment can not be empty' 1>&2
    exit 1
fi

if [ -z "${ARG_PROJECT_DIR}" ]; then
    echo 'project_dir can not be empty' 1>&2
    exit 1
fi

cat <<FILE > "${SCRIPT_DIR}/../config/${ARG_ENV}.env"
VAGRANT_PROJECT_DIR=${ARG_PROJECT_DIR}
VAGRANT_DOTFILE_PATH=${ARG_PROJECT_DIR}/vagrant
VAGRANT_PROVIDER=${ARG_DEFAULT_PROVIDER}
VAGRANT_DEFAULT_PROVIDER=${ARG_DEFAULT_PROVIDER}
FILE

