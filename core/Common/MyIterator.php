<?php
namespace Core\Common;

class MyIterator implements \Iterator{

    private $collection;
    private $position = 0;
    private $reverse = false;

    public function __construct($collection, $reverse = false)
    {
        $this->collection = $collection;
        $this->reverse = $reverse;
    }

    public function rewind(): void
    {
        $this->position = $this->reverse ? count($this->collection->getItems()) - 1 : 0;
    }

    public function current()
    {
        return $this->collection->getItems()[$this->position];
    }

    public function key(): Int
    {
        return $this->position;
    }

    public function next(): void
    {
        $this->position = $this->position + ($this->reverse ? -1 : 1);
    }

    public function valid(): bool
    {
        return isset($this->collection->getItems()[$this->position]);
    }

    public function previousRtn()
    {
        try {
            $rtn = $this->collection->getItems()[$this->position - 1];
        } catch (\Exception $e) {
            return false;
        }

        return $rtn;
    }

    public function nextRtn()
    {
        try {
            $rtn = $this->collection->getItems()[$this->position + 1];
        } catch (\Exception $e) {
            return false;
        }

        return $rtn;
    }
}