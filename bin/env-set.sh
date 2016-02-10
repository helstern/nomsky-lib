#!/usr/bin/env bash

# declare arguments
SCRIPT_DIR=$(cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd)
ARG_ENV="${ARG_ENV}"

function help() {
cat <<HELP
Best way to iset the environment is:

ARG_ENV=<env file path> source bin/env-set.sh

HELP
}

# parse options (flags, flags with arguments, long options) and input
function parse_arguments() {
    while [ $# -gt 0 ]
    do
        case "${1}" in
            -e|--env)
                ARG_ENV="${2}"
                if [  -z "${ARG_ENV}" ]; then
                    echo 'environment can not be empty' 1>&2
                    exit 1
                fi
                shift
            ;;
            -h|--help)
                help
                exit 0
            ;;

            --) # End of all options
                shift
                INPUT="${1}"
                break;
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

if ! test -f "${ARG_ENV}" ; then
    echo "this environment file does not exist: ${ARG_ENV}" 1>&2
    exit 1
fi

OIFS=$IFS # save the field separator
IFS=$'\n'
for line in $(cat "${ARG_ENV}"); do
    export "${line}"
done
IFS=${OIFS} # restore the field separator
