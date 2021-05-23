<?php

namespace Bermuda;

/**
 * @param array $arguments
 * @return array
 */
function arguments(array $arguments): array 
{
    if(array_key_exists(0, $arguments) && is_array($arguments[0]))
    {
        return $arguments[0];
    }

    return $arguments;
}


/**
 * @param array $array
 * @return bool
 */
function is_assoc(array $array): bool 
{
    foreach ($array as $offset => $value)
    {
        if(is_string($offset))
        {
            return true;
        }
    }

    return false;
}

/**
 * @param $var
 * @return bool
 */
function is_oct($var): bool 
{
    return decoct(octdec($var)) == $var;
}

/**
 * @param $var
 * @return bool
 */
function is_hex($var): bool 
{
    return ctype_xdigit($var);
}

/**
 * @param $var
 * @return bool
 */
function is_accessible($var): bool 
{
    return is_array($var) || $var instanceof \ArrayAccess;
}

/**
 * @param array $array
 * @param $offset
 * @return mixed|null
 */
function array_pull(array &$array, $offset)
{    
    $v = array_get($array, $offset);
    array_remove($array, $offset);

    return $v;
}


/**
 * @param array $array
 * @param $offset
 * @param null $default
 * @return mixed|null
 */
function array_get(array $array, $offset, $default = null)
{    
    if(is_string($offset) && is_dot($offset))
    {
        $segments = dot_explode($offset);
        $offset = array_pop($segments);

        foreach ($segments as $segment)
        {    
            if(array_key_exists($segment, $array) && is_array($array[$segment]))
            {
                $array = $array[$segment];
                continue;
            } 
            
            return $default;
        }
    }

    return array_key_exists($offset, $array) ? $array[$offset] : $default;
}

/**
 * @param array $array
 * @param $offset
 * @param $value
 */
function array_set(array &$array, $offset, $value): void 
{
    if($offset === null)
    {
        $array[] = $value;
        return;
    }

    if(is_string($offset) && is_dot($offset))
    {
        $segments = dot_explode($offset);
        $offset = array_pop($segments);

        foreach ($segments as $segment)
        {
            $array[$segment] = [];
            $array =& $array[$segment];
        }
    }

    $array[$offset] = $value;
}

/**
 * @param array $array
 * @param array|string|int $offset
 * @param string|int ... $offsets
 * @return array
 */
function array_only(array $array, $offset, ... $offsets): array 
{
    if(is_array($offset))
    {
        $offsets = $offset;
    } 
    
    else 
    {
        array_unshift($offsets, $offset);
    }

    $values = [];

    foreach ($offsets as $offset)
    {
        if (array_has($array, $offset))
        {
            $values[$offset] = array_get($array, $offset);
        }
    }

    return $values;
}

/**
 * @param array $array
 * @param array|string|int $offset
 * @param string|int ... $offsets
 * @return array
 */
function array_except(array $array, $offset, ... $offsets): array 
{
    if(is_array($offset))
    {
        $offsets = $offset;
    } 
    
    else
    {
        array_unshift($offsets, $offset);
    }

    foreach ($offsets as $offset)
    {
        array_remove($array, $offset);
    }

    return $array;
}

/**
 * @param array $array
 * @param $offset
 * @return bool
 */
function array_has(array $array, $offset): bool 
{
    if(is_string($offset) && is_dot($offset))
    {
        $segments = dot_explode($offset);
        $offset = array_pop($segments);

        foreach ($segments as $segment)
        {
            if(array_key_exists($segment, $array) && is_array($array[$segment])){
                $array = $array[$segment];
                continue;
            } 
            
            return false;
        }
    }

    return array_key_exists($offset, $array);
}

/**
 * @param array $array
 * @param $offset
 */
function array_remove(array &$array, $offset): void 
{
    if(is_string($offset) && is_dot($offset))
    {
        $segments = dot_explode($offset);
        $offset = array_pop($segments);

        foreach ($segments as $segment)
        {    
            if(array_key_exists($segment, $array) && is_array($array[$segment])){
                $array =& $array[$segment];
                continue;
            }
            
            return;
        }
    }

    unset($array[$offset]);
}

/**
 * @param string $string
 * @return bool
 */
function is_dot(string $string): bool 
{
    return contains($string, '.');
}

/**
 * @param string $string
 * @return array
 */
function dot_explode(string $string): array 
{
    return explode('.', $string);
}
