<?php

/*
 * Bootstrap: setup the test environment.
 */

// Set error level to what the target should pass
error_reporting(E_ALL | E_STRICT);

// Set include path to the code.
$root   = realpath(dirname(dirname(__FILE__)));
$target = "$root/library";
$tests  = "$root/tests";

$path = array(
    $target,
    $tests,
    get_include_path()
);
set_include_path(implode(PATH_SEPARATOR, $path));

// clear used variables.
unset($root, $target, $tests, $path);
