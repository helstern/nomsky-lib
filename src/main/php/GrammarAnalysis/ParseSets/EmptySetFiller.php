<?php namespace Helstern\Nomsky\GrammarAnalysis\ParseSets;

use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\GrammarAnalysis\Predicates\SymbolIsEpsilon;


/**
 * This class generates the empty set of a grammar
 * The empty set is the set of all non terminals that generate the epsilon (empty) symbol
 *
 * The empty set is important because the non-terminals that can derive epsilon may disappear during a parse
 */
class EmptySetFiller
{
    /**
     * @param SymbolSet $set
     * @param array|Production[] $list
     */
    public function addProductionList(SymbolSet $set, array $list)
    {
        do {
            $changes = false;
            foreach ($list as $production) {
                $changes |= $this->addProduction($set, $production);
            }
        } while ($changes);

    }

    /**
     * @param SymbolSet $set
     * @param Production $production
     *
     * @return bool
     */
    public function addProduction(SymbolSet $set, Production $production)
    {
        $nonTerminal = $production->getNonTerminal();
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
     * @param Production $production
     *
     * @return bool
     */
    private function directlyGeneratesEpsilon(Production $production)
    {
        //directly generates epsilon
        $answer = $production->count() == 1;
        $firstSymbol = $production->getFirstSymbol();
        $answer &= SymbolIsEpsilon:: singletonInstance()->matchSymbol($firstSymbol);

        return $answer;
    }

    /**
     * @param Production $production
     * @param SymbolSet $alreadyAdded
     *
     * @return bool
     */
    private function indirectlyGeneratesEpsilon(Production $production, SymbolSet $alreadyAdded)
    {
        $rhsItems = $production->getSymbols();
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
