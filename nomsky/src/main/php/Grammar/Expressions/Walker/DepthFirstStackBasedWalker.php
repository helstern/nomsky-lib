<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\ExpressionAggregate;
use Helstern\Nomsky\Grammar\Expressions\ExpressionIterable;
use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitAction;
use Helstern\Nomsky\Grammar\Expressions\Walker\WalkState\KeepWalkingStateMachine;
use Helstern\Nomsky\Grammar\Expressions\Walker\WalkState\WalkStateMachine;

class DepthFirstStackBasedWalker extends AbstractStackBasedWalker implements Walker
{
    /** @var WalkStateMachine */
    protected $walkStateMachine;

    /**'
     * @param WalkStateMachine $customWalkStateMachine
     */
    public function __construct(WalkStateMachine $customWalkStateMachine = null)
    {
        $this->walkStateMachine = $customWalkStateMachine;
        if (is_null($customWalkStateMachine)) {
            $this->walkStateMachine = $this->getDefaultWalkStateMachine();
        }
    }

    public function getDefaultWalkStateMachine()
    {
        return KeepWalkingStateMachine::singletonInstance();
    }

    /**
     * @return WalkStateMachine
     */
    protected function getWalkStateMachine()
    {
        return $this->walkStateMachine;
    }

    /**
     * @param VisitAction $futureVisitAction
     * @param Expression $lastVisitedExpression
     * @return \SplDoublyLinkedList
     */
    protected function prepareQueueForFutureVisits(VisitAction $futureVisitAction = null, Expression $lastVisitedExpression)
    {
        $stackForExpressions = new \SplDoublyLinkedList();

        if ($lastVisitedExpression instanceof ExpressionIterable) {

            foreach ($lastVisitedExpression as $futureVisit) {
                $stackForExpressions->push($futureVisit);
            }
        } else if ($lastVisitedExpression instanceof ExpressionAggregate) {
            $futureVisit = $lastVisitedExpression->getExpression();
            $stackForExpressions->push($futureVisit);
        }

        if ($futureVisitAction instanceof VisitAction) {
            $stackForExpressions->push($futureVisitAction);
        }

        return $stackForExpressions;
    }
}
