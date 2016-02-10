<?php namespace Helstern\Nomsky\Util;

use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet as SymbolSet;

class ArraySymbolSet implements SymbolSet
{
    protected $symbols = array();

    public function count()
    {
        return count($this->symbols);
    }

    public function remove(Symbol $symbol)
    {
        if (array_key_exists($symbol->toString(), $this->symbols)) {
            unset ($this->symbols[$symbol->toString()]);
            return true;
        }

        return false;
    }

    public function add(Symbol $symbol)
    {
        if (array_key_exists($symbol->toString(), $this->symbols)) {
            return false;
        }

        $this->symbols[$symbol->toString()] = $symbol;
        return true;
    }

    public function contains(Symbol $symbol)
    {
        return array_key_exists($symbol->toString(), $this->symbols);
    }

    /**
     * @return \ArrayIterator|Symbol[]
     */
    public function getIterator()
    {
        // TODO: Implement getIterator() method.
    }

    public function addAll(SymbolSet $all)
    {
        // TODO: Implement addAll() method.
    }
}
