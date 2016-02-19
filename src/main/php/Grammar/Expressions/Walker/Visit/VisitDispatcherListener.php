<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\Visit;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;

interface VisitDispatcherListener
{
    /**
     * @param Choice $expression
     *
*@return boolean
     */
    public function beforeDispatchVisitAlternation(Choice $expression);

    /**
     * @param Choice $expression
     * @param VisitAction $dispatchVisitResult
     *
*@return boolean
     */
    public function afterDispatchVisitAlternation(Choice $expression, VisitAction $dispatchVisitResult = null);

    /**
     * @param Expression $expression
     * @return boolean
     */
    public function beforeDispatchVisitExpression(Expression $expression);

    /**
     * @param Expression $expression
     * @param VisitAction $dispatchVisitResult
     * @return boolean
     */
    public function afterDispatchVisitExpression(Expression $expression, VisitAction $dispatchVisitResult = null);

    /**
     * @param Group $expression
     * @return VisitAction|null
     */
    public function beforeDispatchVisitGroup(Group $expression);

    /**
     * @param Group $expression
     * @param VisitAction $dispatchVisitResult
     * @return boolean
     */
    public function afterDispatchVisitGroup(Group $expression, VisitAction $dispatchVisitResult = null);

    /**
     * @param Optional $expression
     * @param VisitAction $dispatchVisitResult
     *
*@return boolean
     */
    public function beforeDispatchVisitOption(Optional $expression, VisitAction $dispatchVisitResult = null);

    /**
     * @param Optional $expression
     * @param VisitAction $dispatchVisitResult
     *
*@return boolean
     */
    public function afterDispatchVisitOption(Optional $expression, VisitAction $dispatchVisitResult = null);

    /**
     * @param Concatenation $expression
     *
*@return VisitAction|null
     */
    public function beforeDispatchVisitSequence(Concatenation $expression);

    /**
     * @param Concatenation $expression
     * @param VisitAction $dispatchVisitResult
     *
*@return boolean
     */
    public function afterDispatchVisitSequence(Concatenation $expression, VisitAction $dispatchVisitResult = null);

    /**
     * @param Repetition $expression
     *
*@return VisitAction|null
     */
    public function beforeDispatchVisitRepetition(Repetition $expression);

    /**
     * @param Repetition $expression
     * @param VisitAction $dispatchVisitResult
     *
*@return boolean
     */
    public function afterDispatchVisitRepetition(Repetition $expression, VisitAction $dispatchVisitResult = null);
}
