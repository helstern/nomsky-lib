<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\GrammarAnalysis\Production\HashKeyFactory;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;

class LookAheadSets
{
    /** @var array|SymbolSet[] */
    private $sets = array();

    /** @var HashKeyFactory */
    private $productionHashKeyFactory;

    /**
     * @param HashKeyFactory $hashKeyFactory
     */
    public function __construct(HashKeyFactory $hashKeyFactory)
    {
        $this->productionHashKeyFactory = $hashKeyFactory;
    }

    /**
     * Adds a lookahead set for a production
     *
     * @param \Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction $key
     * @param \Helstern\Nomsky\Grammar\Symbol\SymbolSet $value
     *
     * @return bool
     */
    public function add(NormalizedProduction $key, SymbolSet $value)
    {
        $hashKey = $this->productionHashKeyFactory->hash($key);
        if (array_key_exists($hashKey, $this->sets)) {
            return false;
        }

        $this->sets[$hashKey] = new LookAheadSetEntry($key, $value);
        return true;
    }

    /**
     * @param NormalizedProduction $production
     * @return SymbolSet|null
     */
    public function getSet(NormalizedProduction $production)
    {
        $hashKey = $this->productionHashKeyFactory->hash($production);
        if (array_key_exists($hashKey, $this->sets)) {
            /** @var LookAheadSetEntry $entry */
            $entry = $this->sets[$hashKey];
            return $entry->getValue();
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
                return $entry->getValue();
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
