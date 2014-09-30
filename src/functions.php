<?php

namespace Rde;

/** @todo 雖然已經在composer指定5.4+,但暫時還是保留5.3相容寫法 */

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

function object_get($obj, $key, $default = null)
{
    if ( ! \is_object($obj)) {
        throw new \InvalidArgumentException('參數1必須為物件');
    }

    if (isset($obj->{$key})) {
        return $obj->{$key};
    }

    if ($default && is_callable($default)) {
        return $default();
    }

    return $default;
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
