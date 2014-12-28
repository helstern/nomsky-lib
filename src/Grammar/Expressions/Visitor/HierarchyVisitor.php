<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
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
     * @param OptionalList $expression
     * @return boolean
     */
    public function startVisitOptionalList(OptionalList $expression);

    /**
     * @param OptionalList $expression
     * @return boolean
     */
    public function endVisitOptionalList(OptionalList $expression);

    /**
     * @param OptionalItem $expression
     * @return boolean
     */
    public function startVisitOptionalItem(OptionalItem $expression);

    /**
     * @param OptionalItem $expression
     * @return boolean
     */
    public function endVisitOptionalItem(OptionalItem $expression);

    /**
     * @param Expression $expression
     * @return boolean
     */
    public function visitExpression(Expression $expression);
}
