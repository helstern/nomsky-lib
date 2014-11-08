<?php namespace Helstern\Nomsky\Grammar\Converters;

class CombinationWithSequence implements Combination
{
    /** @var Combinator */
    protected $sequenceCombinator;

    /** @var Combinator */
    protected $otherCombinator;

    public function __construct(Combinator $sequenceCombinator, Combinator $otherCombinator)
    {
        $this->sequenceCombinator   = $sequenceCombinator;
        $this->otherCombinator      = $otherCombinator;
    }

    /**
     * @return Combinator
     */
    public function getSequenceCombinator()
    {
        return $this->sequenceCombinator;
    }

    /**
     * @return Combinator
     */
    public function getOtherCombinator()
    {
        return $this->otherCombinator;
    }

    public function getCombinator()
    {
        if ($this->otherCombinator->transformsWhenCombinedWithSequence()) {
            return $this->otherCombinator;
        } else {
            return $this->sequenceCombinator;
        }
    }

    /**
     * @param array $sequenceItems
     * @param array $otherCombinatorItems
     * @return array
     */
    public function combine(array $sequenceItems, array $otherCombinatorItems)
    {
        $combinations = $this->otherCombinator->combineWithSequence($sequenceItems, $otherCombinatorItems);
        return $combinations;
    }
}
