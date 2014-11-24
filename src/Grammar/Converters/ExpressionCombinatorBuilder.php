<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;

class ExpressionCombinatorBuilder
{
    /** @var array|Expression[] */
    protected $children;

    /** @var Combinator  */
    protected $combinator;

    /**
     * @return Combinator
     */
    public function getCombinator()
    {
        return $this->combinator;
    }

    public function setCombinator(Combinator $combinator)
    {
        $this->combinator = $combinator;
    }

    /**
     * @return array|\Helstern\Nomsky\Grammar\Expressions\Expression[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    public function setChildren(array $children)
    {
        $this->children = $children;
    }

    /**
     * @return Expression
     */
    public function build()
    {
        $expression = $this->combinator->createExpression($this->children);
        return $expression;
    }

    /**
     * @return Group
     */
    public function buildGroup()
    {
        $expression = $this->build();
        $group = new Group($expression);

        return $group;
    }
}
