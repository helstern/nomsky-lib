<?php namespace Helstern\Nomsky\Grammar\Expressions;

//TODO extending Expression is a hack, needs investigation

interface ExpressionIterable extends \IteratorAggregate, \Countable
{
    /**
     * @return  \Iterator|Expression[]
     */
    public function getIterator();

    /**
     * @return array|Expression[]
     */
    public function toArray();
}
