#!/usr/bin/env bash

# declare arguments
ARG_PROJECT_DIR=
ARG_ENV=
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

cat <<FILE > "${ARG_PROJECT_DIR}/src/env/${ARG_ENV}.env"
VAGRANT_PROJECT_DIR=${ARG_PROJECT_DIR}
VAGRANT_DOTFILE_PATH=${ARG_PROJECT_DIR}/target/vagrant
VAGRANT_PROVIDER=libvirt
VAGRANT_DEFAULT_PROVIDER=libvirt
FILE

