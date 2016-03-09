<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Symbol\ArraySet;
use Helstern\Nomsky\Grammar\Symbol\EpsilonSymbol;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

class ParseSets
{
    /** @var array|ArraySet[] */
    private $terminalSets;

    /** @var array|Symbol[] */
    private $nonTerminals;

    /**
     * @param array|Symbol[] $nonTerminals
     */
    public function __construct(array $nonTerminals)
    {
        $this->nonTerminals = $nonTerminals;
        $this->initTerminalSets();
    }

    protected function initTerminalSets()
    {
        $this->terminalSets = array();
        foreach ($this->nonTerminals as $symbol) {
            $this->terminalSets[$symbol->toString()] = new ArraySet();
        }
    }

    /**
     * @return array|Symbol[]
     */
    public function getNonTerminals()
    {
        return $this->nonTerminals;
    }

    /**
     * @param Symbol $nonTerminal
     * @return ArraySet|null
     */
    public function getTerminalSet(Symbol $nonTerminal)
    {
        $key = $nonTerminal->toString();
        if (array_key_exists($key, $this->terminalSets)) {
            return $this->terminalSets[$key];
        }
        return null;
    }

    /**
     * @param Symbol $nonTerminal
     *
     * @return \ArrayIterator|Symbol[]
     * @throws \Exception
     */
    private function getTerminalSetIterator(Symbol $nonTerminal)
    {
        $terminalSet = $this->getTerminalSet($nonTerminal);
        if (is_null($terminalSet)) {
            throw new \Exception('no terminal set found for symbol ' . $nonTerminal->toString());
        }
        return $terminalSet->getIterator();
    }

    /**
     * @param Symbol $nonTerminal
     * @param SymbolPredicate $acceptPredicate
     * @return ArraySet
     */
    public function filterTerminalSet(Symbol $nonTerminal, SymbolPredicate $acceptPredicate)
    {
        $terminalSetIterator = $this->getTerminalSetIterator($nonTerminal);
        $filtered = new ArraySet();
        while ($terminalSetIterator->valid()) {
            $terminal = $terminalSetIterator->current();
            $terminalSetIterator->next();

            if ($acceptPredicate->matchSymbol($terminal)) {
                $filtered->add($terminal);
            }
        }

        return $filtered;
    }

    public function containsEpsilon(Symbol $nonTerminal)
    {
        $terminalSet = $this->getTerminalSet($nonTerminal);
        return $terminalSet->contains(EpsilonSymbol::singletonInstance());
    }

    /**
     * @param Symbol $nonTerminal
     *
     * @return bool
     * @throws \Exception
     */
    public function addEpsilon(Symbol $nonTerminal)
    {
        $terminalSet = $this->getTerminalSet($nonTerminal);
        if (is_null($terminalSet)) {
            throw new \Exception('no terminal set found for symbol ' . $nonTerminal->toString() );
        }
        return $terminalSet->add(EpsilonSymbol::singletonInstance());
    }

    /**
     * @param Symbol $nonTerminal
     * @param Symbol $terminal
     * @return bool
     */
    public function addTerminal(Symbol $nonTerminal, Symbol $terminal)
    {
        $terminalSet = $this->getTerminalSet($nonTerminal);
        return $terminalSet->add($terminal);
    }

    /**
     * @param Symbol $nonTerminal
     * @param SymbolSet $terminals
     * @return bool
     */
    public function addAllTerminals(Symbol $nonTerminal, SymbolSet $terminals)
    {
        $terminalSet = $this->getTerminalSet($nonTerminal);
        return $terminalSet->addAll($terminals);
    }

    /**
     * @param Symbol $nonTerminal
     * @param SymbolSet $terminals
     * @param SymbolPredicate $acceptPredicate
     * @return bool
     */
    public function addSomeTerminals(Symbol $nonTerminal, SymbolSet $terminals, SymbolPredicate $acceptPredicate)
    {
        $terminalSet = $this->getTerminalSet($nonTerminal);
        return $terminalSet->addSome($terminals, $acceptPredicate);
    }
}
