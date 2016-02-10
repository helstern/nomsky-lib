<?php namespace Helstern\Nomsky\Grammar\Production\ExpressionWalkState;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Walker\WalkState\WalkStateMachine;
use Helstern\Nomsky\Grammar\Production\SymbolPredicate\ExpressionAdapter as PredicateExpressionAdapter;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;

class CountAllStateMachine implements WalkStateMachine
{
    /** @var PredicateExpressionAdapter */
    protected $predicateAdapter;

    /** @var int */
    protected $count;

    public function __construct(SymbolPredicate $symbolPredicate)
    {
        $this->predicateAdapter = new PredicateExpressionAdapter($symbolPredicate);
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param Expression $root
     * @return boolean
     */
    public function startWalking(Expression $root)
    {
        return true;
    }

    /**
     * @param Expression $lastVisited
     * @return boolean
     */
    public function continueWalkingAfterVisit(Expression $lastVisited)
    {
        $found = $this->predicateAdapter->matchExpression($lastVisited);
        if ($found) {
            $this->count++;
        }

        return true;
    }
}
