<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\Visit;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
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
     * @param OptionalItem $expression
     * @param VisitAction $dispatchVisitResult
     * @return boolean
     */
    public function beforeDispatchVisitOption(OptionalItem $expression, VisitAction $dispatchVisitResult = null);

    /**
     * @param OptionalItem $expression
     * @param VisitAction $dispatchVisitResult
     * @return boolean
     */
    public function afterDispatchVisitOption(OptionalItem $expression, VisitAction $dispatchVisitResult = null);

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
     * @param OptionalList $expression
     * @return VisitAction|null
     */
    public function beforeDispatchVisitRepetition(OptionalList $expression);

    /**
     * @param OptionalList $expression
     * @param VisitAction $dispatchVisitResult
     * @return boolean
     */
    public function afterDispatchVisitRepetition(OptionalList $expression, VisitAction $dispatchVisitResult = null);
}
