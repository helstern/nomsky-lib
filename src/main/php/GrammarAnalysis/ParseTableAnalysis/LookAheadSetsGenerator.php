<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseTableAnalysis;

use Helstern\Nomsky\Grammar\Expressions\Concatenation;
use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\ArraySet;

use Helstern\Nomsky\GrammarAnalysis\Algorithms\FirstSetCalculator;
use Helstern\Nomsky\GrammarAnalysis\ParseSets;

class LookAheadSetsGenerator
{
    /**
     * @var FirstSetCalculator
     */
    private $firstSetCalculator;

    /**
     * @var ParseSets\SetsGenerator
     */
    private $setsGenerator;

    /**
     * @param \Helstern\Nomsky\GrammarAnalysis\Algorithms\FirstSetCalculator $firstSetCalculator
     * @param ParseSets\SetsGenerator $setsGenerator
     */
    public function __construct(FirstSetCalculator $firstSetCalculator, ParseSets\SetsGenerator $setsGenerator)
    {
        $this->firstSetCalculator = $firstSetCalculator;
        $this->setsGenerator = $setsGenerator;
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Grammar $g
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseTableAnalysis\LookAheadSets $lookAheadSets
     *
     * @return $this
     */
    public function generate(Grammar $g, LookAheadSets $lookAheadSets)
    {
        $nonTerminals = $g->getNonTerminals();
        $firstSets = new ParseSets\ParseSets($nonTerminals);
        $followSets = new ParseSets\ParseSets($nonTerminals);

        $this->generateParseSets($g, $firstSets, $followSets);

        $productions = $g->getProductions();
        foreach ($productions as $production) {
            $predictSet = $this->computePredictSet($production, $firstSets, $followSets);
            $lookAheadSets->add($production, $predictSet);
        }

        return $this;
    }

    private function generateParseSets(Grammar $g, ParseSets\ParseSets $firstSets, ParseSets\ParseSets $followSets)
    {
        $emptySet = new ArraySet();
        $this->setsGenerator->generateEmptySet($g, $emptySet);
        $this->setsGenerator->generateFirstSets($g, $firstSets, $emptySet);
        $this->setsGenerator->generateFollowSets($g, $followSets, $firstSets);
    }

    /**
     * @param \Helstern\Nomsky\Grammar\Production\Production $production
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $firstSets
     * @param \Helstern\Nomsky\GrammarAnalysis\ParseSets\ParseSets $followSets
     *
     * @return \Helstern\Nomsky\Grammar\Symbol\ArraySet
     */
    private function computePredictSet(Production $production, ParseSets\ParseSets $firstSets, ParseSets\ParseSets $followSets)
    {
        $set = new ArraySet();
        $expression = $production->getExpression();
        if ($expression instanceof Concatenation) {
            $epsilonAdded = $this->firstSetCalculator->processConcatenation($set, $expression, $firstSets);
        } else {
            $epsilonAdded = $this->firstSetCalculator->processExpression($set, $expression, $firstSets);
        }

        if ($epsilonAdded) {
            $lhs = $production->getNonTerminal();
            $followSet = $followSets->getTerminalSet($lhs);
            $set->addAll($followSet);
        }

        return $set;
    }
}

