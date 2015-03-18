<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateOptionals;

use Helstern\Nomsky\Grammar\Converters\ProductionTransformer;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit\CompleteVisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\DepthFirstStackBasedWalker;
use Helstern\Nomsky\Grammar\Production\DefaultProduction;
use Helstern\Nomsky\Grammar\Production\Production;

class ConversionTransformer implements ProductionTransformer
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

        $cleanedProduction = new DefaultProduction($initialProduction->getNonTerminal(), $visitor->getRoot());
        $cleanedProductionsList = array($cleanedProduction);
        $cleanedProductionsList = array_merge($cleanedProductionsList, $visitor->getEpsilonAlternatives());

        return $cleanedProductionsList;
    }
}
