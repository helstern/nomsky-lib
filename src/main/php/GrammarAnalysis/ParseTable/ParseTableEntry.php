<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseTable;

use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedSet;

class ParseTableEntry
{
    /** @var Symbol */
    private $terminal;

    /** @var Symbol */
    private $nonTerminal;

    /** @var NormalizedSet */
    private $productionSet;

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $nonTerminal
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $terminal
     * @param \Helstern\Nomsky\GrammarAnalysis\Production\NormalizedSet $productionSet
     */
    public function __construct(Symbol $nonTerminal, Symbol $terminal, NormalizedSet $productionSet)
    {
        $this->nonTerminal = $nonTerminal;
        $this->terminal = $terminal;
        $this->productionSet = $productionSet;
    }

    /**
     * @return Symbol
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * @return Symbol
     */
    public function getNonTerminal()
    {
        return $this->nonTerminal;
    }

    /**
     * @return NormalizedSet
     */
    public function getProductionSet()
    {
        return $this->productionSet;
    }
}
