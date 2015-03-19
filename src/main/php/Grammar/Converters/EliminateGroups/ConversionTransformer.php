<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateGroups;

use Helstern\Nomsky\Grammar\Converters\ProductionTransformer;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;
use Helstern\Nomsky\Grammar\Production\DefaultProduction;
use Helstern\Nomsky\Grammar\Production\Production;

class ConversionTransformer implements ProductionTransformer
{
    /**
     * @param Production $production
     * @return array|Production[]
     */
    public function transform(Production $production)
    {
        $expression = $production->getExpression();

        $visitor                    = new GroupsEliminator();
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker                     = new DepthFirstStackBasedWalker();
        $walker->walk($expression, $hierarchicVisitDispatcher);
        $rootExpression             = $visitor->getRoot();

        $production = new DefaultProduction($production->getNonTerminal(), $rootExpression);
        return array($production);
    }
}
