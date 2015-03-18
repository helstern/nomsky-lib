<?php namespace Helstern\Nomsky\GrammarAnalysis\FollowSetsAnalysis;

use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolTypeEquals;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class SymbolFollowsNonTerminalPredicate implements SymbolPredicate
{
    /** @var SymbolTypeEquals */
    protected $symbolIsNonTerminal;

    protected $matchedNonTerminal = false;

    public function __construct()
    {
        $this->symbolIsNonTerminal = SymbolTypeEquals::newInstanceMatchingNonTerminals();
    }

    public function matchSymbol(Symbol $symbol)
    {
        if ($this->matchedNonTerminal) {
            return true;
        }

        $this->matchedNonTerminal = $this->symbolIsNonTerminal->matchSymbol($symbol);
        return false;
    }
}
