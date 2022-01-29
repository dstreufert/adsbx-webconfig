#!/bin/bash

set -o noclobber

# atomically tries to create the file if it doesn't exist (noclobber option)

if { > /run/webconfig-auth.lock ; } &> /dev/null; then
    exit 0
else
    exit 1
fi
