<?php namespace Helstern\Nomsky\Grammar\Converters;

class CombinationWithAlternation implements Combination
{
    /** @var Combinator */
    protected $alternationCombinator;

    /** @var Combinator */
    protected $otherCombinator;

    public function __construct(Combinator $alternationCombinator, Combinator $otherCombinator)
    {
        $this->alternationCombinator    = $alternationCombinator;
        $this->otherCombinator          = $otherCombinator;
    }

    /**
     * @return Combinator
     */
    public function getAlternationCombinator()
    {
        return $this->alternationCombinator;
    }

    /**
     * @return Combinator
     */
    public function otherCombinator()
    {
        return $this->otherCombinator;
    }

    public function getCombinator()
    {
        if ($this->otherCombinator->transformsWhenCombinedWithAlternation()) {
            return $this->otherCombinator;
        } else {
            return $this->alternationCombinator;
        }
    }

    /**
     * @param array $alternationItems
     * @param array $combinatorItems
     * @return array
     */
    public function combine(array $alternationItems, array $combinatorItems)
    {
        $combinations = $this->otherCombinator->combineWithAlternation($alternationItems, $combinatorItems);
        return $combinations;
    }
}
