<?php namespace Helstern\Nomsky\Grammar\Expressions;

class Group implements Expression, ExpressionAggregate
{
    /** @var Expression */
    protected $expression;

    public function __construct(Expression $expression)
    {
        $this->expression = $expression;
    }

    public function getExpression()
    {
        return $this->expression;
    }
}
