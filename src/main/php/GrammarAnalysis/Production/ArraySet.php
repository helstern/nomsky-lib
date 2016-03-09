<?php namespace Helstern\Nomsky\GrammarAnalysis\Production;


class ArraySet implements NormalizedSet
{
    private $items = array();

    /** @var HashKeyFactory */
    private $hashFactory;

    /**
     * @param \Helstern\Nomsky\GrammarAnalysis\Production\HashKeyFactory $hashFactory
     */
    public function __construct(HashKeyFactory $hashFactory)
    {
        $this->hashFactory = $hashFactory;
    }

    public function count()
    {
        return count($this->items);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }

    public function contains(NormalizedProduction $production)
    {
        $key = $this->hashFactory->hash($production);
        return array_key_exists($key, $this->items);
    }

    public function add(NormalizedProduction $production)
    {
        $key = $this->hashFactory->hash($production);
        if (array_key_exists($key, $this->items)) {
            return false;
        }

        $this->items[$key] = $production;
        return true;
    }

    public function addAll(NormalizedSet $otherSet)
    {
        $newSymbolsAdded = false;
        /** @var NormalizedProduction $otherProduction */
        $otherProduction     = null;
        foreach ($otherSet as $otherProduction) {
            $newSymbolsAdded |= $this->add($otherProduction);
        }

        return $newSymbolsAdded;
    }

    public function remove(NormalizedProduction $production)
    {
        $key = $this->hashFactory->hash($production);
        if (array_key_exists($key, $this->items)) {
            unset($this->items[$key]);
            return true;
        }

        return false;
    }

    public function toList()
    {
        return $this->items;
    }
}
