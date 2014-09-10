<?php

namespace Rde;

function array_get($arr, $key, $default = null)
{
    if ( ! is_array($arr) && ! is_a($arr, 'ArrayAccess')) {
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

// debug
function dd()
{
    call_user_func_array('var_dump', func_get_args());
    die;
}
