<?php namespace Helstern\Nomsky\Grammar\Expressions;

/**
 * Representation of an optional repetition of an expression
 *
 * @package Helstern\Nomsky\Grammar\Expressions
 */
class OptionalList implements Expression, ExpressionAggregate
{
    /** @var Expression */
    protected $expression;

    /**
     * @param Expression $expression the optional repeated expression
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
