<?php namespace Helstern\Nomsky\Grammar\Transformations\EliminateOptionals;

use Helstern\Nomsky\Grammar\Converter\ProductionTransformer;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;
use Helstern\Nomsky\Grammar\Production\StandardProduction;
use Helstern\Nomsky\Grammar\Production\Production;

class Transformer implements ProductionTransformer
{
    /** @var NonTerminalNamingStrategy */
    protected $nonTerminalNamingStrategy;

    /**
     * @param NonTerminalNamingStrategy $nonTerminalNamingStrategy
     */
    public function __construct(NonTerminalNamingStrategy $nonTerminalNamingStrategy)
    {
        $this->nonTerminalNamingStrategy = $nonTerminalNamingStrategy;
    }
    /**
     * @param Production $initialProduction
     * @return array|Production[]
     */
    public function transform(Production $initialProduction)
    {
        /** @var Expression $expression */
        $expression = $initialProduction->getExpression();

        $visitor                    = new OptionalsEliminator($this->nonTerminalNamingStrategy);
        $hierarchicVisitDispatcher  = new CompleteVisitDispatcher($visitor);

        $walker                     = new DepthFirstStackBasedWalker();
        $walker->walk($expression, $hierarchicVisitDispatcher);

        //TODO this cast is a hack, needs investigation
        /** @var ExpressionIterable $visitRoot */
        $visitRoot = $visitor->getRoot();

        $cleanedProduction = new StandardProduction($initialProduction->getNonTerminal(), $visitRoot);
        $cleanedProductionsList = array($cleanedProduction);
        $cleanedProductionsList = array_merge($cleanedProductionsList, $visitor->getEpsilonAlternatives());

        return $cleanedProductionsList;
    }
}
