<?php namespace Helstern\Nomsky\Grammar\Expressions;

class Option implements Expression, ExpressionAggregate
{
    /** @var Expression */
    protected $expression;

    public function getExpression()
    {
        return $this->expression;
    }
}
