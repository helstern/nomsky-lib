<?php namespace Helstern\Nomsky\Grammar\Converters;

use Helstern\Nomsky\Grammar\Expressions\Expression;

interface Combinator
{
    /**
     * @param array $itemsInExpression
     * @return Expression
     */
    public function createExpression(array $itemsInExpression);

    /**
     * Creates the combination with the first firstCombinator
     *
     * @param Combinator $firstCombinator
     * @return Combination
     */
    public function createCombinationWith(Combinator $firstCombinator);

    /**
     * @return boolean
     */
    public function transformsWhenCombinedWithAlternation();

    /**
     * @param array $alternationItems
     * @param array $ownItems
     * @return array
     */
    public function combineWithAlternation(array $alternationItems, array $ownItems);

    /**
     * @return boolean
     */
    public function transformsWhenCombinedWithSequence();

    /**
     * @param array $sequenceItems
     * @param array $ownItems
     * @return array
     */
    public function combineWithSequence(array $sequenceItems, array $ownItems);
}
