<?php namespace Helstern\Nomsky\Grammar\Symbol;

use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;

class ArraySet implements SymbolSet
{
    private $terminals = array();

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
        return array_key_exists($terminal->toString(), $this->terminals);
    }

    public function add(Symbol $terminal)
    {
        $key = $terminal->toString();
        if (array_key_exists($key, $this->terminals)) {
            return false;
        }

        $this->terminals[$key] = $terminal;
        return true;
    }

    public function addAll(SymbolSet $otherSet)
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
     * @param SymbolSet $otherSet
     * @param SymbolPredicate $acceptPredicate
     * @return bool
     */
    public function addSome(SymbolSet $otherSet, SymbolPredicate $acceptPredicate)
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
            unset($this->terminals[$symbol->toString()]);
            return true;
        }

        return false;
    }
}
