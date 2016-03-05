<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;

class SetsGenerator
{
    /**
     * @var EmptySetFiller
     */
    private $emptySetGenerator;

    /**
     * @var FirstSetFiller
     */
    private $firstSetGenerator;

    /**
     * @var FollowSetFiller
     */
    private $followSetGenerator;

    /**
     * @param EmptySetFiller $emptySetGenerator
     * @param FirstSetFiller $firstSetGenerator
     * @param FollowSetFiller $followSetGenerator
     */
    public function __construct(
        EmptySetFiller $emptySetGenerator,
        FirstSetFiller $firstSetGenerator,
        FollowSetFiller $followSetGenerator
    ){
        $this->emptySetGenerator = $emptySetGenerator;
        $this->firstSetGenerator = $firstSetGenerator;
        $this->followSetGenerator = $followSetGenerator;
    }

    /**
     * @param Grammar $g
     * @param SymbolSet $emptySet
     *
     * @return SetsGenerator
     */
    public function generateEmptySet(Grammar $g, SymbolSet $emptySet)
    {
        $productions = $g->getProductions();
        $this->emptySetGenerator->addProductionList($emptySet, $productions);
        return $this;
    }

    /**
     * @param Grammar $g
     * @param ParseSets $firstSets
     * @param SymbolSet $emptySet
     *
     * @return SetsGenerator
     */
    public function generateFirstSets(
        Grammar $g,
        ParseSets $firstSets,
        SymbolSet $emptySet
    ) {
        //add epsilon to the first sets of the non terminals which generate epsilon
        $nonTerminals = $g->getNonTerminals();
        $this->firstSetGenerator->addEpsilonNonTerminals($firstSets, $emptySet, $nonTerminals);

        //process all the productions which do not generate epsilon directly
        $productions = $g->getProductions();
        $this->firstSetGenerator->addProductionList($firstSets, $productions);
        return $this;
    }

    /**
     * @param Grammar $g
     * @param ParseSets $followSets
     * @param ParseSets $firstSets
     *
     * @return SetsGenerator
     */
    public function generateFollowSets(
        Grammar $g,
        ParseSets $followSets,
        ParseSets $firstSets
    ) {
        $startSymbol = $g->getStartSymbol();
        $this->followSetGenerator->addEpsilon($followSets, $startSymbol);

        $productions = $g->getProductions();
        $this->followSetGenerator->addProductionList($followSets, $firstSets, $productions);

        return $this;
    }

}
