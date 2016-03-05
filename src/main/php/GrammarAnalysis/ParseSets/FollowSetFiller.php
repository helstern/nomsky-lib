<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Production\Production;

use Helstern\Nomsky\Grammar\Symbol\ArraySet;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

use Helstern\Nomsky\GrammarAnalysis\Algorithms\FirstSetCalculator;
use Helstern\Nomsky\GrammarAnalysis\Algorithms\FollowSetCalculator;

/**
 * This class generates the follow sets for a grammar
 * The follow set is computed for a non-terminal and represents the set of terminals that can follow A
 *
 * The follow sets are used to produce the predict set of grammar and the ll parse table
 * Follow sets are useful because they define the right context for a given non-terminal
 */
class FollowSetFiller
{
    /**
     * @var FirstSetCalculator
     */
    private $firstSetCalculator;

    /**
     * @var FollowSetCalculator
     */
    private $followSetCalculator;

    /**
     * @param \Helstern\Nomsky\GrammarAnalysis\Algorithms\FirstSetCalculator $firstSetCalculator
     * @param \Helstern\Nomsky\GrammarAnalysis\Algorithms\FollowSetCalculator $followSetCalculator
     */
    public function __construct(FirstSetCalculator $firstSetCalculator, FollowSetCalculator $followSetCalculator)
    {
        $this->firstSetCalculator = $firstSetCalculator;
        $this->followSetCalculator = $followSetCalculator;
    }

    /**
     * Adds the epsilon symbol to the follow set of $symbol
     *
     * @param ParseSets $followSets
     * @param Symbol $forSymbol
     *
     * @return bool
     * @throws \Exception
     */
    public function addEpsilon(ParseSets $followSets, Symbol $forSymbol)
    {
        $followSets->addEpsilon($forSymbol);
        return true;
    }

    /**
     * @param ParseSets $followSets
     * @param ParseSets $firstSets
     * @param Production $production
     *
     * @return bool true of something new was added to $followSets
     */
    public function addProduction(
        ParseSets $followSets,
        ParseSets $firstSets,
        Production $production
    ) {
        $expression = $production->getExpression();

        if ($expression instanceof Concatenation) {
            $otherProductions = $this->followSetCalculator->processConcatenation($expression);
        } else {
            $otherProductions = $this->followSetCalculator->processExpressionAsList($expression);
        }

        $changes = false;
        /** @var Production $otherProduction */
        $otherProduction = null;
        foreach ($otherProductions as $otherProduction) {

            $expression = $otherProduction->getExpression();
            $updateSet = new ArraySet();
            if ($expression instanceof Concatenation) {
                $epsilonAdded = $this->firstSetCalculator->processConcatenation($updateSet, $expression, $firstSets);
            } else {
                $epsilonAdded = $this->firstSetCalculator->processExpression($updateSet, $expression, $firstSets);
            }

            $otherNonTerminal = $otherProduction->getNonTerminal();
            $changes = $followSets->addAllTerminals($otherNonTerminal, $updateSet);

            if ($epsilonAdded) {
                $ruleSymbol = $production->getNonTerminal();
                $updateSet  = $followSets->getTerminalSet($ruleSymbol);
                $changes    |= $followSets->addAllTerminals($otherNonTerminal, $updateSet);
            }
        }

        return $changes;
    }

    /**
     * @param ParseSets $followSets
     * @param ParseSets $firstSets
     * @param array|Production[] $list
     *
     * @return bool
     */
    public function addProductionList(
        ParseSets $followSets,
        ParseSets $firstSets,
        array $list
    ) {
        do {
            $changes = false;
            foreach ($list as $production) {
                $changes |= $this->addProduction($followSets, $firstSets, $production);
            }
        } while ($changes);

        return $changes;
    }
}
