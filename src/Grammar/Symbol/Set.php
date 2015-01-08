<?php namespace Helstern\Nomsky\Grammar\Symbol;

interface Set extends \Countable, \IteratorAggregate
{
    /**
     * @return \ArrayIterator|Symbol[]
     */
    public function getIterator();

    public function remove(Symbol $symbol);

    public function add(Symbol $symbol);

    public function addAll(Set $all);

    public function contains(Symbol $symbol);
}
