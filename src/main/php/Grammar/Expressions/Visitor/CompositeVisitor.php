<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor;

use Helstern\Nomsky\Grammar\Expressions\Alternative;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

interface CompositeVisitor
{
    /**
     * @param Alternative $expression
     * @return boolean
     */
    public function visitAlternation(Alternative $expression);

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
     * @param OptionalList $expression
     * @return boolean
     */
    public function visitOptionalList(OptionalList $expression);

    /**
     * @param OptionalItem $expression
     * @return boolean
     */
    public function visitOptionalItem(OptionalItem $expression);

    /**
     * @param Expression $expression
     * @return boolean
     */
    public function visitExpression(Expression $expression);
}
