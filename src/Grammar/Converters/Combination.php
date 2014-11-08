<?php namespace Helstern\Nomsky\Grammar\Converters;

interface Combination
{
    /**
     * @return Combinator
     */
    public function getCombinator();

    /**
     * @param array $rightGroup
     * @param array $leftGroup
     * @return array
     */
    public function combine(array $rightGroup, array $leftGroup);
}
