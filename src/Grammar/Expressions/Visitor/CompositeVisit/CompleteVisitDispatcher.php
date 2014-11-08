<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor\CompositeVisit;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;

use Helstern\Nomsky\Grammar\Expressions\Option;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
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
     * @param Alternation $expression
     * @return null
     */
    public function dispatchVisitAlternation(Alternation $expression)
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
     * @param Option $expression
     * @return null
     */
    public function dispatchVisitOption(Option $expression)
    {
        $this->visitor->visitOption($expression);
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
     * @param Repetition $expression
     * @return null
     */
    public function dispatchVisitRepetition(Repetition $expression)
    {
        $this->visitor->visitRepetition($expression);
        return null;
    }
}
