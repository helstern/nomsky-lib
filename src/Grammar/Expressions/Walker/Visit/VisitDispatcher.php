<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\Visit;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Option;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

interface VisitDispatcher
{
    /**
     * @param Alternation $expression
     * @return VisitAction|null
     */
    public function dispatchVisitAlternation(Alternation $expression);

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
     * @param Option $expression
     * @return VisitAction|null
     */
    public function dispatchVisitOption(Option $expression);

    /**
     * @param Sequence $expression
     * @return VisitAction|null
     */
    public function dispatchVisitSequence(Sequence $expression);

    /**
     * @param Repetition $expression
     * @return VisitAction|null
     */
    public function dispatchVisitRepetition(Repetition $expression);
}
