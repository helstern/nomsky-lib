<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;
use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitAction;

class EndAlternationAction implements VisitAction
{
    /** @var  Alternation */
    protected $expression;

    /** @var HierarchyVisitor */
    protected $visitor;

    /** @var bool */
    protected $executed = false;

    public function __construct(Alternation $expression, HierarchyVisitor $visitor)
    {
        $this->expression   = $expression;
        $this->visitor      = $visitor;
    }

    /**
     * @return Alternation
     */
    public function getExpression()
    {
        return $this->expression;
    }

    /**
     * @return boolean
     */
    public function wasExecuted()
    {
        return $this->executed;
    }

    /**
     * @return boolean
     */
    public function execute()
    {
        if ($this->executed) {
            return false;
        }

        $this->visitor->endVisitAlternation($this->expression);
        $this->executed = true;

        return true;
    }
}
