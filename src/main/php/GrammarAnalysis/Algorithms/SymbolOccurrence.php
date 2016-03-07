<?php namespace Helstern\Nomsky\GrammarAnalysis\Algorithms;

use Helstern\Nomsky\Grammar\Symbol\Symbol;

/**
 *  Models an occurrence of a symbol b in the right hand side of a production like A -> abc
 */
class SymbolOccurrence
{
    /** @var array|Symbol[] */
    private $preceding;

    /** @var array|Symbol[] */
    private $following;

    /** @var Symbol */
    private $symbol;

    /** @var Symbol */
    private $productionNonTerminal;

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $symbol
     * @param array|Symbol[] $preceding
     * @param array|Symbol[] $following
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $nonTerminal
     */
    public function __construct(Symbol $symbol, array $preceding, array $following, Symbol $nonTerminal)
    {
        $this->symbol = $symbol;
        $this->preceding = $preceding;
        $this->following = $following;
        $this->productionNonTerminal = $nonTerminal;
    }

    /**
     * @return array|\Helstern\Nomsky\Grammar\Symbol\Symbol[]
     */
    public function getPreceding()
    {
        return $this->preceding;
    }

    /**
     * @return array|\Helstern\Nomsky\Grammar\Symbol\Symbol[]
     */
    public function getFollowing()
    {
        return $this->following;
    }

    /**
     * @return Symbol
     */
    public function getSymbol()
    {
        return $this->symbol;
    }

    /**
     * @return Symbol
     */
    public function getProductionNonTerminal()
    {
        return $this->productionNonTerminal;
    }
}
