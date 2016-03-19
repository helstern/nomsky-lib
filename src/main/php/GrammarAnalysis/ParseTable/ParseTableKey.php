<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseTable;

use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedSet;

class ParseTableKey
{
    /** @var Symbol */
    private $terminal;

    /** @var Symbol */
    private $nonTerminal;

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $nonTerminal
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $terminal
     */
    public function __construct(Symbol $nonTerminal, Symbol $terminal)
    {
        $this->nonTerminal = $nonTerminal;
        $this->terminal = $terminal;
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
     * @return string
     */
    public function toHash()
    {
        $string = $this->nonTerminal->toString() . $this->terminal->toString();
        $hash = md5($string);
        return $hash;
    }
}
