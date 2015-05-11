#!/bin/bash

CURRENT_DIR=$(cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
PROJECT_DIR="$(cd "${CURRENT_DIR}/../../../";pwd)"

# this is not needed
# export VAGRANT_CWD=${PROJECT_DIR}/src/infrastructure/vagrant
export VAGRANT_PROJECT_DIR=${PROJECT_DIR}
export VAGRANT_DOTFILE_PATH=${PROJECT_DIR}/target/vagrant
export VAGRANT_PROVIDER=libvirt
