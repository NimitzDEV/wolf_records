#!/bin/sh
split -l 400 users.sql user_
find ./ -name "user_*" -exec sh replace.sh {} \;
