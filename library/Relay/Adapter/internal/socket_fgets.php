<?php

/**
 * Hack to impelment fgets for raw sockets :)
 *
 * @param resource  $socket
 * @param int       $length
 * @return string|bool
 */
function socket_fgets($socket, $length) {

    $buf = false;

    for($n = 0; $n < $length - 1; $n++) {

        $ch = '';
        if (@socket_recv($socket, $ch, 1, 0) === false) {
            break;
        }

        $buf .= $ch;
        if ($ch == "\n") {
            break;
        }
    }
    return $buf;
}
