<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker;

use Helstern\Nomsky\Grammar\Expressions\Alternative;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\OptionalItem;
use Helstern\Nomsky\Grammar\Expressions\OptionalList;
use Helstern\Nomsky\Grammar\Expressions\Sequence;
use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitDispatcher;

class VisitDispatcherRoute
{
    /** @var VisitDispatcher */
    protected $actionDispatcher;

    /**
     * @param VisitDispatcher $actionDispatcher
     */
    public function __construct(VisitDispatcher $actionDispatcher)
    {
        $this->actionDispatcher = $actionDispatcher;
    }

    /**
     * @param Expression $expression
     * @return \Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitAction
     */
    public function dispatchVisit(Expression $expression)
    {
        /** @var \Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitAction $futureVisitAction */
        $futureVisitAction  = null;
        $actionDispatcher   = $this->actionDispatcher;

        if ($expression instanceof Alternative) {
            $futureVisitAction = $actionDispatcher->dispatchVisitAlternation($expression);
        } else if ($expression instanceof Group) {
            $futureVisitAction = $actionDispatcher->dispatchVisitGroup($expression);
        } else if ($expression instanceof OptionalItem) {
            $futureVisitAction = $actionDispatcher->dispatchVisitOptionalItem($expression);
        } else if ($expression instanceof OptionalList) {
            $futureVisitAction = $actionDispatcher->dispatchVisitOptionalList($expression);
        } else if ($expression instanceof Sequence) {
            $futureVisitAction = $actionDispatcher->dispatchVisitSequence($expression);
        } else {
            $futureVisitAction = $actionDispatcher->dispatchVisitExpression($expression);
        }

        return $futureVisitAction;
    }
}
