<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Alternation;
use Helstern\Nomsky\Grammar\Expressions\Sequence;

class CombinatorForAlternation implements Combinator
{
    /**
     * @param array $itemsInExpression
     * @return Alternation
     */
    public function createExpression(array $itemsInExpression)
    {
        return new Alternation(array_shift($itemsInExpression), $itemsInExpression);
    }

    public function createCombinationWith(Combinator $nextCombinator)
    {
        $combination = new CombinationWithAlternation($this, $nextCombinator);
        return $combination;
    }

    /**
     * If alternation is combined with alternation (this alternation), the result is an alternation
     *
     * @return boolean
     */
    public function transformsWhenCombinedWithAlternation()
    {
        return false;
    }

    /**
     * @param array $alternationItems
     * @param array $ownAlternationItems
     * @return array
     */
    public function combineWithAlternation(array $alternationItems, array $ownAlternationItems)
    {
        $items = array_merge($alternationItems, $ownAlternationItems);

        return $items;
    }

    /**
     * If sequence is combined with alternation, the result is an alternation
     *
     * @return boolean
     */
    public function transformsWhenCombinedWithSequence()
    {
        return true;
    }

    /**
     * @param array $sequenceItems
     * @param array $ownAlternationItems
     * @return array
     */
    public function combineWithSequence(array $sequenceItems, array $ownAlternationItems)
    {
        $result = array();
        do {
            $tail = current($ownAlternationItems);
            next($ownAlternationItems);
            $items = array_merge($sequenceItems, array($tail));

            $result[] = new Sequence(array_shift($items), $items);
        } while (!is_null(key($ownAlternationItems)));

        return $result;
    }
}
