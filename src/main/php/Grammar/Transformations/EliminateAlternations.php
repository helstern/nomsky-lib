<?php namespace Helstern\Nomsky\Grammar\Transformations;

use Helstern\Nomsky\Grammar\Converter\ProductionTransformer;
use Helstern\Nomsky\Grammar\Expressions\Alternative;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Production\StandardProduction;
use Helstern\Nomsky\Grammar\Production\Production;

class EliminateAlternations implements ProductionTransformer
{
    /**
     * @param Production $production
     * @return array|Production[]
     */
    public function transform(Production $production)
    {
        $expression = $production->getExpression();

        if ($expression instanceof Alternative) {
            $productions = array();

            $expressions = iterator_to_array($expression->getIterator());
            foreach($expressions as $expression) {
                $expressionIterable = $this->convertToExpressionIterable($expression);
                $productions[] = new StandardProduction($production->getNonTerminal(), $expressionIterable);
            }
            return $productions;
        }

        return array($production);
    }

    /**
     * @param Expression $e
     * @return ExpressionIterable|Sequence
     */
    protected function convertToExpressionIterable(Expression $e)
    {
        if ($e instanceof ExpressionIterable) {
            return $e;
        }

        return new Sequence($e);
    }
}
