<?php namespace Helstern\Nomsky\Grammar\Expressions;

/**
 * Representation of an optional expression
 *
 * @package Helstern\Nomsky\Grammar\Expressions
 */
class OptionalItem implements Expression, ExpressionAggregate
{
    /** @var Expression */
    protected $expression;

    /**
     * @param Expression $expression the optional expression
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
