<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\Visit;

use Helstern\Nomsky\Grammar\Expressions\Alternative;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

interface VisitDispatcher
{
    /**
     * @param Alternative $expression
     * @return VisitAction|null
     */
    public function dispatchVisitAlternation(Alternative $expression);

    /**
     * @param Expression $expression
     * @return VisitAction|null
     */
    public function dispatchVisitExpression(Expression $expression);

    /**
     * @param Group $expression
     * @return VisitAction|null
     */
    public function dispatchVisitGroup(Group $expression);

    /**
     * @param OptionalItem $expression
     * @return VisitAction|null
     */
    public function dispatchVisitOptionalItem(OptionalItem $expression);

    /**
     * @param Sequence $expression
     * @return VisitAction|null
     */
    public function dispatchVisitSequence(Sequence $expression);

    /**
     * @param OptionalList $expression
     * @return VisitAction|null
     */
    public function dispatchVisitOptionalList(OptionalList $expression);
}
