<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor\CompositeVisit;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;

use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;

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
     * @param Choice $expression
     *
     * @return null
     */
    public function dispatchVisitChoice(Choice $expression)
    {
        $this->visitor->visitChoice($expression);
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
     * @param Optional $expression
     *
     * @return null
     */
    public function dispatchVisitOptional(Optional $expression)
    {
        $this->visitor->visitOptional($expression);
        return null;
    }

    /**
     * @param Concatenation $expression
     *
     * @return null
     */
    public function dispatchVisitConcatenation(Concatenation $expression)
    {
        $this->visitor->visitConcatenation($expression);
        return null;
    }

    /**
     * @param Repetition $expression
     *
    * @return null
     */
    public function dispatchVisitRepetition(Repetition $expression)
    {
        $this->visitor->visitRepetition($expression);
        return null;
    }
}
