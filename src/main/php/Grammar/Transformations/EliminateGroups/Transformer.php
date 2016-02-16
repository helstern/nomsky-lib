<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateGroups;

use Helstern\Nomsky\Grammar\Converter\ProductionTransformer;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;
use Helstern\Nomsky\Grammar\Production\StandardProduction;
use Helstern\Nomsky\Grammar\Production\Production;

class Transformer implements ProductionTransformer
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

        $production = new StandardProduction($production->getNonTerminal(), $rootExpression);
        return array($production);
    }
}
