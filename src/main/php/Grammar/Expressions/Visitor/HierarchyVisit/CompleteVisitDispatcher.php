<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit;

use Helstern\Nomsky\Grammar\Expressions\Alternative;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;

use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;


use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;
use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitDispatcher;

use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\AbstractDispatcher;

class CompleteVisitDispatcher extends AbstractDispatcher implements VisitDispatcher
{
    /** @var HierarchyVisitor  */
    protected $visitor;

    /**
     * @param HierarchyVisitor $visitor
     */
    public function __construct(HierarchyVisitor $visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * @param Alternative $expression
     * @return EndAlternationAction
     */
    public function dispatchVisitAlternation(Alternative $expression)
    {
        $this->visitor->startVisitAlternation($expression);

        $nextVisitAction = new EndAlternationAction($expression, $this->visitor);
        return $nextVisitAction;
    }

    /**
     * @param Group $expression
     * @return EndGroupAction
     */
    public function dispatchVisitGroup(Group $expression)
    {
        $this->visitor->startVisitGroup($expression);

        $nextVisitAction = new EndGroupAction($expression, $this->visitor);
        return $nextVisitAction;
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
     * @return EndOptionalItemAction
     */
    public function dispatchVisitOptionalItem(OptionalItem $expression)
    {
        $this->visitor->startVisitOptionalItem($expression);

        $nextVisitAction = new EndOptionalItemAction($expression, $this->visitor);
        return $nextVisitAction;
    }

    /**
     * @param Sequence $expression
     * @return EndSequenceAction
     */
    public function dispatchVisitSequence(Sequence $expression)
    {
        $this->visitor->startVisitSequence($expression);

        $nextVisitAction = new EndSequenceAction($expression, $this->visitor);
        return $nextVisitAction;
    }

    /**
     * @param OptionalList $expression
     * @return EndOptionalListAction
     */
    public function dispatchVisitOptionalList(OptionalList $expression)
    {
        $this->visitor->startVisitOptionalList($expression);

        $nextVisitAction = new EndOptionalListAction($expression, $this->visitor);
        return $nextVisitAction;
    }
}
