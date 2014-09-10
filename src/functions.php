<?php

namespace Rde;

function array_get(array $arr, $key, $default = null)
{
    if (array_key_exists($key, $arr)) {
        return $arr[$key];
    }

    if ($default && is_callable($default)) {
        return $default();
    }

    return $default;
}
