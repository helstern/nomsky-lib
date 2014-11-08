<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Option;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

interface HierarchyVisitor
{
    /**
     * @param Alternation $expression
     * @return boolean
     */
    public function startVisitAlternation(Alternation $expression);

    /**
     * @param Alternation $expression
     * @return boolean
     */
    public function endVisitAlternation(Alternation $expression);

    /**
     * @param Sequence $expression
     * @return boolean
     */
    public function startVisitSequence(Sequence $expression);

    /**
     * @param Sequence $expression
     * @return boolean
     */
    public function endVisitSequence(Sequence $expression);

    /**
     * @param Group $expression
     * @return boolean
     */
    public function startVisitGroup(Group $expression);

    /**
     * @param Group $expression
     * @return boolean
     */
    public function endVisitGroup(Group $expression);

    /**
     * @param Repetition $expression
     * @return boolean
     */
    public function startVisitRepetition(Repetition $expression);

    /**
     * @param Repetition $expression
     * @return boolean
     */
    public function endVisitRepetition(Repetition $expression);

    /**
     * @param Option $expression
     * @return boolean
     */
    public function startVisitOption(Option $expression);

    /**
     * @param Option $expression
     * @return boolean
     */
    public function endVisitOption(Option $expression);

    /**
     * @param Expression $expression
     * @return boolean
     */
    public function visitExpression(Expression $expression);
}
