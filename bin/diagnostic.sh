#!/usr/bin/env bash -x

# directories
SOURCE_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"
PROJECT_DIR="$( cd "${SOURCE_DIR}/.." && pwd )"

# project commands
ENVSET="${PROJECT_DIR}/bin/env-set.sh"

# diagnostic
DIR="${PROJECT_DIR}/diagnostic"
CONFIG_DIR="${DIR}/config"


ARG_ENV="${ARG_ENV}"
ARGS=''
# parse options (flags, flags with arguments, long options) and input
function parse_arguments() {
    while [ $# -gt 0 ]
    do
        case "${1}" in
            --env)
                ARG_ENV="${2}"
                if [  -z "${ARG_ENV}" ]; then
                    echo 'env can not be empty 1' 1>&2
                    exit 1
                fi
                shift
            ;;
            *)
                ARGS="${ARGS} ${1}"
            ;;
        esac
        shift
    done
}

parse_arguments "$@"

if test -n "${ARG_ENV}"; then
    ARG_ENV="${CONFIG_DIR}/${ARG_ENV}.env" source "${ENVSET}" --
else
    echo 'env can not be empty 2' 1>&2
    exit 1
fi
