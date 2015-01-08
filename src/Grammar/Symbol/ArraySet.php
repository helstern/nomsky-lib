<?php namespace Helstern\Nomsky\Grammar\Symbol;

use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;

class ArraySet implements Set
{
    protected $terminals = array();

    public function count()
    {
        return count($this->terminals);
    }

    /**
     * @return \ArrayIterator|Symbol[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->terminals);
    }

    public function contains(Symbol $terminal)
    {
        return array_key_exists($terminal->hashCode(), $this->terminals);
    }

    public function add(Symbol $terminal)
    {
        if (array_key_exists($terminal->hashCode(), $this->terminals)) {
            return false;
        }

        $this->terminals[$terminal->hashCode()] = $this->terminals;
        return true;
    }

    public function addAll(Set $otherSet)
    {
        $newSymbolsAdded = false;
        /** @var Symbol $otherSymbol */
        $otherSymbol     = null;
        foreach ($otherSet as $otherSymbol) {
            $newSymbolsAdded |= $this->add($otherSymbol);
        }

        return $newSymbolsAdded;
    }

    /**
     * @param Set $otherSet
     * @param SymbolPredicate $acceptPredicate
     * @return bool
     */
    public function addSome(Set $otherSet, SymbolPredicate $acceptPredicate)
    {
        $newSymbolsAdded = false;

        foreach ($otherSet as $otherSymbol) {
            if ($acceptPredicate->matchSymbol($otherSymbol)) {
                $newSymbolsAdded |= $this->add($otherSymbol);
            }
        }

        return $newSymbolsAdded;
    }

    public function remove(Symbol $symbol)
    {
        if ($this->contains($symbol)) {
            unset($this->terminals[$symbol->hashCode()]);
            return true;
        }

        return false;
    }
}
