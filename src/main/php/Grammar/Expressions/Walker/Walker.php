<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker;

use Helstern\Nomsky\Grammar\Expressions\Expression;
use Helstern\Nomsky\Grammar\Expressions\Walker\Visit\VisitDispatcher;

interface Walker
{
    /**
     * @param Expression $expression
     * @param VisitDispatcher $visitActionDispatch
     * @return boolean
     */
    public function walk(Expression $expression, VisitDispatcher $visitActionDispatch);
}
