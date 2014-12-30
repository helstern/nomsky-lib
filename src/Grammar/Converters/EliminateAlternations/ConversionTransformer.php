<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateAlternations;

use Helstern\Nomsky\Grammar\Converters\ProductionTransformer;
use Helstern\Nomsky\Grammar\Expressions\Alternation;
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

        if ($expression instanceof Alternation) {
            $productions = array();

            $expressions = iterator_to_array($expression->getIterator());
            foreach($expressions as $expression) {
                $productions[] = new DefaultProduction($production->getNonTerminal(), $expression);
            }
            return $productions;
        }

        return array($production);
    }
}
