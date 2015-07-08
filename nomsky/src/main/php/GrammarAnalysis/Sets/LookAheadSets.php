<?php namespace Helstern\Nomsky\GrammarAnalysis\Sets;

use Helstern\Nomsky\Grammar\Production\HashKey\HashKeyFactory;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;

class LookAheadSets
{
    /** @var array|SymbolSet[] */
    protected $sets = array();

    /** @var HashKeyFactory */
    protected $productionHashKeyFactory;

    /**
     * @param HashKeyFactory $hashKeyFactory
     */
    public function __construct(HashKeyFactory $hashKeyFactory)
    {
        $this->productionHashKeyFactory = $hashKeyFactory;
    }

    public function add(Production $production, SymbolSet $predictSet)
    {
        $hashKey = $this->productionHashKeyFactory->hash($production);
        if (array_key_exists($hashKey->toString(), $this->sets)) {
            return false;
        }

        $this->sets[$hashKey->toString()] = new LookAheadSetEntry($production, $predictSet);
        return true;
    }

    /**
     * @param Production $production
     * @return SymbolSet|null
     */
    public function getSet(Production $production)
    {
        $hashKey = $this->productionHashKeyFactory->hash($production);
        if (array_key_exists($hashKey->toString(), $this->sets)) {
            /** @var LookAheadSetEntry $entry */
            $entry = $this->sets[$hashKey->toString()];
            return $entry->getSymbolSet();
        }

        return null;
    }

    /**
     * @return array|SymbolSet[]
     */
    public function getAllSets()
    {
        return array_map(
            function (LookAheadSetEntry $entry) {
                return $entry->getSymbolSet();
            },
            $this->sets
        );
    }

    /**
     * @return LookAheadSetEntryIterator
     */
    public function getEntrySetIterator()
    {
        return new LookAheadSetEntryIterator(new \ArrayIterator($this->sets));
    }
}
