<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;

use Helstern\Nomsky\Grammar\Expressions\Option;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
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
     * @param Alternation $expression
     * @return EndAlternationAction
     */
    public function dispatchVisitAlternation(Alternation $expression)
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
     * @param Option $expression
     * @return EndOptionAction
     */
    public function dispatchVisitOption(Option $expression)
    {
        $this->visitor->startVisitOption($expression);

        $nextVisitAction = new EndOptionAction($expression, $this->visitor);
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
     * @param Repetition $expression
     * @return EndRepetitionAction
     */
    public function dispatchVisitRepetition(Repetition $expression)
    {
        $this->visitor->startVisitRepetition($expression);

        $nextVisitAction = new EndRepetitionAction($expression, $this->visitor);
        return $nextVisitAction;
    }
}
