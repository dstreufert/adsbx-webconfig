#!/bin/bash
# change_passwd.sh user old_pass new_pass
exec 2>&1
set -e
if echo "$2" | sudo -u nobody /bin/su --command true --login "$1"; then
    if echo "$1:$3" | chpasswd; then
        echo "Password changed."
    fi
else
    exit 1
fi
