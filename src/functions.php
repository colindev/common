<?php

namespace Rde;

function array_get(array $arr, $key, $default = null)
{
    return array_key_exists($key, $arr) ? $arr[$key] : $default;
}
