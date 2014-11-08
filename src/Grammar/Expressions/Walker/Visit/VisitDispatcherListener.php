<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\Visit;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Option;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

interface VisitDispatcherListener
{
    /**
     * @param Alternation $expression
     * @return boolean
     */
    public function beforeDispatchVisitAlternation(Alternation $expression);

    /**
     * @param Alternation $expression
     * @param VisitAction $dispatchVisitResult
     * @return boolean
     */
    public function afterDispatchVisitAlternation(Alternation $expression, VisitAction $dispatchVisitResult = null);

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
     * @param Option $expression
     * @param VisitAction $dispatchVisitResult
     * @return boolean
     */
    public function beforeDispatchVisitOption(Option $expression, VisitAction $dispatchVisitResult = null);

    /**
     * @param Option $expression
     * @param VisitAction $dispatchVisitResult
     * @return boolean
     */
    public function afterDispatchVisitOption(Option $expression, VisitAction $dispatchVisitResult = null);

    /**
     * @param Sequence $expression
     * @return VisitAction|null
     */
    public function beforeDispatchVisitSequence(Sequence $expression);

    /**
     * @param Sequence $expression
     * @param VisitAction $dispatchVisitResult
     * @return boolean
     */
    public function afterDispatchVisitSequence(Sequence $expression, VisitAction $dispatchVisitResult = null);

    /**
     * @param Repetition $expression
     * @return VisitAction|null
     */
    public function beforeDispatchVisitRepetition(Repetition $expression);

    /**
     * @param Repetition $expression
     * @param VisitAction $dispatchVisitResult
     * @return boolean
     */
    public function afterDispatchVisitRepetition(Repetition $expression, VisitAction $dispatchVisitResult = null);
}
