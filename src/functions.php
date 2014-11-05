<?php

namespace Rde;

// array
// 效能先決,不做多餘檢查

function is_array($arg)
{
    return  \is_array($arg) || \is_a($arg, 'ArrayAccess');
}

function array_get($arr, $key, $default = null)
{
    if (array_key_exists($key, $arr)) {
        return $arr[$key];
    }

    return value($default);
}

// 參數順序同 array_map
// callback 傳入參數順序 $key, $val
function array_each($callback)
{
    foreach (array_slice(func_get_args(), 1) as $i => $arr) {
        foreach ($arr as $k => $v) {
            if (false === $callback($k, $v)) {
                break;
            }
        }
    }
}

function array_first($arr, $callback, $default = null)
{
    foreach ($arr as $k => $v) {
        if ($callback($k, $v)) {
            return $v;
        }
    }

    return value($default);
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

// Generator

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

// tool
function call($callable, array $args = array())
{
    switch (count($args)) {
        case 0: return call_user_func($callable);
        case 1: return call_user_func($callable, $args[0]);
        case 2: return call_user_func($callable, $args[0], $args[1]);
        case 3: return call_user_func($callable, $args[0], $args[1], $args[2]);
        case 4: return call_user_func($callable, $args[0], $args[1], $args[2], $args[3]);
    }

    return call_user_func_array($callable, $args);
}

function value($val)
{
    return $val instanceof \Closure ? $val() : $val;
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
