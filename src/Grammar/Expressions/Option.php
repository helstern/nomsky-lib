<?php namespace Helstern\Nomsky\Grammar\Expressions;

class Option implements Expression, ExpressionAggregate
{
    /** @var Expression */
    protected $expression;

    /**
     * @param Expression $expression
     */
    public function __construct(Expression $expression)
    {
        $this->expression = $expression;
    }

    public function getExpression()
    {
        return $this->expression;
    }
}
