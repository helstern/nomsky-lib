<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Alternation;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;
use Helstern\Nomsky\Grammar\Grammar;
use Helstern\Nomsky\Grammar\Production\ProductionInterface;
use Helstern\Nomsky\Grammar\Symbol\EpsilonSymbol;

class EbnfToBnf
{
    /** @var  EpsilonSymbol */
    protected $epsilonSymbol;

    public function convert(Grammar $grammar)
    {
        $ebnfRules = $grammar->getRules();
        $bnfRules  = array();
        do {
            $ebnfRule   = array_shift($ebnfRules);
            $toConvert  = $this->eliminateOptionsAndRepetitions($ebnfRule);
            do {
                $rule          = array_pop($toConvert); //todo define where the originating rule is found
                $withoutGroups = $this->eliminateGroups($rule);

                $bnfRules = array_merge($bnfRules, $withoutGroups);
            } while (count($toConvert) > 0);
        } while (!is_null(key($ebnfRules)));

        return $bnfRules;
    }

    /**
     * Removes all the alternation and groups from a rule
     *
     * @param ProductionInterface $ebnfRule
     * @return \Helstern\Nomsky\Grammar\Expressions\Expression[]|null
     */
    public function eliminateGroups(ProductionInterface $ebnfRule)
    {
        /** @var Alternation $expression */
        $expression = $ebnfRule->getExpression();

        $visitor                    = new EliminateGroupsVisitor();
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker                     = new DepthFirstStackBasedWalker();
        $walker->walk($expression, $hierarchicVisitDispatcher);
        $rootExpression             = $visitor->getRoot();

        return array($rootExpression);
    }

    public function eliminateOptionsAndRepetitions(ProductionInterface $ebnfRule)
    {
        /** @var Expression $expression */
        $expression = $ebnfRule->getExpression();

        $visitor                    = new EliminateOptionalsVisitor();
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker                     = new DepthFirstStackBasedWalker();
        $walker->walk($expression, $hierarchicVisitDispatcher);

        $expressions = array($visitor->getRoot());
        $expressions = array_merge($expressions, $visitor->getEpsilonAlternatives());

        return $expressions;
    }
}
