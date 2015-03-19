<?php namespace Helstern\Nomsky\Grammar\Converters\EliminateAlternations;

use Helstern\Nomsky\Grammar\Converters\ProductionTransformer;
use Helstern\Nomsky\Grammar\Expressions\Alternative;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
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

        if ($expression instanceof Alternative) {
            $productions = array();

            $expressions = iterator_to_array($expression->getIterator());
            foreach($expressions as $expression) {
                $expressionIterable = $this->convertToExpressionIterable($expression);
                $productions[] = new DefaultProduction($production->getNonTerminal(), $expressionIterable);
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
