<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Converters\EliminateOptionals\IncrementalNamingStrategy;
use Helstern\Nomsky\Grammar\Converters\EliminateOptionals\OptionalsEliminator;


use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;

use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Production\DefaultProduction;
use Helstern\Nomsky\Grammar\Production\Production;

class EbnfToBnf
{
    /** @var IncrementalNamingStrategy */
    protected $nonTerminalNamingStrategy;

    /**
     * @param Grammar $grammar
     * @return array|Production[]
     */
    public function convert(Grammar $grammar)
    {
        $this->nonTerminalNamingStrategy = $this->createNonTerminalNamingStrategy();

        $ebnfProductionsList = $grammar->getProductions();
        $bnfProductionsList  = array();
        do {
            $ebnfProduction   = array_shift($ebnfProductionsList);
            $intermediateBnfProductionsList = $this->eliminateOptionals($ebnfProduction);

            $intermediateBnfProductionsList = array_reverse($intermediateBnfProductionsList);
            do {
                $intermediateProduction = array_pop($intermediateBnfProductionsList); //todo define where the originating rule is found
                $finalBnfProductionsList = $this->eliminateGroups($intermediateProduction);

                $bnfProductionsList = array_merge($bnfProductionsList, $finalBnfProductionsList);
            } while (count($intermediateBnfProductionsList) > 0);
        } while (!is_null(key($ebnfProductionsList)));

        return $bnfProductionsList;
    }

    /**
     * Removes all the alternation and groups from a rule
     *
     * @param Production $ebnfRule
     * @return \Helstern\Nomsky\Grammar\Expressions\Expression[]|null
     */
    public function eliminateGroups(Production $ebnfRule)
    {
        /** @var Alternation $expression */
        $expression = $ebnfRule->getExpression();

        $visitor                    = new GroupsEliminator();
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker                     = new DepthFirstStackBasedWalker();
        $walker->walk($expression, $hierarchicVisitDispatcher);
        $rootExpression             = $visitor->getRoot();

        $production = new DefaultProduction($ebnfRule->getNonTerminal(), $rootExpression);
        return array($production);
    }

    public function eliminateOptionals(Production $ebnfRule)
    {
        /** @var Expression $expression */
        $expression = $ebnfRule->getExpression();

        $visitor                    = new OptionalsEliminator($this->nonTerminalNamingStrategy);
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker                     = new DepthFirstStackBasedWalker();
        $walker->walk($expression, $hierarchicVisitDispatcher);

        $cleanedProduction = new DefaultProduction($ebnfRule->getNonTerminal(), $visitor->getRoot());
        $cleanedProductionsList = array($cleanedProduction);
        $cleanedProductionsList = array_merge($cleanedProductionsList, $visitor->getEpsilonAlternatives());

        return $cleanedProductionsList;
    }

    protected function createNonTerminalNamingStrategy()
    {
        return new IncrementalNamingStrategy();
    }
}
