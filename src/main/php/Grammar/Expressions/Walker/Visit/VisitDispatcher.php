<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\Visit;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;

interface VisitDispatcher
{
    /**
     * @param Choice $expression
     *
*@return VisitAction|null
     */
    public function dispatchVisitChoice(Choice $expression);

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
     * @param Optional $expression
     *
*@return VisitAction|null
     */
    public function dispatchVisitOptional(Optional $expression);

    /**
     * @param Concatenation $expression
     *
*@return VisitAction|null
     */
    public function dispatchVisitConcatenation(Concatenation $expression);

    /**
     * @param Repetition $expression
     *
*@return VisitAction|null
     */
    public function dispatchVisitRepetition(Repetition $expression);
}
