<?php namespace Helstern\Nomsky\Grammar\Converters;

class CombinatorAdapterForExpressionGroup
{
    /** @var Combinator */
    protected $combinator;

    /** @var bool */
    protected $expressionIsChildOfGroup;

    public function __construct(Combinator $combinator, $expressionIsChildOfGroup)
    {
        $this->combinator      = $combinator;
        $this->expressionIsChildOfGroup    = $expressionIsChildOfGroup;
    }

    /**
     * @return Combinator
     */
    public function getCombinator()
    {
        return $this->combinator;
    }

    /**
     * @return bool
     */
    public function expressionIsChildOfGroup()
    {
        return $this->expressionIsChildOfGroup;
    }

    /**
     * @param CombinatorAdapterForExpressionGroup $otherAdapter
     * @return Combination
     */
    public function createCombination(CombinatorAdapterForExpressionGroup $otherAdapter)
    {
        $combinator         = $this->getCombinator();
        $otherCombinator    = $otherAdapter->getCombinator();

        return $combinator->createCombinationWith($otherCombinator);
    }
}
