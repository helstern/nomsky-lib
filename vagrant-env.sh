#!/usr/bin/env bash

SCRIPT_DIR=$(cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

export VAGRANT_PROJECT_DIR="${SCRIPT_DIR}/nomsky"
export VAGRANT_DOTFILE_PATH="${SCRIPT_DIR}/nomsky/target/vagrant"
export VAGRANT_PROVIDER=libvirt

