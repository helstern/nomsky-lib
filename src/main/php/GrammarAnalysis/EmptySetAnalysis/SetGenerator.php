<?php namespace Helstern\Nomsky\GrammarAnalysis\EmptySetsAnalysis;

use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Symbol\ArraySet;
use Helstern\Nomsky\Grammar\Symbol\SymbolSet;
use Helstern\Nomsky\Grammar\Symbol\Symbol;
use Helstern\Nomsky\GrammarAnalysis\Predicates\SymbolIsEpsilon;

class SetGenerator
{
    /**
     * @param Grammar $g
     * @return SymbolSet|Symbol[]
     */
    public function generate(Grammar $g)
    {
        $set = new ArraySet();
        $productions = $g->getProductions();
        $changesDuringLastIteration = null;

        do {
            $changesDuringLastIteration = false;
            foreach ($productions as $production) {
                $nonTerminal = $production->getNonTerminal();

                if ($set->contains($nonTerminal)) {
                    continue;
                }

                //directly generates epsilon
                $directlyGenerates = $production->count() == 1;
                $directlyGenerates &= SymbolIsEpsilon:: singletonInstance()->matchSymbol($production->getFirstSymbol());

                if ($directlyGenerates) {
                    $set->add($nonTerminal);
                    $changesDuringLastIteration = true;
                    continue;
                }

                //indirectly generates epsilon (all from rhs must generate epsilon)
                $rhsItems = $production->getSymbols();
                /** @var Symbol $item */
                $item     = reset($rhsItems);
                $indirectlyGenerates = $set->contains($item);
                for (next($rhsItems); $indirectlyGenerates && !is_null(key($rhsItems)); next($rhsItems)) {
                    $item = current($rhsItems);
                    $indirectlyGenerates = $indirectlyGenerates && $set->contains($item);
                }

                if (is_null(key($rhsItems)) && $indirectlyGenerates) {
                    $set->add($nonTerminal);
                    $changesDuringLastIteration = true;
                }
            }
        } while ($changesDuringLastIteration);

        return $set;
    }
}
