<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseTableAnalysis;

use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Production\HashKey\SimpleHashKeyFactory;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\ArraySet;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\GrammarAnalysis\Sets\LookAheadSets;
use Helstern\Nomsky\Grammar\Production\Set\SetEntry as ProductionSetEntry;

class LLParseTableGenerator
{
    /** @var LookAheadSets */
    protected $predictSets;

    public function __construct(LookAheadSets $predictSets)
    {
        $this->predictSets = $predictSets;
    }

    /**
     * @param Grammar $g
     * @return LLParseTable
     */
    public function generate(Grammar $g)
    {
        $nonTerminalsSet = new ArraySet();
        $nonTerminals = $g->getNonTerminals();
        foreach($nonTerminals as $symbol) {
            $nonTerminalsSet->add($symbol);
        }

        $terminalsSet = new ArraySet();
        $terminals = $g->getTerminals();
        foreach($terminals as $symbol) {
            $terminalsSet->add($symbol);
        }

        /** @var ProductionSetEntry $productionSetEntry */
        $productionSetEntry = null;
        /** @var SymbolSet $terminalsSet */
        $terminalsSet = null;
        /** @var Production $production */
        $production = null;

        $parseTable = new LLParseTable($nonTerminalsSet, $terminalsSet);
        foreach ($this->predictSets->getEntrySetIterator() as $production => $terminalSet) {
            $productionSetEntry = $this->createProductionSetEntry($production);
            $parseTable->addAllEntries($productionSetEntry, $terminalsSet);
        }

        return $parseTable;
    }

    /**
     * @param Production $production
     * @return ProductionSetEntry
     */
    protected function createProductionSetEntry(Production $production)
    {
        $productionHashKeyFactory = new SimpleHashKeyFactory();
        $hashKey = $productionHashKeyFactory->hash($production);

        return new ProductionSetEntry($hashKey, $production);
    }

    protected function addEntries(Production $production, SymbolSet $terminalsSet,  LLParseTable $parseTable)
    {
        $nonTerminal = $production->getNonTerminal();

        foreach ($terminalsSet as $terminal) {
            $parseTable->addEntry($nonTerminal, $terminal, $production);
        }
    }
}
