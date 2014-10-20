<?php

namespace Rde;

// array

function is_array($arg)
{
    return  \is_array($arg) || \is_a($arg, 'ArrayAccess');
}

function array_get($arr, $key, $default = null)
{
    if ( ! is_array($arr)) {
        throw new \InvalidArgumentException('參數1必須為陣列或ArrayAccess實體');
    }

    if (array_key_exists($key, $arr)) {
        return $arr[$key];
    }

    if ($default && is_callable($default)) {
        return $default();
    }

    return $default;
}

function array_each($callback)
{
    foreach (array_slice(func_get_args(), 1) as $i => $arr) {
        if ( ! is_array($arr)) {
            throw new \InvalidArgumentException("參數".($i+2)."必須為陣列或ArrayAccess實體");
        }
        foreach ($arr as $k => $v) {
            if (false === $callback($k, $v)) {
                break;
            }
        }
    }
}

function array_merge_callback($driver, array $base)
{
    $collection = $base;
    $appends = array_slice(func_get_args(), 2);

    foreach ($appends as $append) {
        foreach ($append as $k => $v) {
            $driver(
                $v,
                $k,
                $collection,
                $driver);
        }
    }

    return $collection;
}

// tool
function call($callable, array $args = array())
{
    switch (count($args)) {
        case 0: return $callable();
        case 1: return $callable($args[0]);
        case 2: return $callable($args[0], $args[1]);
        case 3: return $callable($args[0], $args[1], $args[2]);
        case 4: return $callable($args[0], $args[1], $args[2], $args[3]);
        case 5: return $callable($args[0], $args[1], $args[2], $args[3], $args[4]);
    }

    return call_user_func_array($callable, $args);
}

function value($val)
{
    return is_callable($val) ? $val() : $val;
}

function with($any)
{
    return $any;
}

// debug
function dd()
{
    call_user_func_array('var_dump', func_get_args());
    die;
}
