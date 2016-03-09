<?php namespace Helstern\Nomsky\Grammar\Expressions;

class Concatenation implements Expression, ExpressionIterable
{
    /** @var Expression[] */
    private $expressions;

    /**
     * @param Expression $head
     * @param array|Expression[] $tail
     */
    public function __construct(Expression $head, array $tail = null)
    {
        $this->expressions = array($head);
        if (is_null($tail) == false) {
            $this->expressions = array_merge($this->expressions, $tail);
        }
    }

    public function count()
    {
        return count($this->expressions);
    }

    /**
     * @return Expression[]
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->expressions);
    }

    public function toArray()
    {
        return $this->expressions;
    }
}
