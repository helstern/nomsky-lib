<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\Visit;

use Helstern\Nomsky\Grammar\Expressions\Expression;

interface VisitAction
{
    /**
     * Returns the expression that will be visited when execute is called
     *
     * @return Expression
     */
    public function getExpression();

    /**
     * @return boolean
     */
    public function wasExecuted();

    /**
     * Dispatch the visit if the action was not dispatched before
     *
     * @return boolean
     */
    public function execute();
}
