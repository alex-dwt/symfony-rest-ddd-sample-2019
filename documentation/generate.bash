#!/usr/bin/env bash

_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"/api
_FILE=$_DIR/_doc.html

docker run --rm -v $_DIR:/tmp davidonlaptop/aglio -i /tmp/index.md -o /tmp/_doc.html

sed -i 's/<h5>Schema<\/h5><pre>/<h5 style="display:none">Schema<\/h5><pre style="display:none">/' "$_FILE"

sed -i 's/<\/style>/.container {max-width: 1200px !important} nav {width: 285px !important} .container .row .content {margin-left: 290px !important; width: 880px !important} <\/style>/' "$_FILE"