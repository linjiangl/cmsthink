#!/bin/sh

# set these paths to match your environment
rm -rf ./web && mkdir web && mkdir web/admin && mkdir web/api

# admin docs
rm -rf ./apidoc.json && cp apidoc.json.admin apidoc.json
node_modules/apidoc/bin/apidoc -f ".*\\.php$" -i ../src/service/application/admin/controller/ -o ./web/admin/v1/

# api docs
rm -rf ./apidoc.json && cp apidoc.json.api apidoc.json
node_modules/apidoc/bin/apidoc -f ".*\\.php$" -i ../src/service/application/api/controller/ -o ./web/api/v1/
