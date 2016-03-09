<?php namespace Helstern\Nomsky\GrammarAnalysis\Production;

interface NormalizedSet extends \Countable, \IteratorAggregate
{
    /**
     * @return \ArrayIterator|NormalizedProduction[]
     */
    public function getIterator();

    /**
     * @param NormalizedProduction $symbol
     *
     * @return boolean
     */
    public function remove(NormalizedProduction $symbol);

    /**
     * @param NormalizedProduction $symbol
     *
     * @return boolean
     */
    public function add(NormalizedProduction $symbol);

    /**
     * @param NormalizedSet $all
     *
     * @return mixed
     */
    public function addAll(NormalizedSet $all);

    /**
     * @param NormalizedProduction $symbol
     *
     * @return boolean
     */
    public function contains(NormalizedProduction $symbol);

    /**
     * @return array|NormalizedProduction[]
     */
    public function toList();
}
