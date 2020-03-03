#!/usr/bin/env bash

FOLDER="./input"

find ${FOLDER} -iname '*.png' -exec convert \{} -verbose -resize 30x20\> \{} \;
