<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\Visit;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

/**
 * Abstract implementation providing empty hooks for dispatch methods
 *
 * @package Helstern\Nomsky\Grammar\Expressions\Walker
 */
abstract class AbstractDispatcher implements VisitDispatcher
{
    /**
     * @param Alternation $expression
     * @return null
     */
    public function dispatchVisitAlternation(Alternation $expression)
    {
        return null;
    }

    /**
     * @param Expression $expression
     * @return null
     */
    public function dispatchVisitExpression(Expression $expression)
    {
        return null;
    }

    /**
     * @param Group $expression
     * @return null
     */
    public function dispatchVisitGroup(Group $expression)
    {
        return null;
    }

    /**
     * @param OptionalItem $expression
     * @return null
     */
    public function dispatchVisitOptionalItem(OptionalItem $expression)
    {
        return null;
    }

    /**
     * @param Sequence $expression
     * @return null
     */
    public function dispatchVisitSequence(Sequence $expression)
    {
        return null;
    }

    /**
     * @param OptionalList $expression
     * @return null
     */
    public function dispatchVisitOptionalList(OptionalList $expression)
    {
        return null;
    }
}
