<?php

namespace Rde;

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

/**
 * @param array|\Generator $source 資料源
 * @param callable $driver 過濾器
 * @return \Generator
 */
function array_filter($source, callable $driver)
{
    foreach ($source as $k => $v) {
        if ($driver($v, $k)) {
            yield $k => $v;
        }
    }
}

/**
 * @param array|\Generator $source
 * @param int $cnt
 * @return \Generator
 */
function array_take($source, $cnt)
{
    $cnt = (int) $cnt;
    foreach ($source as $k => $v) {
        if (0 >= $cnt) {
            break;
        }

        yield $k => $v;

        --$cnt;
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
