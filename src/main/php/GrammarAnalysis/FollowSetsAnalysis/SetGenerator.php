<?php namespace Helstern\Nomsky\GrammarAnalysis\FollowSetsAnalysis;

use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Production\Production;

use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\Grammar\Symbol\Predicate\MatchCountingInterceptor;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolTypeEquals;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;

use Helstern\Nomsky\GrammarAnalysis\Algorithms\ArbitraryStringFirstSet;
use Helstern\Nomsky\GrammarAnalysis\Predicates\SymbolIsEpsilon;
use Helstern\Nomsky\GrammarAnalysis\Sets\PredictiveParsingSets;

class SetGenerator
{
    /** @var PredictiveParsingSets */
    protected $firstSets;

    /**
     * @param PredictiveParsingSets $firstSets
     */
    public function __construct(PredictiveParsingSets $firstSets)
    {
        $this->firstSets = $firstSets;
    }

    /**
     * @param Grammar $g
     * @return PredictiveParsingSets
     */
    public function generate(Grammar $g)
    {
        $followSets = $this->createSets($g);

        $productions = $this->filterSetGeneratingProductions($g);

        $startSymbol = $g->getStartSymbol();
        $followSets->addEpsilon($startSymbol);

        $symbolIsNonTerminal = SymbolTypeEquals::newInstanceMatchingNonTerminals();
        $symbolIsEpsilon = SymbolIsEpsilon::singletonInstance();

        do {
            $changes = false;
            foreach ($productions as $production) {
                /** @var array|Symbol[] $rhsSymbols */
                $rhsSymbols = null;
                $expression = $production->getExpression();
                $rhsSymbols = $expression->toArray();

                $nonTerminal = $production->getNonTerminal();
                $index = 0;
                while ($index < count($rhsSymbols)) {

                    $symbol = $rhsSymbols[$index];
                    if (!$symbolIsNonTerminal->matchSymbol($symbol)) {
                        $index++;
                        continue;
                    }

                    $suffix = array_slice($rhsSymbols, $index);
                    $index++;

                    $firstSetExceptionFilter    = new MatchCountingInterceptor($symbolIsEpsilon);
                    $updateSet                  = $this->computeFirstSet($suffix, $firstSetExceptionFilter);
                    $changes                    |= $followSets->addAllTerminals($symbol, $updateSet);

                    if ($firstSetExceptionFilter->getMatchCount()) {
                        $updateSet  = $followSets->getTerminalSet($nonTerminal);
                        $changes    |= $followSets->addAllTerminals($symbol, $updateSet);
                    }
                }
            }
        } while ($changes);

        return $followSets;
    }

    /**
     * @param Grammar $g
     * @return array|Production[]
     */
    protected function filterSetGeneratingProductions(Grammar $g)
    {
        $productions = $g->getProductions();
        $filtered = array();

        $predicate = new SymbolFollowsNonTerminalPredicate();
        foreach ($productions as $production) {
            if ($production->findFirst($predicate)) {
                $filtered[] = $production;
            }
        }

        return $filtered;
    }

    /**
     * @param Grammar $g
     * @return PredictiveParsingSets
     */
    protected function createSets(Grammar $g)
    {
        $nonTerminals = $g->getNonTerminals();
        $sets = new PredictiveParsingSets($nonTerminals);

        return $sets;
    }

    /**
     * @param array|Symbol[] $listOfSymbols
     * @param SymbolPredicate $rejectSymbolsPredicate
     * @return SymbolSet
     */
    protected function computeFirstSet(array $listOfSymbols, SymbolPredicate $rejectSymbolsPredicate)
    {
        $firstSetComputer = new ArbitraryStringFirstSet($rejectSymbolsPredicate);
        $firstSet = $firstSetComputer->compute($listOfSymbols, $this->firstSets);

        return $firstSet;
    }
}
