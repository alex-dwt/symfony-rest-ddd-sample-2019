#!/usr/bin/env bash

_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" >/dev/null && pwd )"
_LOG=$_DIR/../var/log/api_tests.log

> $_LOG

export SYMFONY_DEPRECATIONS_HELPER=weak
$_DIR/../vendor/phpunit/phpunit/phpunit /app/tests/Api/