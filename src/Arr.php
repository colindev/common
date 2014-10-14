<?php namespace Rde;

use ArrayAccess;
use Iterator;

class Arr implements ArrayAccess, Iterator
{
    protected $arr;

    protected $queues = array();

    public function __construct($arr)
    {
        $this->arr = $arr;
    }

    public function filter($driver)
    {
        $this->queues[] = $driver;

        return $this;
    }

    public function take($cnt)
    {
        $this->queues[] = function() use($cnt) {
            static $_cnt;
            (null === $_cnt) and $_cnt = $cnt;

            return 0 < $_cnt--;
        };
    }

    public function each($callback)
    {
        foreach ($this->arr as $k => $v) {
            foreach ($this->queues as $driver) {
                if ( ! $driver($v, $k)) {
                    continue 2;
                }
            }

            if (false === $callback($v, $k)) {
                break;
            }
        }
    }

    public function offsetExists($key)
    {
        return isset($this->arr[$key]);
    }

    public function offsetGet($key)
    {
        return $this->arr[$key];
    }

    public function offsetSet($key, $val)
    {
        if (null === $key) {
            $this->arr[] = $val;
        } else {
            $this->arr[$key] = $val;
        }
    }

    public function offsetUnset($key)
    {
        unset($this->arr[$key]);
    }

    public function current()
    {
        return current($this->arr);
    }

    public function key()
    {
        return key($this->arr);
    }

    public function next()
    {
        return next($this->arr);
    }

    public function rewind()
    {
        reset($this->arr);
    }

    public function valid()
    {
        return null !== $this->key();
    }
}
