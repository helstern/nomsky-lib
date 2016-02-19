<?php namespace Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisit;

use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Visitor\HierarchyVisitor;
use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitAction;

class EndOptionalAction implements VisitAction
{
    /** @var Optional */
    protected $expression;

    /** @var HierarchyVisitor */
    protected $visitor;

    /** @var bool */
    protected $executed = false;

    public function __construct(Optional $expression, HierarchyVisitor $visitor)
    {
        $this->expression   = $expression;
        $this->visitor      = $visitor;
    }

    /**
     * @return Optional
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

        $this->visitor->endVisitOptional($this->expression);
        $this->executed = true;

        return true;
    }
}
