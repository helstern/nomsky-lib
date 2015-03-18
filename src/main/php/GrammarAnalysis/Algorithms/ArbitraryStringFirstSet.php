<?php namespace Helstern\Nomsky\GrammarAnalysis\Algorithms;

use Helstern\Nomsky\Grammar\Symbol\ArraySet;
use Helstern\Nomsky\Grammar\Symbol\EpsilonSymbol;
use Helstern\Nomsky\Grammar\Symbol\Predicate\AndPredicate;
use Helstern\Nomsky\Grammar\Symbol\Predicate\MatchCountingInterceptor;
use Helstern\Nomsky\Grammar\Symbol\Predicate\PredicateInverter;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolTypeEquals;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\GrammarAnalysis\Predicates\SymbolIsEpsilon;
use Helstern\Nomsky\GrammarAnalysis\Sets\PredictiveParsingSets;

class ArbitraryStringFirstSet
{
    /** @var null|SymbolPredicate */
    protected $rejectSymbolsPredicate;

    /**
     * @param SymbolPredicate $rejectSymbolsPredicate
     */
    public function __construct(SymbolPredicate $rejectSymbolsPredicate = null)
    {
        $this->rejectSymbolsPredicate = $rejectSymbolsPredicate;
    }

    /**
     * @return SymbolPredicate|null
     */
    public function getRejectSymbolsPredicate()
    {
        return $this->rejectSymbolsPredicate;
    }

    /**
     * @param array $listOfSymbols
     * @param PredictiveParsingSets $nonTerminalFirstSets
     * @return ArraySet
     */
    public function compute(array $listOfSymbols, PredictiveParsingSets $nonTerminalFirstSets)
    {
        $symbolIsEpsilon = SymbolIsEpsilon::singletonInstance();
        $symbolsIsNonTerminal = SymbolTypeEquals::newInstanceMatchingNonTerminals();

        $set = new ArraySet();
        /** @var ArraySet $lastSet */
        $lastSet = null;
        $epsilonCounter = new MatchCountingInterceptor($symbolIsEpsilon);

        /** @var Symbol $lastSymbol */
        $lastSymbol = reset($listOfSymbols);
        if ($symbolsIsNonTerminal->matchSymbol($lastSymbol)) {
            $lastSet = $nonTerminalFirstSets->filterTerminalSet(
                $lastSymbol,
                $this->getTerminalSetFilterPredicate($epsilonCounter)
            );
            $set->addAll($lastSet);
        } elseif ($this->symbolIsAllowedInFirstSet($lastSymbol)) {
            $set->add($lastSymbol);
        }

        for (
            next($listOfSymbols);
            !is_null(key($listOfSymbols)) && $symbolsIsNonTerminal->matchSymbol($lastSymbol) && $epsilonCounter->getMatchCount() > 0;
            next($listOfSymbols)
        ) {
            $lastSymbol = current($listOfSymbols);

            $epsilonCounter = new MatchCountingInterceptor($symbolIsEpsilon);
            $lastSet = $nonTerminalFirstSets->filterTerminalSet(
                $lastSymbol,
                $this->getTerminalSetFilterPredicate($epsilonCounter)
            );

            $set->addAll($lastSet);
        }

        if (
            is_null(key($listOfSymbols)) &&
            $symbolsIsNonTerminal->matchSymbol($lastSymbol) && $epsilonCounter->getMatchCount() > 0 &&
            $this->symbolIsAllowedInFirstSet(new EpsilonSymbol())
        ) {
            $set->add(new EpsilonSymbol());
        }

        return $set;
    }

    /**
     * @param Symbol $symbol
     * @return bool
     */
    protected function symbolIsAllowedInFirstSet(Symbol $symbol)
    {
        if (!is_null($this->rejectSymbolsPredicate)) {
            return !$this->rejectSymbolsPredicate->matchSymbol($symbol);
        }

        return true;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Predicate\MatchCountingInterceptor $epsilonMatchCountPredicate
     * @return SymbolPredicate|AndPredicate
     */
    protected function getTerminalSetFilterPredicate(MatchCountingInterceptor $epsilonMatchCountPredicate)
    {
        if (is_null($this->rejectSymbolsPredicate)) {
            return PredicateInverter::newInstance($epsilonMatchCountPredicate);
        }

        $list = array(
            PredicateInverter::newInstance($epsilonMatchCountPredicate),
            PredicateInverter::newInstance($this->rejectSymbolsPredicate)
        );
        return new AndPredicate($list);
    }
}
