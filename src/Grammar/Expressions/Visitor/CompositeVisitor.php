<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Option;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

interface CompositeVisitor
{
    /**
     * @param Alternation $expression
     * @return boolean
     */
    public function visitAlternation(Alternation $expression);

    /**
     * @param Group $expression
     * @return boolean
     */
    public function visitGroup(Group $expression);

    /**
     * @param Sequence $expression
     * @return boolean
     */
    public function visitSequence(Sequence $expression);

    /**
     * @param Repetition $expression
     * @return boolean
     */
    public function visitRepetition(Repetition $expression);

    /**
     * @param Option $expression
     * @return boolean
     */
    public function visitOption(Option $expression);

    /**
     * @param Expression $expression
     * @return boolean
     */
    public function visitExpression(Expression $expression);
}
