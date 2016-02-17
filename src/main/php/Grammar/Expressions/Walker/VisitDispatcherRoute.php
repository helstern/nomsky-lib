<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker;

use Helstern\Nomsky\Grammar\Expressions\Choice;
use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Group;
use Helstern\Nomsky\Grammar\Expressions\Optional;
use Helstern\Nomsky\Grammar\Expressions\Repetition;
use Helstern\Nomsky\Grammar\Expressions\Concatenation;
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

        if ($expression instanceof Choice) {
            $futureVisitAction = $actionDispatcher->dispatchVisitChoice($expression);
        } else if ($expression instanceof Group) {
            $futureVisitAction = $actionDispatcher->dispatchVisitGroup($expression);
        } else if ($expression instanceof Optional) {
            $futureVisitAction = $actionDispatcher->dispatchVisitOptional($expression);
        } else if ($expression instanceof Repetition) {
            $futureVisitAction = $actionDispatcher->dispatchVisitRepetition($expression);
        } else if ($expression instanceof Concatenation) {
            $futureVisitAction = $actionDispatcher->dispatchVisitConcatenation($expression);
        } else {
            $futureVisitAction = $actionDispatcher->dispatchVisitExpression($expression);
        }

        return $futureVisitAction;
    }
}
