<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseTable;

use Helstern\Nomsky\Grammar\Production;
use Helstern\Nomsky\Grammar\Symbol\ArraySet;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\GrammarAnalysis\ParseSets\LookAheadSets;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;
use Helstern\Nomsky\GrammarAnalysis\Production\SimpleHashKeyFactory;

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
     * @var SimpleHashKeyFactory
     */
    private $productionHashAlgorithm;

    public function __construct()
    {
        $this->productionHashAlgorithm = new SimpleHashKeyFactory();
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

    /**
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\LookAheadSets $sets
     *
     * @return LLParseTableBuilder
     */
    public function addLookAheadSets(LookAheadSets $sets)
    {
        $this->lookAheadSets = $sets;
        return $this;
    }

    /**
     * @return \Helstern\Nomsky\GrammarAnalysis\ParseTable\LLParseTable
     */
    public function build()
    {
        $parseTable = new LLParseTable($this->nonTerminals, $this->terminals, $this->productionHashAlgorithm);

        /** @var NormalizedProduction $production */
        foreach ($this->lookAheadSets->getEntrySetIterator() as $production => $terminalSet) {
            $lhs = $production->getLeftHandSide();
            /** @var Symbol $terminal */
            foreach ($terminalSet as $terminal) {
                $parseTable->add($lhs, $terminal, $production);
            }
        }

        return $parseTable;
    }

}

