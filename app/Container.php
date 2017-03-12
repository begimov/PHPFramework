<?php

namespace App;

class Container implements \ArrayAccess
{
    private $items = array();

    private $cache = array();

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    public function offsetGet($offset)
    {
        if (!isset($this->items[$offset])) {
            return null;
        }

        if (isset($this->cache[$offset])) {
            return $this->cache[$offset];
        }

        $this->cache[$offset] = $this->items[$offset]($this);
        return $this->cache[$offset];
    }

    public function has($offset)
    {
        return $this->offsetExists($offset);
    }

    public function __get($name)
    {
        return $this->offsetGet($name);
    }
}
