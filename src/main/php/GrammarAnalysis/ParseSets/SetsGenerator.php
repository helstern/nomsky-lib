<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Symbol\ArraySet;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\GrammarAnalysis\Algorithms\FirstSetCalculator;
use Helstern\Nomsky\GrammarAnalysis\Algorithms\FollowSetCalculator;
use Helstern\Nomsky\GrammarAnalysis\Algorithms\EmptySetCalculator;
use Helstern\Nomsky\GrammarAnalysis\Algorithms\SymbolOccurrence;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;

class SetsGenerator
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
     * @var EmptySetCalculator
     */
    private $emptySetCalculator;

    /**
     * @param EmptySetCalculator $emptySetGenerator
     * @param FirstSetCalculator $firstSetCalculator
     * @param FollowSetCalculator $followSetCalculator
     */
    public function __construct(
        EmptySetCalculator $emptySetGenerator,
        FirstSetCalculator $firstSetCalculator,
        FollowSetCalculator $followSetCalculator
    ){
        $this->emptySetCalculator = $emptySetGenerator;
        $this->firstSetCalculator = $firstSetCalculator;
        $this->followSetCalculator = $followSetCalculator;
    }

    /**
     * @param array|NormalizedProduction[] $productions
     * @param SymbolSet $emptySet

     * @return \Helstern\Nomsky\GrammarAnalysis\ParseSets\SetsGenerator
     */
    public function generateEmptySet(array $productions, SymbolSet $emptySet)
    {
        do {
            $changes = false;
            foreach ($productions as $production) {
                $changes |= $this->emptySetCalculator->processProduction($emptySet, $production);
            }
        } while ($changes);

        return $this;
    }

    /**
     * @param array|NormalizedProduction[] $productions
     * @param ParseSets $firstSets
     * @param SymbolSet $emptySet
     *
     * @return SetsGenerator
     */
    public function generateFirstSets(
        array $productions,
        ParseSets $firstSets,
        SymbolSet $emptySet
    ) {

        //add epsilon to the first sets of the non terminals which generate epsilon
        foreach ($productions as $production) {
            $lhs = $production->getLeftHandSide();
            if ($emptySet->contains($lhs)) {
                $firstSets->addEpsilon($lhs);
            }
        }

        //initialize the obvious first sets of productions which start with a terminal
        foreach ($productions as $production) {
            $symbol = $production->getFirstSymbol();
            if ($symbol->getType() == Symbol::TYPE_TERMINAL) {
                $firstSets->addTerminal($production->getLeftHandSide(), $symbol);
            }
        }

        //initialize first sets for productions which contain several non-terminals
        do {
            $changes = false;
            foreach ($productions as $production) {

                $updateSet = new ArraySet();
                $rhs = $production->getRightHandSide();
                $this->firstSetCalculator->processSymbolList($updateSet, $rhs, $firstSets);

                $nonTerminal = $production->getLeftHandSide();
                $changes |= $firstSets->addAllTerminals($nonTerminal, $updateSet);

            }
        } while ($changes);

        return $this;
    }

    /**
     * @param array|NormalizedProduction[] $productions
     * @param Symbol $startSymbol
     * @param ParseSets $followSets
     * @param ParseSets $firstSets
     *
     * @return \Helstern\Nomsky\GrammarAnalysis\ParseSets\SetsGenerator
     */
    public function generateFollowSets(
        array $productions,
        Symbol $startSymbol,
        ParseSets $followSets,
        ParseSets $firstSets
    ) {
        $followSets->addEpsilon($startSymbol);

        $occurrences = new \ArrayObject();
        foreach ($productions as $production) {
            $lhs = $production->getLeftHandSide();
            $rhs = $production->getRightHandSide();
            $this->followSetCalculator->addNonTerminalOccurrences($occurrences, $lhs, $rhs);
        }

        do {
            $changes = false;
            /** @var SymbolOccurrence $occurrence */
            foreach ($occurrences as $occurrence) {
                $set = new ArraySet();
                if ($this->followSetCalculator->processOccurrence($set, $occurrence, $followSets, $firstSets)) {
                    $lhs = $occurrence->getProductionNonTerminal();
                    $changes |= $followSets->addAllTerminals($lhs, $set);
                }
            }
        } while ($changes);

        return $this;
    }

    /**
     * @param array|NormalizedProduction[] $productions
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $followSets
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $firstSets
     */
    public function generateLookAheadSets(
        array $productions,
        ParseSets $followSets,
        ParseSets $firstSets
    ) {

    }

}
