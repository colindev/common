<?php namespace Rde;

use ArrayAccess;
use Iterator;

class Arr implements ArrayAccess, Iterator
{
    protected $resource;

    protected $resource_resolved;

    protected $queues = array();

    public function __construct($resource)
    {
        $this->resource = $resource;
    }

    public function filter($driver)
    {
        $this->queues[] = function($v, $k) use($driver) {
            return array(
                $driver($v, $k),
                'filter',
            );
        };

        return $this;
    }

    public function take($cnt)
    {
        $this->queues[] = function() use($cnt) {
            static $_cnt;
            (null === $_cnt) and $_cnt = $cnt;

            return array(
                0 < $_cnt--,
                'take',
            );
        };

        return $this;
    }

    public function init()
    {
        $this->resource_resolved = null;
        $this->queues = array();
    }

    public function each($callback)
    {
        $this->hasResolved() ?
            $this->eachResourceResolved($callback) :
            $this->resolve($callback);
    }

    protected function hasResolved()
    {
        return is_array($this->resource_resolved);
    }

    protected function resolve($callback = null)
    {
        $this->resource_resolved = array();
        $callback = is_callable($callback) ? $callback : function(){};
        foreach ($this->resource as $k => $v) {
            foreach ($this->queues as $driver) {
                $resolve = $driver($v, $k);
                if ( ! $resolve[0]) {
                    if ('take' === $resolve[1]) {
                        break 2;
                    }
                    continue 2;
                }
            }
            $this->resource_resolved[$k] = $v;
            $callback($v, $k);
        }
    }

    protected function eachResourceResolved($callback)
    {
        foreach ($this->resource_resolved as $k => $v) {
            $callback($v, $k);
        }
    }

    public function offsetExists($key)
    {
        $this->hasResolved() or $this->resolve();

        return isset($this->resource_resolved[$key]);
    }

    public function offsetGet($key)
    {
        $this->hasResolved() or $this->resolve();

        return $this->resource_resolved[$key];
    }

    public function offsetSet($key, $val)
    {
        $this->hasResolved() or $this->resolve();

        if (null === $key) {
            $this->resource_resolved[] = $val;
        } else {
            $this->resource_resolved[$key] = $val;
        }
    }

    public function offsetUnset($key)
    {
        $this->hasResolved() or $this->resolve();

        unset($this->resource_resolved[$key]);
    }

    public function current()
    {
        $this->hasResolved() or $this->resolve();

        return current($this->resource_resolved);
    }

    public function key()
    {
        $this->hasResolved() or $this->resolve();

        return key($this->resource_resolved);
    }

    public function next()
    {
        $this->hasResolved() or $this->resolve();

        return next($this->resource_resolved);
    }

    public function rewind()
    {
        $this->hasResolved() or $this->resolve();

        reset($this->resource_resolved);
    }

    public function valid()
    {
        return null !== $this->key();
    }
}
