<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor\CompositeVisit;

use Helstern\Nomsky\Grammar\Expressions\Alternative;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;

use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

use Helstern\Nomsky\Grammar\Expressions\Visitor\CompositeVisitor;

use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\AbstractDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitDispatcher;

class CompleteVisitDispatcher extends AbstractDispatcher implements VisitDispatcher
{
    /** @var CompositeVisitor  */
    protected $visitor;

    public function __construct(CompositeVisitor $visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * @param Alternative $expression
     * @return null
     */
    public function dispatchVisitAlternation(Alternative $expression)
    {
        $this->visitor->visitAlternation($expression);
        return null;
    }

    /**
     * @param Group $expression
     * @return null
     */
    public function dispatchVisitGroup(Group $expression)
    {
        $this->visitor->visitGroup($expression);
        return null;
    }

    /**
     * @param Expression $expression
     * @return null
     */
    public function dispatchVisitExpression(Expression $expression)
    {
        $this->visitor->visitExpression($expression);
        return null;
    }

    /**
     * @param OptionalItem $expression
     * @return null
     */
    public function dispatchVisitOptionalItem(OptionalItem $expression)
    {
        $this->visitor->visitOptionalItem($expression);
        return null;
    }

    /**
     * @param Sequence $expression
     * @return null
     */
    public function dispatchVisitSequence(Sequence $expression)
    {
        $this->visitor->visitSequence($expression);
        return null;
    }

    /**
     * @param OptionalList $expression
     * @return null
     */
    public function dispatchVisitOptionalList(OptionalList $expression)
    {
        $this->visitor->visitOptionalList($expression);
        return null;
    }
}
