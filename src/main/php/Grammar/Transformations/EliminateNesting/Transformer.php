<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateNesting;

use Helstern\Nomsky\Grammar\Converter\ProductionTransformer;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;
use Helstern\Nomsky\Grammar\Production\Production;
use Helstern\Nomsky\Grammar\Production\StandardProduction;

/**
 * Removes all nestings of the form Concatenation-Concatenation or Choice-Choice
 */
class Transformer implements ProductionTransformer
{
    /**
     * @param Production $production
     * @return array|Production[]
     */
    public function transform(Production $production)
    {
        $expression = $production->getExpression();

        $visitor                    = new Visitor();
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker                     = new DepthFirstStackBasedWalker();
        $walker->walk($expression, $hierarchicVisitDispatcher);
        $rootExpression             = $visitor->getRoot();

        $production = new StandardProduction($production->getNonTerminal(), $rootExpression);
        return array($production);
    }
}
