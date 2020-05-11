#!/bin/bash

path=`readlink -f "$0"`
path=`dirname "$path"`

test -e "$path/../.git/hooks/pre-commit" && echo "pre-commit already exists" && exit 1

ln -s "$path/pre-commit.sh" "$path/../.git/hooks/pre-commit"

exit $?
