<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitAction;
use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitDispatcher;
use Helstern\Nomsky\Grammar\Expressions\Walker\WalkState\WalkStateMachine;

abstract class AbstractStackBasedWalker implements Walker
{
    public function walk(Expression $expression, VisitDispatcher $visitActionDispatcher)
    {
        $walkState = $this->getWalkStateMachine();
        if (! $walkState->startWalking($expression)) {
            return false;
        }

        /** @var Expression[] $stackOfExpressions */
        $stackOfExpressions = array($expression);

        do {
            $expression = array_pop($stackOfExpressions);

            if ($expression instanceof VisitAction) {
                $expression->execute();
                continue;
            }

            $futureVisitAction = $this->dispatchVisit($expression, $visitActionDispatcher);

            if (! $walkState->continueWalkingAfterVisit($expression)) {
                return false;
            }

            //reverse push the children linked list, such that the first child is last in $stackOfExpressions
            $newDoublyLinkedList = $this->prepareQueueForFutureVisits($futureVisitAction, $expression);
            while (! $newDoublyLinkedList->isEmpty()) {
                array_push($stackOfExpressions, $newDoublyLinkedList->pop());
            }

        } while (!is_null(key($stackOfExpressions)));

        return true;
    }

    /**
     * @param Expression $expression
     * @param VisitDispatcher $visitActionDispatcher
     * @return VisitAction|null
     */
    protected function dispatchVisit(Expression $expression, VisitDispatcher $visitActionDispatcher)
    {
        $visitActionRoute  = $this->createDispatchRoute($expression, $visitActionDispatcher);
        $futureVisitAction = $visitActionRoute->dispatchVisit($expression);

        return $futureVisitAction;
    }

    /**
     * @param Expression $expression
     * @param VisitDispatcher $visitActionDispatcher
     * @return VisitDispatcherRoute
     */
    protected function createDispatchRoute(Expression $expression, VisitDispatcher $visitActionDispatcher)
    {
        $dispatchRoute = new VisitDispatcherRoute($visitActionDispatcher);
        return $dispatchRoute;
    }

    /**
     * @return WalkStateMachine
     */
    abstract protected function getWalkStateMachine();

    /**
     * @param VisitAction $futureVisitAction
     * @param Expression $lastVisitedExpression
     * @return \SplDoublyLinkedList
     */
    abstract protected function prepareQueueForFutureVisits(VisitAction $futureVisitAction = null, Expression $lastVisitedExpression);
}
