<?php namespace Helstern\Nomsky\Grammar\Expressions;

interface ExpressionIterable extends \IteratorAggregate, \Countable
{
    /**
     * @return \Iterator|Expression[]
     */
    public function getIterator();

    /**
     * @return array|Expression[]
     */
    public function toArray();
}
