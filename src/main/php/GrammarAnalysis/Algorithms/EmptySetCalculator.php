<?php namespace Helstern\Nomsky\GrammarAnalysis\Algorithms;

use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\GrammarAnalysis\Production\NormalizedProduction;


/**
 * This class generates the empty set of a grammar
 * The empty set is the set of all non terminals that generate the epsilon (empty) symbol
 *
 * The empty set is important because the non-terminals that can derive epsilon may disappear during a parse
 */
class EmptySetCalculator
{
    /**
     * @param SymbolSet $set
     * @param NormalizedProduction $production
     *
     * @return bool
     */
    public function processProduction(SymbolSet $set, NormalizedProduction $production)
    {
        $nonTerminal = $production->getLeftHandSide();
        if ($set->contains($nonTerminal)) {
            return false;
        }

        //directly generates epsilon (rhs is epsilon)
        if ($this->directlyGeneratesEpsilon($production)) {
            $set->add($nonTerminal);
            return true;
        }

        //indirectly generates epsilon (all from rhs must generate epsilon)
        if ($this->indirectlyGeneratesEpsilon($production, $set)) {
            $set->add($nonTerminal);
            return true;
        }

        return false;
    }

    /**
     * @param NormalizedProduction $production
     *
     * @return bool
     */
    private function directlyGeneratesEpsilon(NormalizedProduction $production)
    {
        //directly generates epsilon
        $answer = $production->count() == 1;
        $firstSymbol = $production->getFirstSymbol();
        $answer &= SymbolIsEpsilon:: singletonInstance()->matchSymbol($firstSymbol);

        return $answer;
    }

    /**
     * @param NormalizedProduction $production
     * @param SymbolSet $alreadyAdded
     *
     * @return bool
     */
    private function indirectlyGeneratesEpsilon(NormalizedProduction $production, SymbolSet $alreadyAdded)
    {
        $rhsItems = $production->getRightHandSide();
        /** @var Symbol $item */
        $item     = reset($rhsItems);
        $answer = $alreadyAdded->contains($item);
        for (next($rhsItems); $answer && !is_null(key($rhsItems)); next($rhsItems)) {
            $item = current($rhsItems);
            $answer = $answer && $alreadyAdded->contains($item);
        }

        $answer = is_null(key($rhsItems)) && $answer;
        return $answer;
    }
}
