<?php


namespace log;


class CumulativeLogger implements ILogger, \ArrayAccess, \Countable
{
    protected array $logs;

    public function log(string $message)
    {
        $this->logs[] = $message;
    }

    public function offsetExists($offset):bool
    {
        return array_key_exists($offset, $this->logs);
    }

    public function offsetGet($offset)
    {
        return $this->logs[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->logs[] = $value;
        } else {
            $this->logs[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->logs[$offset]);
    }

    public function count():int
    {
        return count($this->logs);
    }
}