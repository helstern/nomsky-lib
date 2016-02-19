<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;

interface CompositeVisitor
{
    /**
     * @param Choice $expression
     *
     * @return boolean
     */
    public function visitChoice(Choice $expression);

    /**
     * @param Group $expression
     * @return boolean
     */
    public function visitGroup(Group $expression);

    /**
     * @param Concatenation $expression
     *
     * @return boolean
     */
    public function visitConcatenation(Concatenation $expression);

    /**
     * @param Repetition $expression
     *
     * @return boolean
     */
    public function visitRepetition(Repetition $expression);

    /**
     * @param Optional $expression
     *
     * @return boolean
     */
    public function visitOptional(Optional $expression);

    /**
     * @param Expression $expression
     * @return boolean
     */
    public function visitExpression(Expression $expression);
}
