<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Sequence;

class CombinatorForSequence  implements Combinator
{
    /**
     * @param array $itemsInExpression
     * @return Sequence
     */
    public function createExpression(array $itemsInExpression)
    {
        return new Sequence(array_shift($itemsInExpression), $itemsInExpression);
    }
    /**
     * @param Combinator $nextCombinator
     * @return Combination|CombinationWithSequence
     */
    public function createCombinationWith(Combinator $nextCombinator)
    {
        $combination = new CombinationWithSequence($this, $nextCombinator);
        return $combination;
    }

    /**
     * if alternation is combined with sequence, result is an alternation
     *
     * @return boolean
     */
    public function transformsWhenCombinedWithAlternation()
    {
        return true;
    }

    /**
     * @param array $alternationItems
     * @param array $ownSequenceItems
     * @return array
     */
    public function combineWithAlternation(array $alternationItems, array $ownSequenceItems)
    {
        $result = array();

        do {
            $head = current($alternationItems);
            next($alternationItems);
            $result[] = new Sequence($head, $ownSequenceItems);
        } while (!is_null(key($alternationItems)));

        return $result;
    }

    /**
     * if sequence is combined with a sequence, the result is a sequence
     *
     * @return boolean
     */
    public function transformsWhenCombinedWithSequence()
    {
        return false;
    }

    /**
     * @param array $sequenceItems
     * @param array $ownSequenceItems
     * @return array
     */
    public function combineWithSequence(array $sequenceItems, array $ownSequenceItems)
    {
        $items   = array_merge($sequenceItems, $ownSequenceItems);

        return $items;
//        $result = new Sequence(array_shift($items), $items);
//
//        return $result;
    }
}
