<?php namespace Helstern\Nomsky\Grammar\Production\Expression;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Walker\WalkState\WalkStateMachine;
use Helstern\Nomsky\Grammar\Production\Expression\SymbolPredicateAdapter as PredicateExpressionAdapter;
use Helstern\Nomsky\Grammar\Symbol\Predicate\SymbolPredicate;

class FindMaxStateMachine implements WalkStateMachine
{
    /** @var SymbolPredicate */
    protected $predicateAdapter;

    /** @var Expression[] */
    protected $foundExpressions = array();

    /** @var int */
    protected $maxMatches;

    /** @var int */
    protected $matchesLeft;

    public function __construct(SymbolPredicate $symbolPredicate, $maxMatches)
    {
        $this->predicateAdapter  = new PredicateExpressionAdapter($symbolPredicate);
        $this->maxMatches       = $maxMatches;
        $this->matchesLeft      = $maxMatches;
    }

    /**
     * @return int
     */
    public function getMatchesLeft()
    {
        return $this->matchesLeft;
    }

    /**
     * @return int
     */
    public function getMaxMatches()
    {
        return $this->matchesLeft;
    }

    /**
     * @return Expression[]
     */
    public function getExpressions()
    {
        return $this->foundExpressions;
    }

    /**
     * @param Expression $root
     * @return boolean
     */
    public function startWalking(Expression $root)
    {
        return $this->matchesLeft == 0 ? false : true;
    }

    /**
     * @param Expression $lastVisited
     * @return boolean
     */
    public function continueWalkingAfterVisit(Expression $lastVisited)
    {
        if ($this->matchesLeft == 0) {
            return false;
        }

        $found = $this->predicateAdapter->matchExpression($lastVisited);
        if ($found) {
            $this->matchesLeft--;
            $this->foundExpressions[] = $lastVisited;
        }

        return $this->matchesLeft == 0 ? false : true;
    }
}
