<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit;

use Helstern\Nomsky\Grammar\Expressions\Option;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;
use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitAction;

class EndOptionAction implements VisitAction
{
    /** @var Option */
    protected $expression;

    /** @var HierarchyVisitor */
    protected $visitor;

    /** @var bool */
    protected $executed = false;

    public function __construct(Option $expression, HierarchyVisitor $visitor)
    {
        $this->expression   = $expression;
        $this->visitor      = $visitor;
    }

    /**
     * @return Option
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

        $this->visitor->endVisitOption($this->expression);
        $this->executed = true;

        return true;
    }
}
