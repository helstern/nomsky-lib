<?php namespace Helstern\Nomsky\GrammarAnalysis\PredictSetsAnalysis;

use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Production\HashKey\SimpleHashKeyFactory;
use Helstern\Nomsky\Grammar\Symbol\Predicate\MatchCountingInterceptor;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;
use Helstern\Nomsky\Grammar\Symbol\Symbol;

use Helstern\Nomsky\GrammarAnalysis\Algorithms\ArbitraryStringFirstSet;
use Helstern\Nomsky\GrammarAnalysis\Predicates\SymbolIsEpsilon;
use Helstern\Nomsky\GrammarAnalysis\Sets\LookAheadSets;
use Helstern\Nomsky\GrammarAnalysis\Sets\PredictiveParsingSets;

class SetGenerator
{
    /** @var  PredictiveParsingSets */
    protected $firstSets;

    /** @var  PredictiveParsingSets */
    protected $followSets;

    public function __construct(PredictiveParsingSets $firstSets, $followSets)
    {
        $this->firstSets = $firstSets;
        $this->followSets = $followSets;
    }

    public function generate(Grammar $g)
    {
        $lookAheadSets = $this->createLookAheadSets();

        $productions = $g->getProductions();
        foreach ($productions as $production) {
            $lhs = $production->getNonTerminal();
            $expression = $production->getExpression();
            $rhs = $expression->toArray();

            $predictSet = $this->computePredictSet($lhs, $rhs);
            $lookAheadSets->add($production, $predictSet);
        }

        return $lookAheadSets;
    }

    /**
     * @param Symbol $lhs
     * @param array|Symbol[] $rhs
     * @return \Helstern\Nomsky\Grammar\Symbol\ArraySet
     */
    protected function computePredictSet(Symbol $lhs, array $rhs)
    {
        $symbolIsEpsilon = SymbolIsEpsilon::singletonInstance();
        $firstSetExceptionFilter = new MatchCountingInterceptor($symbolIsEpsilon);
        $predictSet = $this->computeFirstSet($rhs, $firstSetExceptionFilter);

        if ($firstSetExceptionFilter->getMatchCount()) {
            $followSet = $this->followSets->getTerminalSet($lhs);
            $predictSet->addAll($followSet);
        }

        return $predictSet;
    }

    /**
     * @param array|Symbol[] $rhs
     * @param SymbolPredicate $rejectSymbolPredicate
     * @return \Helstern\Nomsky\Grammar\Symbol\ArraySet
     */
    protected function computeFirstSet(array $rhs, SymbolPredicate $rejectSymbolPredicate)
    {
        $firstSetCreator = new ArbitraryStringFirstSet($rejectSymbolPredicate);
        $firstSet = $firstSetCreator->compute($rhs, $this->firstSets);
        return $firstSet;
    }

    /**
     * @return LookAheadSets
     */
    protected function createLookAheadSets()
    {
        $hashFactory = new SimpleHashKeyFactory();
        return new LookAheadSets($hashFactory);
    }
}

