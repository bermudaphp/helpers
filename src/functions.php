<?php


namespace Lobster;


/**
 * @param array $arguments
 * @return array
 */
function arguments(array $arguments) : array {

    if(array_key_exists(0, $arguments) && is_array($arguments[0])){
        return $arguments[0];
    }

    return $arguments;
}
