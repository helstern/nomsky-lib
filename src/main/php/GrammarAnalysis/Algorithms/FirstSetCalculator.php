<?php namespace Helstern\Nomsky\GrammarAnalysis\Algorithms;

use Helstern\Nomsky\Grammar\Symbol\EpsilonSymbol;
use Helstern\Nomsky\Grammar\Symbol\Predicate\MatchCountingInterceptor;
use Helstern\Nomsky\Grammar\Symbol\Predicate\Inverter;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolTypeEquals;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;

/**
 * Calculates the first set of an expression
 */
class FirstSetCalculator
{
    /**
     * @var \Helstern\Nomsky\GrammarAnalysis\Algorithms\SymbolIsEpsilon
     */
    private $symbolIsEpsilon;

    public function __construct()
    {
        $this->symbolIsEpsilon = SymbolIsEpsilon::singletonInstance();
    }

    /**
     * Calculates the first set of the symbol $source and adds it's elements to $set
     *
     * @param \Helstern\Nomsky\Grammar\Symbol\SymbolSet $set
     * @param \Helstern\Nomsky\Grammar\Symbol\Symbol $source
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $firstSets
     *
     * @return boolean if the epsilon symbol was added to $set
     */
    public function processSymbol(SymbolSet $set, Symbol $source, ParseSets $firstSets)
    {
        $symbolIsEpsilon = SymbolIsEpsilon::singletonInstance();
        if ($symbolIsEpsilon->matchSymbol($source)) {
            $set->add(new EpsilonSymbol());
            return true;
        }

        $symbolsIsNonTerminal = SymbolTypeEquals::newInstanceMatchingNonTerminals();
        if ($symbolsIsNonTerminal->matchSymbol($source)) {
            $epsilonCounter = new MatchCountingInterceptor($symbolIsEpsilon);
            $acceptPredicate = Inverter::newInstance($epsilonCounter);
            $otherSet = $firstSets->filterTerminalSet($source, $acceptPredicate);
            $set->addAll($otherSet);

            if ($epsilonCounter->getMatchCount() > 0) {
                $set->add(new EpsilonSymbol());
                return true;
            }

            return false;
        }

        $set->add($source);
        return false;
    }

    /**
     * Calculates the first set of $list and adds it's elements to $set
     *
     * @param \Helstern\Nomsky\Grammar\Symbol\SymbolSet $set
     * @param array|NormalizedProduction[] $list
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $firstSets
     *
     * @return bool if the epsilon symbol was added to $set
     */
    public function processSymbolList(SymbolSet $set, array $list, ParseSets $firstSets)
    {
        if (0 == count($list)) {
            $set->add(new EpsilonSymbol());
            return true;
        }

        if (1 == count($list)) {
            /** @var Symbol $symbol */
            $symbol = $list[0];
            return $this->processSymbol($set, $symbol, $firstSets);
        }

        $symbolIsEpsilon = SymbolIsEpsilon::singletonInstance();
        $symbolsIsNonTerminal = SymbolTypeEquals::newInstanceMatchingNonTerminals();

        /** @var SymbolSet $lastSet */
        $lastSet = null;
        $epsilonCounter = new MatchCountingInterceptor($symbolIsEpsilon);

        //we assume there is no epsilon symbol in the list

        $lastSymbol = reset($list);
        if ($symbolsIsNonTerminal->matchSymbol($lastSymbol)) {
            $acceptPredicate = Inverter::newInstance($epsilonCounter);
            $lastSet = $firstSets->filterTerminalSet($lastSymbol, $acceptPredicate);
            $set->addAll($lastSet);
        } else {
            $set->add($lastSymbol);
        }

        for (
            next($list);
            !is_null(key($list)) && $symbolsIsNonTerminal->matchSymbol($lastSymbol) && $epsilonCounter->getMatchCount() > 0;
            next($list)
        ) {
            $lastSymbol = current($list);

            $epsilonCounter = new MatchCountingInterceptor($symbolIsEpsilon);
            $acceptPredicate = Inverter::newInstance($epsilonCounter);
            $lastSet = $firstSets->filterTerminalSet($lastSymbol, $acceptPredicate);

            $set->addAll($lastSet);
        }

        if (
            is_null(key($list))
            && $symbolsIsNonTerminal->matchSymbol($lastSymbol)
            && $epsilonCounter->getMatchCount() > 0
        ) {
            $set->add(new EpsilonSymbol());
            return true;
        }

        return false;
    }
}
