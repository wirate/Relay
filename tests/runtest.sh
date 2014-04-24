#!/bin/sh

PHPUNIT=phpunit
BOOTSTRAP=Bootstrap.php

$PHPUNIT --bootstrap "${BOOTSTRAP}" AllTests
