<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;

interface HierarchyVisitor
{
    /**
     * @param Choice $expression
     *
     * @return boolean
     */
    public function startVisitChoice(Choice $expression);

    /**
     * @param Choice $expression
     *
     * @return boolean
     */
    public function endVisitChoice(Choice $expression);

    /**
     * @param Concatenation $expression
     *
     * @return boolean
     */
    public function startVisitConcatenation(Concatenation $expression);

    /**
     * @param Concatenation $expression
     *
     * @return boolean
     */
    public function endVisitConcatenation(Concatenation $expression);

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
     *
     * @return boolean
     */
    public function startVisitRepetition(Repetition $expression);

    /**
     * @param Repetition $expression
     *
     * @return boolean
     */
    public function endVisitRepetition(Repetition $expression);

    /**
     * @param Optional $expression
     *
     * @return boolean
     */
    public function startVisitOptional(Optional $expression);

    /**
     * @param Optional $expression
     *
     * @return boolean
     */
    public function endVisitOptional(Optional $expression);

    /**
     * @param Expression $expression
     * @return boolean
     */
    public function visitExpression(Expression $expression);
}
