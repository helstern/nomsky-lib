<?php namespace Helstern\Nomsky\Grammar\Expressions;

interface ExpressionAggregate
{
    /**
     * @return Expression
     */
    public function getExpression();
}
