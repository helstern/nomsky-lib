<?php namespace Helstern\Nomsky\GrammarAnalysis\Algorithms;

use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Symbol\EpsilonSymbol;
use Helstern\Nomsky\Grammar\Symbol\Predicate\MatchCountingInterceptor;
use Helstern\Nomsky\Grammar\Symbol\Predicate\Inverter;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolTypeEquals;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\GrammarAnalysis\Predicates\SymbolIsEpsilon;
use Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets;

/**
 * Calculates the first set of an expression
 */
class FirstSetCalculator
{
    /**
     * @var \Helstern\Nomsky\GrammarAnalysis\Predicates\SymbolIsEpsilon
     */
    private $symbolIsEpsilon;

    public function __construct()
    {
        $this->symbolIsEpsilon = SymbolIsEpsilon::singletonInstance();
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\SymbolSet $set
     * @param \Helstern\Nomsky\Grammar\Expressions\Concatenation $expression
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $firstSets
     *
     * @return bool if the epsilon symbol was added to $set
     */
    public function processConcatenation(SymbolSet $set, Concatenation $expression, ParseSets $firstSets)
    {
        $list = $expression->toArray();
        $symbolIsEpsilon = SymbolIsEpsilon::singletonInstance();
        $symbolsIsNonTerminal = SymbolTypeEquals::newInstanceMatchingNonTerminals();

        /** @var SymbolSet $lastSet */
        $lastSet = null;
        $epsilonCounter = new MatchCountingInterceptor($symbolIsEpsilon);

        $lastSymbol = reset($list);
        if ($lastSymbol instanceof Symbol && $symbolsIsNonTerminal->matchSymbol($lastSymbol)) {
            $lastSet = $firstSets->filterTerminalSet(
                $lastSymbol,
                $this->createAcceptTerminalPredicate($epsilonCounter)
            );
            $set->addAll($lastSet);
        } else {
            $set->add($lastSymbol);
        }

        for (
            next($list);
            !is_null(key($list)) && $lastSymbol instanceof Symbol && $symbolsIsNonTerminal->matchSymbol($lastSymbol) && $epsilonCounter->getMatchCount() > 0;
            next($list)
        ) {
            $lastSymbol = current($list);

            $epsilonCounter = new MatchCountingInterceptor($symbolIsEpsilon);
            $lastSet = $firstSets->filterTerminalSet(
                $lastSymbol,
                $this->createAcceptTerminalPredicate($epsilonCounter)
            );

            $set->addAll($lastSet);
        }

        if (
            is_null(key($list))
            && $symbolsIsNonTerminal->matchSymbol($lastSymbol) && $epsilonCounter->getMatchCount() > 0
        ) {
            $set->add(new EpsilonSymbol());
            return true;
        }

        return false;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\SymbolSet $set
     * @param \Helstern\Nomsky\Grammar\Expressions\Expression $expression
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $firstSets
     *
     * @return boolean if the epsilon symbol was added to $set
     */
    public function processExpression(SymbolSet $set, Expression $expression, ParseSets $firstSets)
    {
        $symbolIsEpsilon = SymbolIsEpsilon::singletonInstance();
        $symbolsIsNonTerminal = SymbolTypeEquals::newInstanceMatchingNonTerminals();

        if ($expression instanceof Symbol && $symbolIsEpsilon->matchSymbol($expression)) {
            $set->add(new EpsilonSymbol());
            return true;
        }

        if ($expression instanceof Symbol && $symbolsIsNonTerminal->matchSymbol($expression)) {
            $epsilonCounter = new MatchCountingInterceptor($symbolIsEpsilon);
            $otherSet = $firstSets->filterTerminalSet($expression, $this->createAcceptTerminalPredicate($epsilonCounter));
            $set->addAll($otherSet);

            if ($epsilonCounter->getMatchCount() > 0) {
                $set->add(new EpsilonSymbol());
                return true;
            }

            return false;
        }

        if ($expression instanceof Symbol) {
            $set->add($expression);
            return false;
        }

        return false;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Symbol\Predicate\MatchCountingInterceptor $epsilonMatchCountPredicate
     *
     * @return \Helstern\Nomsky\Grammar\Symbol\Predicate\Inverter
     */
    private function createAcceptTerminalPredicate(MatchCountingInterceptor $epsilonMatchCountPredicate)
    {
        return Inverter::newInstance($epsilonMatchCountPredicate);
    }
}
