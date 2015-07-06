#!/usr/bin/env bash

PROJECT_DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

function module_exists() {
    MODULE=${1}

    if ! test -d "${PROJECT_DIR}/${MODULE}" ; then
       return 100
    fi

    if ! test -d "${PROJECT_DIR}/${MODULE}/.git" ; then
       return 101
    fi

    return 0
}

if module_exists 'provision-puppet' ; then
    echo 'module provision-puppet exists, not cloning'
else
    echo 'cloning puppet provision module'
    git clone git@github.com:helstern/provisioning-with-puppet.git provision-puppet
fi

if module_exists 'provision-puppet' ; then
    echo 'module provision-puppet exists, not cloning'
else
    echo 'cloning puppet provision module'
    git clone git@github.com:helstern/provisioning-with-puppet.git provision-puppet
fi
