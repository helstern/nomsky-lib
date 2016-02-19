<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\Visit;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;

/**
 * Abstract implementation providing empty hooks for dispatch methods
 *
 * @package Helstern\Nomsky\Grammar\Expressions\Walker
 */
abstract class AbstractDispatcher implements VisitDispatcher
{
    /**
     * @param Choice $expression
     *
*@return null
     */
    public function dispatchVisitChoice(Choice $expression)
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
     * @param Optional $expression
     *
*@return null
     */
    public function dispatchVisitOptional(Optional $expression)
    {
        return null;
    }

    /**
     * @param Concatenation $expression
     *
*@return null
     */
    public function dispatchVisitConcatenation(Concatenation $expression)
    {
        return null;
    }

    /**
     * @param Repetition $expression
     *
*@return null
     */
    public function dispatchVisitRepetition(Repetition $expression)
    {
        return null;
    }
}
