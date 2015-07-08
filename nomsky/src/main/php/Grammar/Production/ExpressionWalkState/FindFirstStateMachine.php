<?php namespace Helstern\Nomsky\Grammar\Production\ExpressionWalkState;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Walker\WalkState\WalkStateMachine;
use Helstern\Nomsky\Grammar\Production\SymbolPredicate\ExpressionAdapter as PredicateExpressionAdapter;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;

class FindFirstStateMachine implements WalkStateMachine
{
    /** @var SymbolPredicate */
    protected $predicateAdapter;

    /** @var Expression */
    protected $foundExpression;

    public function __construct(SymbolPredicate $symbolPredicate)
    {
        $this->predicateAdapter = new PredicateExpressionAdapter($symbolPredicate);
    }

    /**
     * @return Expression
     */
    public function getExpression()
    {
        return $this->foundExpression;
    }

    /**
     * @param Expression $root
     * @return boolean
     */
    public function startWalking(Expression $root)
    {
        if (is_null($this->foundExpression)) {
            return true;
        }

        return false;
    }

    /**
     * @param Expression $lastVisited
     * @return boolean
     */
    public function continueWalkingAfterVisit(Expression $lastVisited)
    {
        if (!is_null($this->foundExpression)) {
            return false;
        }

        $found = $this->predicateAdapter->matchExpression($lastVisited);
        if ($found) {
            $this->foundExpression = $lastVisited;
        }

        return !$found;
    }
}
