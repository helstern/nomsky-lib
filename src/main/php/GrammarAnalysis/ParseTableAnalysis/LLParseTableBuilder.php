<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseTableAnalysis;

use Helstern\Nomsky\Grammar\Production;
use Helstern\Nomsky\Grammar\Symbol\ArraySet;

class LLParseTableBuilder
{
    /**
     * @var ArraySet
     */
    private $terminals;

    /**
     * @var ArraySet
     */
    private $nonTerminals;

    /**
     * @var LookAheadSets
     */
    private $lookAheadSets;

    /**
     * @var Production\HashKey\SimpleHashKeyFactory
     */
    private $productionHashAlgorithm;

    public function __construct()
    {
        $this->productionHashAlgorithm = new Production\HashKey\SimpleHashKeyFactory();
    }

    /**
     * @param array $terminals
     *
     * @return LLParseTableBuilder
     */
    public function addTerminals(array $terminals)
    {
        $set = new ArraySet();
        foreach($terminals as $symbol) {
            $set->add($symbol);
        }
        $this->terminals = $set;

        return $this;
    }

    /**
     * @param array $nonTerminals
     *
     * @return LLParseTableBuilder
     */
    public function addNonTerminals(array $nonTerminals)
    {
        $set = new ArraySet();
        foreach($nonTerminals as $symbol) {
            $set->add($symbol);
        }
        $this->nonTerminals = $set;

        return $this;
    }

    public function addLookAheadSets(LookAheadSets $sets)
    {
        $this->lookAheadSets = $sets;
        return $this;
    }

    public function build()
    {
        $parseTable = new LLParseTable($this->nonTerminals, $this->terminals);

        /** @var Production\Production $production */
        foreach ($this->lookAheadSets->getEntrySetIterator() as $production => $terminalSet) {
            $productionSetEntry = $this->createProductionSetEntry($production);
            $parseTable->addAllEntries($productionSetEntry, $terminalSet);
        }

        return $parseTable;
    }

    /**
     * @param Production\Production $production
     * @return Production\Set\ProductionSetEntry
     */
    private function createProductionSetEntry(Production\Production $production)
    {
        $hashKey = $this->productionHashAlgorithm->hash($production);
        return new Production\Set\ProductionSetEntry($hashKey, $production);
    }
}

