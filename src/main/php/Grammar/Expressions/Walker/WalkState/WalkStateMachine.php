<?php namespace Helstern\Nomsky\Grammar\Expressions\Walker\WalkState;

use Helstern\Nomsky\Grammar\Expressions\Expression;

interface WalkStateMachine
{
    /**
     * @param Expression $root
     * @return boolean
     */
    public function startWalking(Expression $root);

    /**
     * @param Expression $lastVisited
     * @return boolean
     */
    public function continueWalkingAfterVisit(Expression $lastVisited);
}
