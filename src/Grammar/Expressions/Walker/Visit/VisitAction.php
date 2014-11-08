<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\Visit;

use Helstern\Nomsky\Grammar\Expressions\Expression;

interface VisitAction
{
    /**
     * @return Expression
     */
    public function getExpression();

    /**
     * @return boolean
     */
    public function wasExecuted();

    /**
     * @return boolean
     */
    public function execute();
}
