<?php namespace Helstern\Nomsky\Grammar\Expressions;

class Repetition implements Expression, ExpressionAggregate
{
    /** @var Expression */
    protected $expression;

    public function getExpression()
    {
        return $this->expression;
    }
}
