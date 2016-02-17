<?php namespace Helstern\Nomsky\Grammar\Expressions;

/**
 * Model of a repeated expression
 *
 * @package Helstern\Nomsky\Grammar\Expressions
 */
class Repetition implements Expression, ExpressionAggregate
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
