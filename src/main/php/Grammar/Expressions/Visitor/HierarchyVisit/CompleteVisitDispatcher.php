<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;

use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;


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
     * @param Choice $expression
     *
     * @return EndChoiceAction
     */
    public function dispatchVisitChoice(Choice $expression)
    {
        $this->visitor->startVisitChoice($expression);

        $nextVisitAction = new EndChoiceAction($expression, $this->visitor);
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
     * @param Optional $expression
     *
     * @return EndOptionalAction
     */
    public function dispatchVisitOptional(Optional $expression)
    {
        $this->visitor->startVisitOptional($expression);

        $nextVisitAction = new EndOptionalAction($expression, $this->visitor);
        return $nextVisitAction;
    }

    /**
     * @param Concatenation $expression
     *
     * @return EndConcatenationAction
     */
    public function dispatchVisitConcatenation(Concatenation $expression)
    {
        $this->visitor->startVisitConcatenation($expression);

        $nextVisitAction = new EndConcatenationAction($expression, $this->visitor);
        return $nextVisitAction;
    }

    /**
     * @param Repetition $expression
     *
    * @return EndRepetitionAction
     */
    public function dispatchVisitRepetition(Repetition $expression)
    {
        $this->visitor->startVisitRepetition($expression);

        $nextVisitAction = new EndRepetitionAction($expression, $this->visitor);
        return $nextVisitAction;
    }
}
