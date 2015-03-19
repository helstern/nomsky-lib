#!/bin/bash -o

DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )

dot -O -T png "$(php $DIR/demo-ebnf-graphviz.php)"
