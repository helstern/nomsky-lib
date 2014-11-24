<?php namespace Helstern\Nomsky\Grammar\Converters;

interface Combination
{
    /**
     * @return Combinator
     */
    public function getCombinator();

    /**
     * @param array $leftGroup
     * @param array $rightGroup
     * @return array
     */
    public function combine(array $leftGroup, array $rightGroup);
}
