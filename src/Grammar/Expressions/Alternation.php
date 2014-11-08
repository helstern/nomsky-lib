<?php namespace Helstern\Nomsky\Grammar\Expressions;

class Alternation implements Expression, ExpressionIterable
{
    /** @var Expression[] */
    protected $expressions;

    /**
     * @param Expression $anAlternative
     * @param array|Expression[] $otherAlternatives
     */
    public function __construct(Expression $anAlternative, array $otherAlternatives = null)
    {
        $this->expressions = array($anAlternative);
        if (is_null($otherAlternatives) == false) {
            $this->expressions = array_merge($this->expressions, $otherAlternatives);
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
