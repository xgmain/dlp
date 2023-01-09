<?php
namespace Core\Common;

class MyCollection implements \IteratorAggregate
{
    private $items = [];

    public function getItems()
    {
        return $this->items;
    }

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function getIterator(): MyIterator
    {
        return new MyIterator($this);
    }

    public function getReverseIterator(): MyIterator
    {
        return new MyIterator($this, true);
    }
}