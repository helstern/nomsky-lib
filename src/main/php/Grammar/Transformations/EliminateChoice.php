<?php namespace Helstern\Nomsky\Grammar\Transformations;

use Helstern\Nomsky\Grammar\Converter\ProductionTransformer;
use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Production\StandardProduction;
use Helstern\Nomsky\Grammar\Production\Production;

class EliminateChoice implements ProductionTransformer
{
    /**
     * @param Production $production
     * @return array|Production[]
     */
    public function transform(Production $production)
    {
        $expression = $production->getExpression();

        if ($expression instanceof Choice) {
            $productions = array();

            $expressions = iterator_to_array($expression->getIterator());
            foreach($expressions as $expression) {
                $productions[] = new StandardProduction($production->getNonTerminal(), $expression);
            }
            return $productions;
        }

        return array($production);
    }
}
